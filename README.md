# ArrayCombining

Библиотека для объединения массивов в PHP с поддержкой связей one-to-many и one-to-one.

## Установка

```bash
composer require inilim/array_combining:dev-main
```

## Использование

### One-to-Many связь

Метод `oneToMany()` позволяет объединить один массив с другим, где один элемент из первого массива может быть связан с несколькими элементами из второго массива.

```php
<?php

use Inilim\ArrayCombining\ArrayCombining;

$posts = [
    ['id' => 1, 'title' => 'Post 1', 'userId' => 111],
    ['id' => 2, 'title' => 'Post 2', 'userId' => 222],
    ['id' => 3, 'title' => 'Post 3', 'userId' => 111],
];

$comments = [
    ['id' => 1, 'postId' => 1, 'text' => 'Comment 1 for post 1'],
    ['id' => 2, 'postId' => 1, 'text' => 'Comment 2 for post 1'],
    ['id' => 3, 'postId' => 2, 'text' => 'Comment 1 for post 2'],
];

$combiner = new ArrayCombining();
$result = $combiner->oneToMany($posts, $comments, 'id', 'postId', 'comments');

// Результат:
// [
//     [
//         'id' => 1,
//         'title' => 'Post 1',
//         'userId' => 111,
//         'comments' => [
//             ['id' => 1, 'postId' => 1, 'text' => 'Comment 1 for post 1'],
//             ['id' => 2, 'postId' => 1, 'text' => 'Comment 2 for post 1']
//         ]
//     ],
//     [
//         'id' => 2,
//         'title' => 'Post 2',
//         'userId' => 222,
//         'comments' => [
//             ['id' => 3, 'postId' => 2, 'text' => 'Comment 1 for post 2']
//         ]
//     ],
//     [
//         'id' => 3,
//         'title' => 'Post 3',
//         'userId' => 111,
//         'comments' => []
//     ]
// ]
```

### One-to-One связь

Метод `oneToOne()` позволяет объединить один массив с другим, где один элемент из первого массива связан с одним элементом из второго массива.

```php
<?php

use Inilim\ArrayCombining\ArrayCombining;

$posts = [
    ['id' => 1, 'title' => 'Post 1', 'userId' => 111],
    ['id' => 2, 'title' => 'Post 2', 'userId' => 222],
];

$users = [
    ['id' => 111, 'name' => 'User 1'],
    ['id' => 222, 'name' => 'User 2'],
];

$combiner = new ArrayCombining();
$result = $combiner->oneToOne($posts, $users, 'userId', 'id', 'user');

// Результат:
// [
//         'id' => 1,
//         'title' => 'Post 1',
//         'userId' => 111,
//         'user' => ['id' => 111, 'name' => 'User 1']
//     ],
//     [
//         'id' => 2,
//         'title' => 'Post 2',
//         'userId' => 222,
//         'user' => ['id' => 222, 'name' => 'User 2']
//     ]
// ]
```

### Исключение ключей

Вы можете исключить определенные ключи из результата с помощью параметров `exceptKeysFromArrayOne` и `exceptKeysFromArrayMany`:

```php
<?php

use Inilim\ArrayCombining\ArrayCombining;

$posts = [
    ['id' => 1, 'title' => 'Post 1', 'userId' => 111],
    ['id' => 2, 'title' => 'Post 2', 'userId' => 222],
];

$users = [
    ['id' => 111, 'name' => 'User 1', 'email' => 'user1@example.com'],
    ['id' => 222, 'name' => 'User 2', 'email' => 'user2@example.com'],
];

$combiner = new ArrayCombining();
$result = $combiner->oneToOne(
    $posts, 
    $users, 
    'userId', 
    'id', 
    'user',
    ['userId'], // исключить ключ 'userId' из массива $posts
    ['id']      // исключить ключ 'id' из массива $users
);

// Результат:
// [
//     [
//         'id' => 1,
//         'title' => 'Post 1',
//         'user' => ['name' => 'User 1', 'email' => 'user1@example.com']
//     ],
//     [
//         'id' => 2,
//         'title' => 'Post 2',
//         'user' => ['name' => 'User 2', 'email' => 'user2@example.com']
//     ]
// ]
```

## Методы

### `oneToMany(array $arrayOne, array $arrayMany, $keyArrayOne, $keyArrayMany, $finalKey, array $exceptKeysFromArrayOne = [], array $exceptKeysFromArrayMany = []): array`

Объединяет массивы с отношением один ко многим.

- `$arrayOne` - первый массив
- `$arrayMany` - второй массив
- `$keyArrayOne` - ключ в первом массиве для сопоставления
- `$keyArrayMany` - ключ во втором массиве для сопоставления
- `$finalKey` - ключ в результирующем массиве, куда будут помещены связанные элементы
- `$exceptKeysFromArrayOne` - массив ключей для исключения из первого массива
- `$exceptKeysFromArrayMany` - массив ключей для исключения из второго массива

### `oneToOne(array $arrayPrimary, array $arraySecondary, $keyArrayPrimary, $keyArraySecondary, $finalKey, array $exceptKeysFromArrayPrimary = [], array $exceptKeysFromArraySecondary = []): array`

Объединяет массивы с отношением один к одному.

- `$arrayPrimary` - первый массив
- `$arraySecondary` - второй массив
- `$keyArrayPrimary` - ключ в первом массиве для сопоставления
- `$keyArraySecondary` - ключ во втором массиве для сопоставления
- `$finalKey` - ключ в результирующем массиве, куда будет помещен связанный элемент
- `$exceptKeysFromArrayPrimary` - массив ключей для исключения из первого массива
- `$exceptKeysFromArraySecondary` - массив ключей для исключения из второго массива

## Требования

- PHP >= 7.4

## Лицензия

MIT