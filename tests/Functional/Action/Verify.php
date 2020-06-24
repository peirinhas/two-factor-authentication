<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use Symfony\Component\HttpFoundation\JsonResponse;

class Verify extends ActionTestBase
{
    public function testVerifyCorrectNumberMobile(): void
    {
        $payload = [
            'mobile' => '666666666',
        ];

        self::$client->request(
            'POST',
            $this->endpointAuthenticate,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertNotEmpty($responseData['token']);

    }

    public function testVerifyBadNumberMobile(): void
    {
        $payload = [
            'mobile' => '466666666',
        ];

        self::$client->request(
            'POST',
            $this->endpointAuthenticate,
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals("It's unsupported mobile number.",$responseData['message']);
    }
}
