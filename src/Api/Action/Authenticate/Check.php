<?php

namespace App\Api\Action\Authenticate;

use App\Api\Action\RequestTransformer;
use App\Exception\Authenticate\AuthenticateExistException;
use App\Repository\AuthenticateRepository;
use App\Service\Token\Token;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Check
{
    private AuthenticateRepository $authenticateRepository;

    public function __construct(AuthenticateRepository $authenticateRepository)
    {
        $this->authenticateRepository = $authenticateRepository;
    }

    /**
     * @Route("/authenticates/{id}", methods={"PATCH"})
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');
        $token = RequestTransformer::getRequiredField($request, 'token');

        $authenticate = $this->authenticateRepository->findOneById($id);

        $this->authenticateRepository->save($authenticate);

        if (0 === $authenticate) {
            AuthenticateExistException::notExist();
        }

        //validate token hasn't expired and authenticate is active
        if ($this->authenticateRepository->notExpireToken($authenticate) && $authenticate->getActive()) {
            if ($token == Token::getTokenMaster()) {
                $authenticate->markAsUsed();

            } else {
                if (Token::validateToken($authenticate->getToken(), $token)) {
                    $authenticate->markAsUsed();
                } else {
                    $authenticate->setActive(0);
                }
            }
        } else {
            $authenticate->setActive(0);
        }

        $this->authenticateRepository->save($authenticate);

        $mobile = $authenticate->getMobile();
        $active = $authenticate->getActive();

        return new JsonResponse(['mobile' => $mobile, 'active' => $active]);
    }
}
