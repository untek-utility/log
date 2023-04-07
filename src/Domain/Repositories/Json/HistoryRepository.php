<?php

namespace Untek\Utility\Log\Domain\Repositories\Json;

use Monolog\Handler\HandlerInterface;
use Untek\Utility\Log\Domain\Entities\HistoryEntity;
use Untek\Utility\Log\Domain\Interfaces\Repositories\HistoryRepositoryInterface;
use Untek\Utility\Log\Domain\Mappers\HistoryMapper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Model\Entity\Interfaces\EntityIdInterface;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\EntityManager\Traits\EntityManagerAwareTrait;
use Untek\Model\Query\Entities\Query;
use Untek\Model\Repository\Traits\RepositoryMapperTrait;

class HistoryRepository implements HistoryRepositoryInterface
{

    use EntityManagerAwareTrait;
    use RepositoryMapperTrait;

    private $path;

    public function __construct(EntityManagerInterface $em, HandlerInterface $handler)
    {
        $this->path = $handler->getUrl();
        $this->setEntityManager($em);
    }

    public function getEntityClass(): string
    {
        return HistoryEntity::class;
    }

    public function mappers(): array
    {
        return [
            new HistoryMapper(),
        ];
    }

    public function findAll(Query $query = null): Enumerable
    {
        $file = new \SplFileObject($this->path);
        $fileIterator = new \LimitIterator($file, $query->getOffset(), $query->getPerPage());
        $collection = new Collection();
        foreach ($fileIterator as $index => $line) {
            if (!empty($line)) {
                $item = json_decode($line, JSON_OBJECT_AS_ARRAY);
                $id = $index + 1;
                $item['id'] = $id;
                $entity = $this->mapperDecodeEntity($item);
                $collection->add($entity);
            }
        }
        return $collection;
    }

    public function count(Query $query = null): int
    {
        $count = 0;
        $handle = fopen($this->path, "r");
        while (!feof($handle)) {
            fgets($handle);
            $count++;
        }
        fclose($handle);
        return $count - 1;

//        $file_arr = file($this->path);
//        return count($file_arr);
    }

    public function findOneById($id, Query $query = null): EntityIdInterface
    {
        $query = Query::forge($query);
        $query->offset($id - 1);
        $query->perPage(1);
        $collection = $this->findAll($query);
        $entity = $collection->first();
        if (empty($entity)) {
            throw new NotFoundException();
        }
        return $entity;
    }
}
