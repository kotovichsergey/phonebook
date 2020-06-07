<?php


$app->get('/', function ($request, $response) {
    return $this->response->withJson(array('title' => 'Телефонная книга', 'msg' => 'Привет пользователь'));
});

$app->get('/users', 'UserController:index');

$app->post('/user/create', 'UserController:createUser');
$app->delete('/user/{id:[0-9]+}', 'UserController:deleteUser');

$app->post('/user/{id:[0-9]+}', 'UserController:userUpdate');
$app->get('/user/{id:[0-9]+}', 'UserController:userPhones');

$app->post('/user/{id:[0-9]+}/add', 'PhoneController:addUserPhone');
$app->post('/user/{id:[0-9]+}/phone/{phone_id:[0-9]+}', 'PhoneController:phoneUpdate');
$app->delete('/phone/{id:[0-9]+}', 'PhoneController:deletePhone');

$app->get('/weather', 'WeatherController:getWeather');