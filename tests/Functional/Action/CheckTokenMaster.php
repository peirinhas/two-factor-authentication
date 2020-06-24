<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use App\Service\Token\Token;
use Symfony\Component\HttpFoundation\JsonResponse;

class CheckTokenMaster extends ActionTestBase
{
    public function testCheckWithTokenMaster(): void
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

        //After to generate token we check this with token master
        $payload = [
            'token' => 'M4s1er',
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
