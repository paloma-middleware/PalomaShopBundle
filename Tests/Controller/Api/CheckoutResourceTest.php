<?php

namespace Paloma\ShopBundle\Tests\Controller\Api;

use Paloma\ShopBundle\Tests\FunctionalTest;

class CheckoutResourceTest extends FunctionalTest
{
    public function testSetAddresses()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/checkout/addresses',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ 
                "billing": {
                    "title": "mr",
                    "firstName": "Hans",
                    "lastName": "Muster",
                    "street": "Musterweg 1",
                    "zipCode": "8000",
                    "city": "Zurich",
                    "country": "CH"
                },
                "shipping": null
            }'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $order = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($order['billing']['address']);
        $this->assertEquals('Muster', $order['billing']['address']['lastName']);

        $this->assertNotNull($order['shipping']['address']);
        $this->assertEquals('Muster', $order['shipping']['address']['lastName']);
    }

    public function testListShippingMethods()
    {
        $client = static::createClient();

        $client->request('GET', '/api/checkout/shipping_methods/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $methods = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($methods);
    }

    public function testGetListShippingMethodOptions()
    {
        $client = static::createClient();

        $client->request('GET', '/api/checkout/shipping_methods/options?method=swisspost_default');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $methods = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($methods);
    }

    public function testSetShippingMethod()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/checkout/shipping_methods/set',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "method": "swisspost_default" }'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListPaymentMethods()
    {
        $client = static::createClient();

        $client->request('GET', '/api/checkout/payment_methods/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $methods = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotNull($methods);
    }

    public function testSetPaymentMethod()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/checkout/payment_methods/set',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "method": "invoice" }'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testInitializePayment()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/checkout/payments/initialize',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ 
                "successUrl": "https://success",
                "errorUrl": "https://error",
                "cancelUrl": "https://cancel"
             }'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testPurchase()
    {
        $client = static::createClient();

        $client->request('POST', '/api/checkout/purchase');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}