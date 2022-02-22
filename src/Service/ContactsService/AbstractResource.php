<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 09.02.2022
 * Time: 16:42
*/

namespace App\Service\ContactsService;

use App\Repository\ContactProfileRepository;
use App\Repository\ImportedContactRepository;
use App\Service\ContactsService\AbstractResource\ContactsExporter\ContactsExporter;
use App\Service\ContactsService\AbstractResource\ContactsFilter\ContactsFilter;
use App\Service\ContactsService\AbstractResource\ContactsImporter\ContactsImporter;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;

abstract class AbstractResource
{


    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger,
    )
    {
    }
//
    /**
     * @return ContactsImporter
     */
    #[Pure] public function importer(): ContactsImporter
    {
        return (new ContactsImporter($this->logger));
    }

    /**
     * @return ContactsExporter
     */
    public function exporter(): ContactsExporter {
        return (new ContactsExporter($this->logger));
    }

    /**
     * @param ContactProfileRepository $repo
     * @return ContactsFilter
     */
    #[Pure] public function filter(ContactProfileRepository $repo): ContactsFilter {
        return (new ContactsFilter($this->logger, $repo));
    }
}
