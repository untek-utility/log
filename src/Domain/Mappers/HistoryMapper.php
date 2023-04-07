<?php

namespace Untek\Utility\Log\Domain\Mappers;

use Untek\Model\Repository\Interfaces\MapperInterface;

class HistoryMapper implements MapperInterface
{

    public function encode($entityAttributes)
    {
        return $entityAttributes;
    }

    public function decode($rowAttributes)
    {
        $rowAttributes['createdAt'] = new \DateTime($rowAttributes['datetime']);
        unset($rowAttributes['datetime']);
        return $rowAttributes;
    }
}
