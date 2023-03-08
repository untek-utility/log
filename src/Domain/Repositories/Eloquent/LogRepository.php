<?php

namespace Untek\Utility\Log\Domain\Repositories\Eloquent;

use Untek\Utility\Log\Domain\Entities\LogEntity;
use Untek\Utility\Log\Domain\Interfaces\Repositories\LogRepositoryInterface;
use Untek\Database\Eloquent\Domain\Base\BaseEloquentCrudRepository;

class LogRepository extends BaseEloquentCrudRepository implements LogRepositoryInterface
{

    public function tableName(): string
    {
        return 'log_history';
    }

    public function getEntityClass(): string
    {
        return LogEntity::class;
    }
}
