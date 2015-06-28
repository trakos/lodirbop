<?php

namespace Trakos\AppBundle\Tests\Functional\Controller;

use Trakos\AppBundle\Tests\Utils\ClientWebTestCase;

class ApiControllerTest extends ClientWebTestCase
{

    /**
     * Tests for every action.
     *
     * More extensive validation testing is done in integration tests, like:
     * @see \Trakos\Dirbos\AppBundle\Tests\Integration\RestInput\Type\QueryMagicResultsInputTypeTest
     * @see \Trakos\Dirbos\AppBundle\Tests\Integration\RestInput\Type\SeedInputTypeTest
     *
     * @dataProvider getTestApiGetActions
     *
     * @param string $path
     * @param array $getData
     * @param int $expectedCode
     * @param callable $dataCallback
     */
    public function testApiGetActions($path, $getData, $expectedCode, $dataCallback)
    {
        $client = self::getClient();
        $client->request('GET', $path, $getData);
        // validate status
        $this->assertEquals($expectedCode, $client->getResponse()->getStatusCode());
        // validate type
        $this->assertResponseTypeJson($client);
        $this->assertJson($client->getResponse()->getContent());
        // validate message content
        $data = json_decode($client->getResponse()->getContent());
        $dataCallback($data);
    }

    public function getTestApiGetActions()
    {
        return [
            // query-seed
            'query-seed_empty' => [
                'path' => '/api/query-seed',
                'getData' => [],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertEquals(400, $responseData->code);
                    $this->assertContains('No required parameters were sent', $responseData->message);
                }
            ],
            'query-seed_error' => [
                'path' => '/api/query-seed',
                'getData' => ['form' => ['_token' => '?']],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertEquals(400, $responseData->code);
                    $this->assertContains('Input validation failed', $responseData->message);
                    $this->assertContains('The CSRF token is invalid', $responseData->message);
                }
            ],
            'query-seed_error-unknown-param' => [
                'path' => '/api/query-seed',
                'getData' => [
                    'form' => [
                        '_token' => $this->getCsrfTokenValue(),
                        'I\'m lost' => 'where am I?'
                    ]
                ],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertContains('Input validation failed', $responseData->message);
                    $this->assertContains('This form should not contain extra fields.', $responseData->message);
                }
            ],
            'query-seed_valid' => [
                'path' => '/api/query-seed',
                'getData' => ['form' => ['_token' => $this->getCsrfTokenValue()]],
                'expectedCode' => 200,
                'dataCallback' => function ($responseData) {
                    $this->assertNotEmpty($responseData->serverSeed);
                }
            ],

            // query-randomized
            'query-randomized_empty' => [
                'path' => '/api/query-randomized',
                'getData' => [],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertEquals(400, $responseData->code);
                    $this->assertContains('No required parameters were sent', $responseData->message);
                }
            ],
            'query-randomized_error-csrf' => [
                'path' => '/api/query-randomized',
                'getData' => ['form' => ['_token' => '?', 'basketsSeed' => 'test']],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertEquals(400, $responseData->code);
                    $this->assertContains('Input validation failed', $responseData->message);
                    $this->assertContains('The CSRF token is invalid', $responseData->message);
                }
            ],
            'query-randomized_error-empty' => [
                'path' => '/api/query-randomized',
                'getData' => ['form' => ['_token' => $this->getCsrfTokenValue(), 'basketsSeed' => '']],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertEquals(400, $responseData->code);
                    $this->assertContains('Input validation failed', $responseData->message);
                    $this->assertContains('This value should not be blank.', $responseData->message);
                }
            ],
            'query-randomized_error-unknown-param' => [
                'path' => '/api/query-randomized',
                'getData' => [
                    'form' => [
                        '_token' => $this->getCsrfTokenValue(),
                        'basketsSeed' => 'test',
                        'I\'m lost' => 'where am I?'
                    ]
                ],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertContains('Input validation failed', $responseData->message);
                    $this->assertContains('This form should not contain extra fields.', $responseData->message);
                }
            ],
            'query-randomized_success' => [
                'path' => '/api/query-randomized',
                'getData' => ['form' => ['_token' => $this->getCsrfTokenValue(), 'basketsSeed' => 'test']],
                'expectedCode' => 200,
                'dataCallback' => function ($responseData) {
                    // for a given seed it should always be the same result
                    // unless php will change something in mt_rand implementation in the future
                    // but it will be a one time change
                    // some logic depends on it being determinant, so we're testing for it
                    $this->assertJsonStringEqualsJsonFile(
                        __DIR__ . DIRECTORY_SEPARATOR . 'api_query-randomized.json',
                        json_encode($responseData)
                    );
                }
            ],

            // query-magic-results
            'query-magic-results_empty' => [
                'path' => '/api/query-magic-results',
                'getData' => [],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertEquals(400, $responseData->code);
                    $this->assertContains('No required parameters were sent', $responseData->message);
                }
            ],
            'query-magic-results-csrf' => [
                'path' => '/api/query-magic-results',
                'getData' => ['form' => ['_token' => '?', 'basketsSeed' => 'test', 'userBasket' => [5]]],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertEquals(400, $responseData->code);
                    $this->assertContains('Input validation failed', $responseData->message);
                    $this->assertContains('The CSRF token is invalid', $responseData->message);
                }
            ],
            'query-magic-results_error-empty_seed' => [
                'path' => '/api/query-magic-results',
                'getData' => [
                    'form' => [
                        '_token' => $this->getCsrfTokenValue(),
                        'basketsSeed' => '',
                        'userBasket' => [5]
                    ]
                ],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertEquals(400, $responseData->code);
                    $this->assertContains('Input validation failed', $responseData->message);
                    $this->assertContains('This value should not be blank.', $responseData->message);
                }
            ],
            'query-magic-results_error-empty_basket' => [
                'path' => '/api/query-magic-results',
                'getData' => [
                    'form' => [
                        '_token' => $this->getCsrfTokenValue(),
                        'basketsSeed' => '',
                        'userBasket' => []
                    ]
                ],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertEquals(400, $responseData->code);
                    $this->assertContains('Input validation failed', $responseData->message);
                    $this->assertContains('This value should not be blank.', $responseData->message);
                }
            ],
            'query-magic-results_error-duplicates' => [
                'path' => '/api/query-magic-results',
                'getData' => [
                    'form' => [
                        '_token' => $this->getCsrfTokenValue(),
                        'basketsSeed' => 'test',
                        'userBasket' => [5, 5]
                    ]
                ],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertEquals(400, $responseData->code);
                    $this->assertContains('Input validation failed', $responseData->message);
                    $this->assertContains('userBasket: Not all values are unique.', $responseData->message);
                }
            ],
            'query-magic-results_error-max' => [
                'path' => '/api/query-magic-results',
                'getData' => [
                    'form' => [
                        '_token' => $this->getCsrfTokenValue(),
                        'basketsSeed' => 'test',
                        'userBasket' => [5, 1000]
                    ]
                ],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertEquals(400, $responseData->code);
                    $this->assertContains('Input validation failed', $responseData->message);
                    $this->assertContains('This value should be less than or equal to 999.', $responseData->message);
                }
            ],
            'query-magic-results_error-unknown-param' => [
                'path' => '/api/query-magic-results',
                'getData' => [
                    'form' => [
                        '_token' => $this->getCsrfTokenValue(),
                        'basketsSeed' => 'test',
                        'userBasket' => [5, 7],
                        'I\'m lost' => 'where am I?'
                    ]
                ],
                'expectedCode' => 400,
                'dataCallback' => function ($responseData) {
                    $this->assertContains('Input validation failed', $responseData->message);
                    $this->assertContains('This form should not contain extra fields.', $responseData->message);
                }
            ],
            'query-magic-results_valid' => [
                'path' => '/api/query-magic-results',
                'getData' => [
                    'form' => [
                        '_token' => $this->getCsrfTokenValue(),
                        'basketsSeed' => 'test',
                        'userBasket' => [5, 7]
                    ]
                ],
                'expectedCode' => 200,
                'dataCallback' => function ($responseData) {
                    // for a given seed it should always be the same result
                    // unless php will change something in mt_rand implementation in the future
                    // but it will be a one time change
                    // some logic depends on it being determinant, so we're testing for it
                    $this->assertJsonStringEqualsJsonFile(
                        __DIR__ . DIRECTORY_SEPARATOR . 'api_query-magic-results.json',
                        json_encode($responseData)
                    );
                }
            ],
        ];
    }

}
