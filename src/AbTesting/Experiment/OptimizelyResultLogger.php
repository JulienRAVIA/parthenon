<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022, all rights reserved.
 */

namespace Parthenon\AbTesting\Experiment;

use Optimizely\Optimizely;
use Parthenon\User\Entity\UserInterface;

final class OptimizelyResultLogger implements ResultLoggerInterface
{
    public function __construct(private Optimizely $optimizely)
    {
    }

    public function log(string $resultId, ?UserInterface $user = null, array $userAttributes = [], array $eventTags = []): void
    {
        $userId = ($user) ? $user->getId() : null;
        $this->optimizely->track($resultId, $userId, $userAttributes, $eventTags);
    }
}
