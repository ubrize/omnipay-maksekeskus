<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus\Messages;

use Omnipay\Common\Exception\InvalidRequestException;

class CreatePaymentRequest extends AbstractRequest
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
                'email' => $this->getCustomerEmail(),
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

    /**
     * @param string $email
     * @return CreatePaymentRequest
     */
    public function setCustomerEmail(string $email): self
    {
        return $this->setParameter('email', $email);
    }

    /**
     * @return string|null
     */
    public function getCustomerEmail(): ?string
    {
        return $this->getParameter('email');
    }
}
