<?php

namespace Untek\Utility\Log\Domain\Interfaces\Repositories;

use Untek\Model\Shared\Interfaces\GetEntityClassInterface;
use Untek\Model\Shared\Interfaces\ReadAllInterface;
use Untek\Model\Repository\Interfaces\FindOneInterface;
use Untek\Model\Repository\Interfaces\RepositoryInterface;

interface HistoryRepositoryInterface extends RepositoryInterface, GetEntityClassInterface, ReadAllInterface, FindOneInterface
{


}
