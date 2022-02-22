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
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;

final class ContactsService extends AbstractResource
{
    #[Pure] public function __construct(
        LoggerInterface $logger,
    )
    {
        parent::__construct($logger);
    }
}
