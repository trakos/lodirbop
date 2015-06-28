<?php

namespace Trakos\AppBundle\Tests\Functional\Controller;

use Trakos\AppBundle\Tests\Utils\ClientWebTestCase;

class HomeControllerTest extends ClientWebTestCase
{

    /**
     * @dataProvider getLanguageDetectionData
     */
    public function testIndexActionLocaleDetection($acceptLanguage, $expectedRedirect)
    {
        $client = static::getClient();
        $client->request('GET', '/', [], [], ['HTTP_ACCEPT_LANGUAGE' => $acceptLanguage]);
        $this->assertTrue(
            $client->getResponse()->isRedirect($expectedRedirect)
        );
    }

    /**
     * Make sure that home action will only get triggered for 2-letters code
     */
    public function testHomeDoesNotPolluteRoutes()
    {
        $client = static::getClient();
        $client->request('GET', '/zzz');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    /**
     * Make sure that language detection works.
     * @dataProvider getTranslateTestData
     */
    public function testHomeEnglishWorks($languageCode, $text)
    {
        $client = static::getClient();
        $crawler = $client->request('GET', '/' . $languageCode);
        $this->assertContains(
            'text/html',
            $client->getResponse()->headers->get('Content-Type')
        );
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Home page is really simple, rest of the content will be in the angular view container.
        // so that simple filter should be enough
        $this->assertEquals($text, trim($crawler->filter('a.dropdown-toggle')->text()));
    }

    /**
     * @return array
     */
    public function getLanguageDetectionData()
    {
        return [
            'pl' => ['pl', '/pl'],
            'it fallback to en' => ['it', '/en'],
            'en' => ['en', '/en'],
            'rather en' => ['pl; q=0.4, en; q=0.5', '/en'],
            'again en' => ['de; q=1,0, it; 1=0.4, en; q=0.3', '/en']
        ];
    }

    /**
     * @return array
     */
    public function getTranslateTestData()
    {
        return [
            'en' => ['en', 'Change language'],
            'pl' => ['pl', 'Zmień język'],
            'fallback' => ['it', 'Change language'],
        ];
    }

}
