<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022, all rights reserved.
 */

namespace Parthenon\AbTesting\Command;

use Parthenon\AbTesting\Decider\ChoiceDecider\CacheGenerator;
use Parthenon\Common\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateDecisionCacheCommand extends Command
{
    use LoggerAwareTrait;

    protected static $defaultName = 'parthenon:ab-testing:generate-decision-cache';

    private CacheGenerator $generator;

    public function __construct(CacheGenerator $generator)
    {
        parent::__construct(null);
        $this->generator = $generator;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating ab testing decision cache');
        $this->getLogger()->info('Generating ab testing decision cache');

        $this->generator->generate();

        return 0;
    }
}
