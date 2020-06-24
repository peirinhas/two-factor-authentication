<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use App\Service\Token\Token;
use Symfony\Component\HttpFoundation\JsonResponse;

class CheckToken extends ActionTestBase
{
    public function testCheckWithCorrectToken(): void
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

        //After to generate token we check this
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

    }

    public function testCheckWithBadToken(): void
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

        //After to generate token we check this
        $payload = [
            'token' => 'BAD_TOKEN',
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

    /*
     * Check first wrong token. We try again with the correct token but it will no longer work
     */
    public function testCheckWithBadTokenTryCorrectToken(): void
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

        //After to generate token we check this with bad token
        $payload = [
            'token' => 'BAD_TOKEN',
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

        //After to generate token we check this with correct token
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
