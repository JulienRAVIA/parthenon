<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022, all rights reserved.
 */

namespace Parthenon\AbTesting\Entity;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Variant
{
    protected UuidInterface $id;

    /**
     * @Assert\NotBlank
     */
    protected string $name;

    /**
     * @Assert\NotBlank
     * @Assert\LessThanOrEqual(100)
     */
    protected int $percentage;

    protected bool $isDefault = false;

    private Experiment $experiment;

    private VariantStats $stats;

    public function __construct()
    {
        $this->stats = new VariantStats();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPercentage(): int
    {
        return $this->percentage;
    }

    public function setPercentage(int $percentage): void
    {
        $this->percentage = $percentage;
    }

    public function isIsDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    public function getExperiment(): Experiment
    {
        return $this->experiment;
    }

    public function setExperiment(Experiment $experiment): void
    {
        $this->experiment = $experiment;
    }

    public function getStats(): VariantStats
    {
        return $this->stats;
    }

    public function setStats(VariantStats $stats): void
    {
        $this->stats = $stats;
    }
}
