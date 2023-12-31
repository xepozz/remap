```php
<?php

use Psr\Http\Message\ResponseInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\DataResponse\Middleware\FormatDataResponseAsJson;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Hydrator\HydratorInterface;

class BlogController
{
    public function actionIndex(
        DataResponseFactoryInterface $responseFactory,
        Remap $remap,
    ): ResponseInterface
    {
        $todos = $remap->map(Todo::class, Todo::selectAll());
        $favorites = [
            ...$remap->map(Todo::class, Todo::selectOne(id: "32")),
            ...$remap->map(Todo::class, 'select * from todo where id = :id', ['id' => 31]),
        ];

        return $responseFactory->createResponse(['todos' => $todos, 'favorites' => $favorites]);
    }
}

class Todo
{
    public int $id;
    public string $title;
    public bool $done;

    public static function selectOne(string $id): array
    {
        return [
            'SELECT id, title, false as done FROM todo WHERE id = :id LIMIT 1',
            ['id' => $id],
        ];
    }

    public static function selectAll(): array
    {
        return ['SELECT id, title, false as done FROM todo'];
    }
}
```