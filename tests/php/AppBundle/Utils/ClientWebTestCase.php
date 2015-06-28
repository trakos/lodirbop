<?php

namespace Trakos\AppBundle\Tests\Utils;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Adding initialization of symfony client, and some helpers.
 *
 * @package Trakos\AppBundle\Tests\Utils
 */
abstract class ClientWebTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    static private $client = null;

    /**
     * getCsrfTokenValue might be called before setUp (even static one)
     * so that's a wrapper to get it at anytime
     *
     * @return Client
     */
    static public function getClient()
    {
        if (self::$client == null) {
            self::$client = static::createClient();
        }
        return self::$client;
    }

    static public function tearDownAfterClass()
    {
        self::$client = null;
    }

    protected function assertResponseTypeJson()
    {
        $this->assertContains(
            'application/json',
            self::getClient()->getResponse()->headers->get('Content-Type')
        );
    }

    /**
     * @return string
     */
    protected function getCsrfTokenValue()
    {
        return self::getClient()->getContainer()->get('security.csrf.token_manager')->getToken('form')->getValue();
    }

}