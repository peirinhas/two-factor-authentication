<?php

namespace App\Api\Action\Authenticate;

use App\Api\Action\RequestTransformer;
use App\Entity\Authenticate;
use App\Exception\Mobile\UnsupportedMobileException;
use App\Repository\AuthenticateRepository;
use App\Security\Validator\Mobile\AreValidMobile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Verify
{
    private AuthenticateRepository $authenticateRepository;

    public function __construct(AuthenticateRepository $authenticateRepository)
    {
        $this->authenticateRepository = $authenticateRepository;
    }

    /**
     * @Route("/authenticates", methods={"POST"})
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $mobile = RequestTransformer::getRequiredField($request, 'mobile');

        //validate format mobile phone
        $mobileValidator = new AreValidMobile();

        if (!$mobileValidator->validate($mobile)) {
            throw UnsupportedMobileException::numberUnsupported();
        }

        $authenticate = new Authenticate($mobile);
        $token = $this->authenticateRepository->generateToken($mobile);
        $authenticate->setToken($token);
        $authenticate->markLimitAt();
        $this->authenticateRepository->save($authenticate);
        $this->authenticateRepository->disabledPendingMobileAuthentication($authenticate);

        return new JsonResponse(['id' => $authenticate->getId(), 'token' => $authenticate->getToken()]);
    }
}
