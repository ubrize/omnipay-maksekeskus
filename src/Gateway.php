<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Maksekeskus\Messages\CompleteRequest;
use Omnipay\Maksekeskus\Messages\CreateTransactionRequest;
use Omnipay\Maksekeskus\Messages\PurchaseRequest;
use Omnipay\Maksekeskus\Messages\ShopConfigurationRequest;

class Gateway extends AbstractGateway
{
    /**
     * @var string[] 
     */
    protected $defaults = [
        'currency' => 'EUR',
        'country' => 'ee',
        'locale' => 'en',
        'shopId' => 'random',
        'secretKey' => 'random',
        'apiEndpoint' => 'https://api.maksekeskus.ee/v1/',
        'redirectEndpoint' => 'https://payment.maksekeskus.ee/pay/1/link.html',
        'postEndpoint' => 'https://payment.maksekeskus.ee/pay/1/signed.html',
    ];
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Maksekeskus';
    }

    /**
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return $this->defaults;
    }

    /**
     * @param array $options
     * @return ShopConfigurationRequest|AbstractRequest
     */
    public function getShopConfiguration(array $options = []): ShopConfigurationRequest
    {
        return $this->createRequest(ShopConfigurationRequest::class, $options);
    }

    /**
     * @param array $options
     * @return PurchaseRequest|AbstractRequest
     */
    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    /**
     * @param array $options
     * @return CompleteRequest|RequestInterface
     */
    public function completePurchase(array $options = []): RequestInterface
    {
        return $this->createRequest(CompleteRequest::class, $options);
    }

    /**
     * @param array $options
     * @return CreateTransactionRequest|AbstractRequest
     */
    public function authorize(array $options = []): CreateTransactionRequest
    {
        return $this->createRequest(CreateTransactionRequest::class, $options);
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
     * @return $this
     */
    public function setCountry(string $country): self
    {
        return $this->setParameter('country', $country);
    }

    /**
     * @return string|null
     */
    public function getLocale(): string
    {
        return $this->getParameter('locale');
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale(string $locale): self
    {
        return $this->setParameter('locale', $locale);
    }

    /**
     * @return string|null
     */
    public function getApiEndpoint(): string
    {
        return $this->getParameter('apiEndpoint');
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setApiEndpoint(string $url): self
    {
        return $this->setParameter('apiEndpoint', $url);
    }

    /**
     * @return string|null
     */
    public function getRedirectEndpoint(): string
    {
        return $this->getParameter('redirectEndpoint');
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setRedirectEndpoint(string $url): self
    {
        return $this->setParameter('redirectEndpoint', $url);
    }

    /**
     * @return string|null
     */
    public function getPostEndpoint(): string
    {
        return $this->getParameter('postEndpoint');
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setPostEndpoint(string $url): self
    {
        return $this->setParameter('postEndpoint', $url);
    }

    /**
     * @return string
     */
    public function getShopId(): string
    {
        return $this->getParameter('shopId');
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setShopId(string $id): self
    {
        return $this->setParameter('shopId', $id);
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->getParameter('secretKey');
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setSecretKey(string $key): self
    {
        return $this->setParameter('secretKey', $key);
    }
}
