<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022, all rights reserved.
 */

namespace Parthenon\User\Gdpr\Export;

use Parthenon\User\Entity\UserInterface;
use Parthenon\User\Exception\Gdpr\NoFormatterFoundException;
use Symfony\Component\HttpFoundation\Response;

final class FormatterManager implements FormatterManagerInterface
{
    /**
     * @var FormatterInterface[]
     */
    private array $formatters = [];
    private string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function add(FormatterInterface $formatter): void
    {
        $this->formatters[] = $formatter;
    }

    public function format(UserInterface $user, array $data): Response
    {
        foreach ($this->formatters as $formatter) {
            if ($formatter->getName() === $this->type) {
                $filename = $formatter->getFilename($user);
                $data = $formatter->format($data);

                return new Response($data, Response::HTTP_OK, [
                    'Content-Type' => 'application/octet-stream',
                    'Content-Disposition' => sprintf('attachment; filename=%s', $filename),
                    'Content-Description' => 'File Transfer',
                    ]);
            }
        }

        throw new NoFormatterFoundException(sprintf('No formatter found for type set %s', $this->type));
    }
}
