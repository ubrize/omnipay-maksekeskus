<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus\Messages;

use Omnipay\Common\Exception\InvalidRequestException;

class CreateTransactionRequest extends AbstractRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        return [
            'transaction' => [
                'amount' => $this->getAmount(),
                'currency' => $this->getCurrency(),
                'reference' => $this->getTransactionReference(),
                'merchant_data' => '',
            ],
            'customer' => [
                'email' => '',
                'ip' => $this->getClientIp(),
                'country' => $this->getCountry(),
                'locale' => $this->getLocale(),
            ],
        ];
    }

    /**
     * @return string
     */
    public function getPathParts(): string
    {
        return '/transactions';
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->getParameter('locale');
    }
}
