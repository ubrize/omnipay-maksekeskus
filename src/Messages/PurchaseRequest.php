<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus\Messages;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

class PurchaseRequest extends AbstractRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $data = [
            'shop' => $this->getShopId(),
            'amount' => $this->getAmount(),
            'reference' => $this->getTransactionReference(),
            'country' => $this->getCountry(),
            'locale' => $this->getLocale(),
            'preferred_method' => $this->getPreferredMethod(),
        ];

        return $data;
    }

    /**
     * @param string $method
     * @return PurchaseRequest|AbstractRequest
     */
    public function setPreferredMethod(string $method): self
    {
        return $this->setParameter('preferredMethod', $method);
    }

    /**
     * @return string|null
     */
    public function getPreferredMethod(): ?string
    {
        return $this->getParameter('preferredMethod');
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->getParameter('locale');
    }

    /**
     * @return string
     * @throws InvalidRequestException
     */
    public function getRedirectUrl(): string
    {
        $query = $this->getData() ? http_build_query($this->getData(), '', '&') : '';

        return $this->getRedirectEndpoint() . '?' . $query;
    }

    /**
     * @return string
     */
    public function getPostUrl(): string
    {
        return $this->getPostEndpoint();
    }

    /**
     * @param mixed $data
     * @return ResponseInterface
     */
    public function sendData($data): ResponseInterface
    {
        return new PurchaseResponse($this, new \GuzzleHttp\Psr7\Response(200));
    }
}
