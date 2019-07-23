<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus\Messages;

class CompleteRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getPathParts(): string
    {
        return 'transactions/' . $this->getTransactionId();
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        $data = json_decode($this->httpRequest->get('json', '{}'), true);

        return $data['transaction'] ?? '';
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
     */
    public function getHttpMethod(): string
    {
        return 'GET';
    }

    /**
     * @return bool
     */
    public function shouldValidate(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    protected function getResponseObject(): string
    {
        return CompleteResponse::class;
    }
}
