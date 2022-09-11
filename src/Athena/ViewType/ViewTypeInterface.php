<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022, all rights reserved.
 */

namespace Parthenon\Athena\ViewType;

interface ViewTypeInterface
{
    public function getName(): string;

    public function setData($data);

    public function getHtmlOutput(): string;
}
