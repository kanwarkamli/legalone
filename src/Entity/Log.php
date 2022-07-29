<?php

namespace App\Entity;

//use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LogRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: LogRepository::class)]
class Log
{
    #[Id]
    #[Column(type: "uuid", unique: true)]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[Column(name: "status_code", type: "string", length: 255)]
    private string $statusCode;

    #[Column(name: "service_name", type: "string", length: 255)]
    private string $serviceName;

    #[Column(name: "logged_at", type: "datetime", nullable: true)]
    private Carbon|null $loggedAt = null;

    #[Column(name: "created_at", type: "datetime", nullable: true)]
    private Carbon|null $createdAt = null;

    public function __construct()
    {
        $this->createdAt = Carbon::now();
    }

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @param Uuid $id
     * @return Log
     */
    public function setId(Uuid $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
     * @return Log
     */
    public function setStatusCode(string $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     * @return Log
     */
    public function setServiceName(string $serviceName): self
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getLoggedAt(): Carbon
    {
        return $this->loggedAt;
    }

    /**
     * @param Carbon $loggedAt
     * @return Log
     */
    public function setLoggedAt(Carbon $loggedAt): self
    {
        $this->loggedAt = $loggedAt;
        return $this;
    }
}
