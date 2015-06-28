<?php

namespace Trakos\AppBundle\Tests\Utils;


trait QueryMagicResultsInputTypeDataTrait
{

    public function getValidData()
    {
        return [
            [
                'data' => [
                    'basketsSeed' => 'test8',
                    'userBasket' => ['3', 2, '9', 1],
                ],
            ],
            [
                'data' => [
                    'basketsSeed' => 'test4',
                    'userBasket' => ['6', 5, '9', 1],
                ],
            ],
            [
                'data' => [
                    'basketsSeed' => '555',
                    'userBasket' => [2.5, '3.5'],
                ],
            ],
        ];
    }

    public function getInvalidData()
    {
        return [
            [
                'data' => [
                    'basketsSeed' => 'asd',
                    'userBasket' => ['0x5'], // non-integer
                ],
            ],
            [
                'data' => [
                    'basketsSeed' => '', // can't be empty
                    'userBasket' => ['3', 2, '9', 1],
                ],
            ],
            [
                'data' => [
                    'basketsSeed' => 'test4',
                    'userBasket' => null, // can't be empty
                ],
            ],
            [
                'data' => [
                    'basketsSeed' => null, // can't be empty
                    'userBasket' => [1],
                ],
            ],
            [
                'data' => [
                    'basketsSeed' => 'a',
                    'userBasket' => [1, 2, 1], // duplicates
                ],
            ],
            [
                'data' => [
                    'basketsSeed' => 'a',
                    'userBasket' => [1, 5, '0'], // 0 too small
                ],
            ],
            [
                'data' => [
                    'basketsSeed' => 'a',
                    'userBasket' => [1000], // too big
                ],
            ],
            [
                'data' => [
                    'basketsSeed' => 'a',
                    'userBasket' => [10, '010'], // sneaky duplicate and non-integer
                ],
            ],
            [
                'data' => [
                    'basketsSeed' => 'a',
                    'userBasket' => [], // can't be empty
                ],
            ],
            [
                'data' => [
                    'basketsSeed' => 'a',
                    'userBasket' => [1.1, 1.2], // sneaky duplicate
                ],
            ],
        ];
    }

}