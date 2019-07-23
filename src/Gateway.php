<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Maksekeskus\Messages\CompleteRequest;
use Omnipay\Maksekeskus\Messages\CreateTransactionRequest;
use Omnipay\Maksekeskus\Messages\PurchaseRequest;
use Omnipay\Maksekeskus\Messages\ShopConfigurationRequest;

class Gateway extends AbstractGateway
{
    public const SIGNATURE_TYPE_1 = 'V1';
    public const SIGNATURE_TYPE_2 = 'V2';
    public const SIGNATURE_TYPE_MAC = 'MAC';

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
        return [
            'testMode' => false,
            'currency' => 'EUR',
            'country' => 'ee',
            'locale' => 'en',
        ];
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
     * @return AbstractRequest|AbstractRequest
     */
    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    /**
     * @param array $options
     * @return CompleteRequest|AbstractRequest
     */
    public function completePurchase(array $options): CompleteRequest
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


}
