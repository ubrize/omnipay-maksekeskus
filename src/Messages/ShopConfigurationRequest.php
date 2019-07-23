<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus\Messages;

class ShopConfigurationRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'platform' => $this->getPlatform(),
            'module' => $this->getModule(),
        ];
    }

    /**
     * @return string|null
     */
    public function getPlatform(): ?string
    {
        return $this->getParameter('platform');
    }

    /**
     * @param string $platform
     * @return ShopConfigurationRequest
     */
    public function setPlatform(string $platform): self
    {
        return $this->setParameter('platform', $platform);
    }

    /**
     * @return string|null
     */
    public function getModule(): ?string
    {
        return $this->getParameter('module');
    }

    /**
     * @param string $module
     * @return ShopConfigurationRequest
     */
    public function setModule(string $module): self
    {
        return $this->setParameter('module', $module);
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'GET';
    }

    /**
     * @return string
     */
    public function getPathParts(): string
    {
        return '/shop/configuration';
    }
}
