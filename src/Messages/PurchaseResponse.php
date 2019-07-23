<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus\Messages;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Maksekeskus\Support\Mac;

class PurchaseResponse extends Response implements RedirectResponseInterface
{
    /**
     * @var PurchaseRequest
     */
    protected $request;

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isRedirect(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getRedirectMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        return $this->request->getPostUrl();
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getRedirectData()
    {
        $data = $this->request->getData();

        return [
            'json' => json_encode($data),
            'mac' => (new Mac($data, $this->request->getSecretKey()))->hash(),
        ];
    }
}
