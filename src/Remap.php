<?php

declare(strict_types=1);

use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Hydrator\HydratorInterface;

final class Remap
{
    public function __construct(
        private readonly HydratorInterface $hydrator,
        private readonly ConnectionInterface $connection,
    ) {
    }

    public function map(string $class, string|array $sql, array $params = []): iterable
    {
        if (is_array($sql)) {
            $params = $sql[1] ?? $params;
            $sql = $sql[0];
        }
        $iterator = $this->connection->createCommand($sql, $params)->query();
        foreach ($iterator as $row) {
            yield $this->hydrator->create($class, $row);
        }
    }
}