<?php

declare(strict_types=1);

namespace Tests\Controller;

use App\DataFixtures\AppFixtures;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $fixture = new AppFixtures();
        $fixture->load($entityManager);
    }

    public function testTotalDataCount(): void
    {
        $this->client->request('GET', '/count', []);

        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse();
        $this->assertEquals('{"counter":38}', $response->getContent());
    }

    public function testCountByFilterServiceNameIsUserService(): void
    {
        $this->client->request('GET', '/count', ['serviceNames' => 'USER-SERVICE']);

        $response = $this->client->getResponse();
        $this->assertEquals('{"counter":11}', $response->getContent());
    }

    public function testCountByFilterServiceNameIsInvoiceService(): void
    {
        $this->client->request('GET', '/count', ['serviceNames' => 'INVOICE-SERVICE']);

        $response = $this->client->getResponse();
        $this->assertEquals('{"counter":27}', $response->getContent());
    }

    public function testCountByFilterServiceNameIsInvoiceServiceAndUserService(): void
    {
        $this->client->request('GET', '/count', ['serviceNames' => ['INVOICE-SERVICE' , 'USER-SERVICE']]);

        $response = $this->client->getResponse();
        $this->assertEquals('{"counter":38}', $response->getContent());
    }
}
