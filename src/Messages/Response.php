<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus\Messages;

use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Response extends CommonAbstractResponse
{
    /**
     * @var ResponseInterface
     */
    protected $httpResponse;

    /**
     * @var AbstractRequest
     */
    protected $request;

    /**
     * AbstractResponse constructor.
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->httpResponse = $response;
        $data = json_decode($response->getBody()->getContents(), true);
        parent::__construct($request, $data);
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->httpResponse->getStatusCode() === 200;
    }
}
