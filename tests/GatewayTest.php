<?php

declare(strict_types=1);

namespace Omnipay\Maksekeskus\Tests;

use Omnipay\Maksekeskus\Gateway;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;
    
    protected function setUp()
    {
        parent::setUp();
    
        $this->gateway = new class($this->getHttpClient(), $this->getHttpRequest()) extends Gateway {
            protected $defaults = [
                'country' => 'ee',
                'locale' => 'en',
                'shopId' => 'random',
                'secretKey' => 'random',
                'apiEndpoint' => 'https://api.maksekeskus.ee/v1/',
                'redirectEndpoint' => 'https://payment.maksekeskus.ee/pay/1/link.html',
                'postEndpoint' => 'https://payment.maksekeskus.ee/pay/1/signed.html',
            ];
        };
    }
    
    public function testItUsesTheDefaultParameters()
    {
        $apiEndpoint = $this->gateway->purchase()->getApiEndpoint();
        $redirectEndpoint = $this->gateway->purchase()->getRedirectEndpoint();
        $postEndpoint = $this->gateway->purchase()->getPostEndpoint();
        $defaults = $this->gateway->getDefaultParameters();
        
        $this->assertSame($defaults['apiEndpoint'], $apiEndpoint);
        $this->assertSame($defaults['redirectEndpoint'], $redirectEndpoint);
        $this->assertSame($defaults['postEndpoint'], $postEndpoint);
        $this->assertSame($defaults['locale'], $this->gateway->getLocale());
        $this->assertSame($defaults['country'], $this->gateway->getCountry());
    }
}
