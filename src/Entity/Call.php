<?php

namespace App\Entity;

use App\Repository\CallRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CallRepository::class)]
#[ORM\Table(name: '`call`')]
class Call
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $custumer_id;

    #[ORM\Column(type: 'integer')]
    private $duration;

    #[ORM\Column(type: 'integer')]
    private $phone;

    #[ORM\Column(type: 'string', length: 255)]
    private $ip;

    #[ORM\Column(type: 'datetime')]
    private $timestamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustumerId(): ?int
    {
        return $this->custumer_id;
    }

    public function setCustumerId(int $custumer_id): self
    {
        $this->custumer_id = $custumer_id;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
