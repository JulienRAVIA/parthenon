<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: TBD ( 3 years after 2.1.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Parthenon\Common\Pdf;

use DocRaptor\Doc;
use DocRaptor\DocApi;
use Parthenon\Common\Exception\GeneralException;

final class DocRaptorGenerator implements GeneratorInterface
{
    public function __construct(private DocApi $docApi)
    {
    }

    public function generate(string $html)
    {
        $doc = new Doc();
        $doc->setDocumentContent($html);
        $doc->setDocumentType('pdf');

        try {
            return $this->docApi->createDoc($doc);
        } catch (GeneralException $e) {
            throw new GeneralException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
