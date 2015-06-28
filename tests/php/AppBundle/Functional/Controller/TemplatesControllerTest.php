<?php

namespace Trakos\AppBundle\Tests\Functional\Controller;

use Trakos\AppBundle\Tests\Utils\ClientWebTestCase;

class TemplatesControllerTest extends ClientWebTestCase
{

    /**
     * Just make sure that the page loads and has one of the html nodes (unique to it)

     * @dataProvider getChooseModeActionData
     */
    public function testChooseModeAction($languageCode, $text)
    {
        $client = static::getClient();
        $crawler = $client->request('GET', '/' . $languageCode . '/templates/choose-mode');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains(
            'text/html',
            $client->getResponse()->headers->get('Content-Type')
        );
        $this->assertEquals($text, trim($crawler->filter('.jumbotron.game-option h2')->text()));
    }

    /**
     * @return array
     */
    public function getChooseModeActionData()
    {
        return [
            'en' => ['en', 'Let\'s guess'],
            'pl' => ['pl', 'Zgadujmy'],
            'fallback' => ['it', 'Let\'s guess'],
        ];
    }

    /**
     * Just make sure that the page loads and has one of the html nodes (unique to it).

     * @dataProvider getChooseBallsActionData
     */
    public function testChooseBallsAction($languageCode, $text)
    {
        $client = static::getClient();
        $crawler = $client->request('GET', '/' . $languageCode . '/templates/choose-balls');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains(
            'text/html',
            $client->getResponse()->headers->get('Content-Type')
        );
        $this->assertEquals($text, trim($crawler->filter('h1')->text()));
    }

    /**
     * @return array
     */
    public function getChooseBallsActionData()
    {
        return [
            'en' => ['en', 'Choose your numbers'],
            'pl' => ['pl', 'Wybierz swoje liczby'],
            'fallback' => ['de', 'Choose your numbers'],
        ];
    }

    /**
     * Just make sure that the page loads and has one of the html nodes (unique to it).

     * @dataProvider getResultsActionData
     */
    public function testResultsAction($languageCode, $text)
    {
        $client = static::getClient();
        $crawler = $client->request('GET', '/' . $languageCode . '/templates/results');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains(
            'text/html',
            $client->getResponse()->headers->get('Content-Type')
        );
        $this->assertEquals(4, $crawler->filter('h2')->count());
        $this->assertEquals($text, trim($crawler->filter('h2')->first()->text()));
    }

    /**
     * @return array
     */
    public function getResultsActionData()
    {
        return [
            'en' => ['en', 'Baskets that have only balls owned by the user'],
            'pl' => ['pl', 'Koszyki, które mają tylko kulki posiadane przez użytkownika'],
            'fallback' => ['no', 'Baskets that have only balls owned by the user'],
        ];
    }

}
