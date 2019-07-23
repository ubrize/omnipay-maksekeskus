<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus\Messages;

use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Maksekeskus\Exceptions\FailedValidationResponse;
use Omnipay\Maksekeskus\Support\Mac;
use Psr\Http\Message\ResponseInterface as PsrResponse;

abstract class AbstractRequest extends CommonAbstractRequest
{
    /**
     * @var string
     */
    protected $testApiEndpoint = 'https://api-test.maksekeskus.ee/v1/';

    /**
     * @var string
     */
    protected $testRedirectEndpoint = 'https://payment-test.maksekeskus.ee/pay/1/link.html';

    /**
     * @var string
     */
    protected $testPostEndpoint = 'https://payment-test.maksekeskus.ee/pay/1/signed.html';

    /**
     * @var string
     */
    protected $liveApiEndpoint = 'https://api.maksekeskus.ee/v1/';

    /**
     * @var string
     */
    protected $liveRedirectEndpoint = 'https://payment.maksekeskus.ee/pay/1/link.html';

    /**
     * @var string
     */
    protected $livePostEndpoint = 'https://payment.maksekeskus.ee/pay/1/signed.html';

    /**
     * @return string
     */
    public function getApiEndpoint(): string
    {
        return $this->getTestMode() ? $this->testApiEndpoint : $this->liveApiEndpoint;
    }

    /**
     * @return string
     */
    public function getRedirectEndpoint(): string
    {
        return $this->getTestMode() ? $this->testRedirectEndpoint : $this->liveRedirectEndpoint;
    }

    /**
     * @return string
     */
    public function getPostEndpoint(): string
    {
        return $this->getTestMode() ? $this->testPostEndpoint : $this->livePostEndpoint;
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     */
    public function getPathParts(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->getParameter('shopId');
    }

    /**
     * @param string $shopId
     * @return AbstractRequest
     */
    public function setShopId(string $shopId): self
    {
        return $this->setParameter('shopId', $shopId);
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    /**
     * @param string $secretKey
     * @return AbstractRequest
     */
    public function setSecretKey(string $secretKey): self
    {
        return $this->setParameter('secretKey', $secretKey);
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->getParameter('country');
    }

    /**
     * @param string $country
     * @return AbstractRequest
     */
    public function setCountry(string $country): self
    {
        return $this->setParameter('country', $country);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->getParameter('locale');
    }

    /**
     * @param string $locale
     * @return AbstractRequest
     */
    public function setLocale(string $locale): self
    {
        return $this->setParameter('locale', $locale);
    }

    /**
     * @return array
     */
    public function getAuthenticationHeaders(): array
    {
        $authentication = implode(':', [
            $this->getShopId(),
            $this->getSecretKey()
        ]);

        return [
            'Authorization' => 'Basic ' . base64_encode($authentication)
        ];
    }

    /**
     * @return array
     */
    protected function getRequestHeaders(): array
    {
        $headers = [];

        if ($this->getHttpMethod() === 'POST') {
            $headers['Content-Type'] = 'application/json';
        }

        return $headers;
    }

    /**
     * @return array
     */
    protected function getHeaders(): array
    {
        return array_merge(
            $this->getRequestHeaders(),
            $this->getAuthenticationHeaders()
        );
    }

    /**
     * @return string
     */
    protected function getRequestUrl(): string
    {
        return implode('/', [
            rtrim($this->getApiEndpoint(), '/'),
            ltrim($this->getPathParts(), '/'),
        ]);
    }

    /**
     * @param array $data
     * @return array|string|null
     */
    protected function getRequestBody(array $data)
    {
        if ($this->getHttpMethod() === 'GET') {
            return $data ? http_build_query($data, '', '&') : null;
        }

        return json_encode($data);
    }

    /**
     * @param mixed $data
     * @return ResponseInterface
     * @throws FailedValidationResponse
     */
    public function sendData($data)
    {
        if ($this->shouldValidate()) {
            $this->validateMac();
        }

        $response = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getRequestUrl(),
            $this->getHeaders(),
            $this->getRequestBody($data)
        );

        return $this->createResponse($this, $response);
    }

    /**
     * @return bool
     */
    public function shouldValidate()
    {
        return true;
    }

    /**
     * @return array
     */
    public function extractRequestData()
    {
        if ($this->httpRequest->getMethod() == 'POST') {
            $data = $this->httpRequest->request->all();
        } else {
            $data = $this->httpRequest->query->all();
        }

        return $data ?? [];
    }

    /**
     * @return array
     */
    public function extractRequestJson(): array
    {
        $json = $this->extractRequestData()['json'] ?? '{}';

        return json_decode($json, true);
    }

    /**
     * @return string|null
     */
    public function extractRequestMac(): ?string
    {
        return $this->extractRequestData()['mac'] ?? null;
    }

    /**
     * @return bool
     * @throws FailedValidationResponse
     */
    public function validateMac(): bool
    {
        $received = $this->extractRequestMac();
        $expected = (new Mac($this->extractRequestJson(), $this->getSecretKey()))->hash();

        if ($received === $expected) {
            return true;
        }

        throw new FailedValidationResponse('Mac validation failed');
    }

    /**
     * @return array
     */
    public function parseRequest()
    {
        return [
            'mac' => $this->httpRequest->get('mac'),
            'json' => json_decode($this->httpRequest->get('json'), false),
        ];
    }

    /**
     * @param RequestInterface $request
     * @param PsrResponse $response
     * @return ResponseInterface
     */
    protected function createResponse(RequestInterface $request, PsrResponse $response): ResponseInterface
    {
        $object = $this->getResponseObject();

        return new $object($request, $response);
    }

    /**
     * @return string
     */
    protected function getResponseObject(): string
    {
        return Response::class;
    }
}
