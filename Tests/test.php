<?php

\ini_set('error_reporting', E_ALL);

require_once '../vendor/autoload.php';

use Inilim\Dump\Dump;
use Inilim\IPDO\IPDOMySQL;
use Inilim\ArrayCombining\ArrayCombining;

Dump::init();

dUsage();

$conn = new IPDOMySQL('remfy_local', 'root', '', 'MySQL-8.2');

$orders = $conn->exec('SELECT * FROM designer_orders LIMIT 25', 2);

$contacts = $conn->exec(
    'SELECT CO.order_id, C.* FROM designer_contact_order AS CO
        JOIN designer_contacts AS C
        ON CO.contact_id = C.id AND CO.order_id IN ({order_id})',
    ['order_id' => \array_column($orders, 'id')],
    2
);

$a = new ArrayCombining;

$orders = [
    [
        'key1' => 1,
        'key2' => 1,
    ],
    [
        'key1' => 1,
        'key2' => 1,
    ],
];

$contacts = [
    [
        'key3' => 1,
        'key4' => 1,
    ],
    [
        'key3' => 1,
        'key4' => 1,
    ],
];

$res = $a->oneToMany($orders, $contacts, 'id', 'order_id', 'contacts', [], ['order_id']);

unset($orders, $contacts);

// deUsage();
de($res);
