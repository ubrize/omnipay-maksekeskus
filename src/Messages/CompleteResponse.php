<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus\Messages;

class CompleteResponse extends Response
{
    // Transaction initiated by merchant
    public const STATUS_CREATED = 'CREATED';

    // Payment process has been initiated, customer is filling in data or
    // payment is in progress in payment channel
    public const STATUS_PENDING = 'PENDING';

    // Customer has explicitly terminated the payment process – pressed ‘cancel’
    // button in bank’s payment dialog
    public const STATUS_CANCELLED = 'CANCELLED';

    // Customer has not completed the payment during 30 minutes in PENDING state
    public const STATUS_EXPIRED = 'EXPIRED';

    // This state is relevant to to some payment channels only –
    // card payments, Citadele bank-link.
    // On card payments this means that card data has been already verified,
    // and transaction is waiting now payment.
    // Regular card payments are however automatically completed.
    public const STATUS_APPROVED = 'APPROVED';

    // Transaction has been successfully paid (green light for merchant to deliver
    // goods/services).
    // The funds will be transferred to merchant with next payout
    public const STATUS_COMPLETED = 'COMPLETED';

    // The transaction amount has been partially refunded to the customer
    public const STATUS_PART_REFUNDED = 'PART_REFUNDED';

    // Transaction has been completely refunded
    public const STATUS_REFUNDED = 'REFUNDED';

    /**
     * @var CompleteRequest
     */
    protected $request;

    /**
     * @return string|null
     */
    public function extractRequestStatus(): ?string
    {
        return $this->request->extractRequestJson()['status'] ?? null;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->extractRequestStatus() === self::STATUS_COMPLETED;
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->extractRequestStatus() === self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->extractRequestStatus() === self::STATUS_CANCELLED;
    }

    /**
     * @return bool
     */
    public function isServerToServerRequest(): bool
    {
        if ($this->request->getHttpMethod() === 'POST') {
            return true;
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getTransactionReference(): string
    {
        return $this->getData()['reference'] ?? '';
    }
}
