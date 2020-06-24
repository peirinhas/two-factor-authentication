<?php


namespace App\Repository;

use App\Entity\Authenticate;
use App\Exception\Authenticate\AuthenticateExistException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Service\Token\Token;

class AuthenticateRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return Authenticate::class;
    }

    public function save(Authenticate $authenticate): void
    {
        $this->saveEntity($authenticate);
    }

    public function findOneById(string $id): ?Authenticate
    {
        /** @var Authenticate $authenticate */
        $authenticate = $this->objectRepository->findOneBy(['id' => $id]);

        if (null === $authenticate) {
            AuthenticateExistException::notExist();
        }

        return $authenticate;
    }

    public function findAllByMobile(string $mobile): ?array
    {
        return $this->objectRepository->findBy(['mobile' => $mobile]);
    }

    public function findAllByMobilePending(string $mobile): ?array
    {
        return $this->objectRepository->findBy(['mobile' => $mobile, 'active' => 1]);
    }

    public function notExpireToken(Authenticate $authenticate): bool
    {
        return ( $this->isTokenExpireTime($authenticate) && !$this->isTokenUsed($authenticate));
    }

    private function isTokenUsed(Authenticate $authenticate): bool
    {
        return $authenticate->getUsedAt() != null;
    }

    private function isTokenExpireTime(Authenticate $authenticate): bool
    {
        $now = new \DateTime();
        return $now < $authenticate->getLimitAt();
    }

    public function disabledPendingMobileAuthentication(Authenticate $authenticate): void
    {
        /** @var Collection */
        $arrAuthenticate = $this->findAllByMobilePending($authenticate->getMobile());

        foreach ($arrAuthenticate as $au) {
            /** @var Authenticate $au */
            if ($au->getId() != $authenticate->getId()) {
                $au->setActive(0);
                $this->save($au);
            }
        }
    }

    public function generateToken(string $mobile): string
    {
        $arrToken = [];
        $tokenService = new Token();

        /** @var Collection */
        $arrAuthenticate = $this->findAllByMobile($mobile);

        foreach ($arrAuthenticate as $authenticate) {
            /** @var Authenticate $authenticate */
            array_push($arrToken, $authenticate->getToken());
        }

        return $tokenService->generateToken($arrToken);
    }
}