<?php

namespace Untek\Utility\Log\Domain\Entities;

use Monolog\DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Model\Entity\Interfaces\EntityIdInterface;
use Untek\Model\Validator\Interfaces\ValidationByMetadataInterface;
use DateTime;

class HistoryEntity implements EntityIdInterface, ValidationByMetadataInterface
{

    private $id;
    private $message;
    private $context;
    private $level;
    private $level_name;
    private $channel;
    private $extra;
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('message', new Assert\NotBlank);
        $metadata->addPropertyConstraint('context', new Assert\NotBlank);
        $metadata->addPropertyConstraint('level', new Assert\NotBlank);
        $metadata->addPropertyConstraint('channel', new Assert\NotBlank);
        $metadata->addPropertyConstraint('createdAt', new Assert\NotBlank);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext($context): void
    {
        $this->context = $context;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getLevelName(): ?string
    {
        return $this->level_name;
    }

    public function setLevelName(string $level_name): void
    {
        $this->level_name = $level_name;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->datetime;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->datetime = $createdAt;
    }

    public function getExtra()
    {
        return $this->extra;
    }

    public function setExtra($extra): void
    {
        $this->extra = $extra;
    }
}
