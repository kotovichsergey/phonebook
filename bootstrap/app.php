<?php

session_start();

require_once 'vendor/autoload.php';

// $user = new \App\Models\User;
// var_dump($user);
// die();

$config = ['settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'phonebook',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]
    ]
];
$app = new \Slim\App($config);

$container = $app->getContainer();

// Service factory for the ORM
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

$container['validator'] = function ($container) {
    return new App\Validation\Validator;
};

$container['UserController'] = function ($container) {
    return new \App\Controllers\UserController($container);
};
$container['PhoneController'] = function ($container) {
    return new \App\Controllers\PhoneController($container);
};