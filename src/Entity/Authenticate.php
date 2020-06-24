<?php


namespace App\Entity;


use DateInterval;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class Authenticate
{
    protected ?string $id;

    protected ?string $mobile;

    protected ?string $token;

    protected ?bool $active;

    protected \DateTime $createdAt;

    protected \DateTime $limitAt;

    protected ?\DateTime $usedAt;

    private  const  TIME_EXPIRE = 5;

    /**
     * @throws \Exception
     */
    public function __construct(string $mobile = null, bool $active = null, string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->mobile = $mobile;
        $this->active = $active ?? 1;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getLimitAt(): \DateTime
    {
        return $this->limitAt;
    }

    /**
     * @throws \Exception
     */
    public function markLimitAt(): void
    {
        $date  = new \DateTime();
        $minute = (int)$date->format("i") + Authenticate::TIME_EXPIRE;
        $this->limitAt = $date->setTime($date->format("H"), $minute , $date->format("s"));
    }

    public function markAsUsed(): void
    {
        $this->usedAt = new \DateTime();
    }

    public function getUsedAt(): ?\DateTime
    {
        return $this->usedAt;
    }
}