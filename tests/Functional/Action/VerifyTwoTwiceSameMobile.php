<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use App\Service\Token\Token;
use Symfony\Component\HttpFoundation\JsonResponse;

class VerifyTwoTwiceSameMobile extends ActionTestBase
{
    /*
     * We will generate the token for a mobile twice.
     * We will test both and the only one that will work will be the second
     */
    public function testVerifyTwoTwiceSameMobileAndFirstFail(): void
    {
        $mobile ='666666666';

        //first we need generate the token
        $payload = [
            'mobile' => $mobile,
        ];

        self::$client->request(
            'POST',
            $this->endpointAuthenticate,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $responseVerify1 = self::$client->getResponse();
        $responseVerifyData1 = $this->getResponseData($responseVerify1);


        self::$client->request(
            'POST',
            $this->endpointAuthenticate,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $responseVerify2 = self::$client->getResponse();
        $responseVerifyData2 = $this->getResponseData($responseVerify2);

        //After to generate token we check the first token
        $payload = [
            'token' => $responseVerifyData1['token'],
        ];

        self::$client->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointAuthenticate, $responseVerifyData1['id']),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $responseCheck1 = self::$client->getResponse();
        $responseCheckData1= $this->getResponseData($responseCheck1);

        $this->assertEquals(JsonResponse::HTTP_OK, $responseCheck1->getStatusCode());
        $this->assertEquals($mobile, $responseCheckData1['mobile']);
        $this->assertEquals(false, $responseCheckData1['active']);


        //After to generate token we check the first token
        $payload = [
            'token' => $responseVerifyData1['token'],
        ];

        self::$client->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointAuthenticate, $responseVerifyData1['id']),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $responseCheck2 = self::$client->getResponse();
        $responseCheckData2= $this->getResponseData($responseCheck2);

        $this->assertEquals(JsonResponse::HTTP_OK, $responseCheck2->getStatusCode());
        $this->assertEquals($mobile, $responseCheckData2['mobile']);
        $this->assertEquals(false, $responseCheckData2['active']);

    }

    public function testTryCheckTwoTwice(): void
    {
        $mobile ='666666666';

        //first we need generate the token
        $payload = [
            'mobile' => $mobile,
        ];

        self::$client->request(
            'POST',
            $this->endpointAuthenticate,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $responseVerify = self::$client->getResponse();
        $responseVerifyData = $this->getResponseData($responseVerify);

        //After to generate token we check the first time
        $payload = [
            'token' => $responseVerifyData['token'],
        ];

        self::$client->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointAuthenticate, $responseVerifyData['id']),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $responseCheck = self::$client->getResponse();
        $responseCheckData= $this->getResponseData($responseCheck);

        $this->assertEquals(JsonResponse::HTTP_OK, $responseCheck->getStatusCode());
        $this->assertEquals($mobile, $responseCheckData['mobile']);
        $this->assertEquals(true, $responseCheckData['active']);

        //After to generate token we check the second time
        $payload = [
            'token' => $responseVerifyData['token'],
        ];

        self::$client->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointAuthenticate, $responseVerifyData['id']),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $responseCheck = self::$client->getResponse();
        $responseCheckData= $this->getResponseData($responseCheck);

        $this->assertEquals(JsonResponse::HTTP_OK, $responseCheck->getStatusCode());
        $this->assertEquals($mobile, $responseCheckData['mobile']);
        $this->assertEquals(false, $responseCheckData['active']);

    }
}
