<?php

$input = [
  'q' => 'Минск',
  'appid' => '' //для получения данных нужно прописать APPID
]; 

$url = 'https://api.openweathermap.org/data/2.5/weather?'.http_build_query($input);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);
$output = json_decode($output);

if ($output->cod[0] == 4) {
    /**
     * Запись ошибки в файл
     */
    file_put_contents('./errors.txt', date('Y-m-d H:i:s') .' '. json_encode($output) . PHP_EOL, FILE_APPEND);
} else {
    /**
     * Добавление данных в таблицу
     */
    try {
        $host = 'localhost';
        $db   = 'phonebook';
        $user = 'root';
        $pass = '';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $user, $pass, $opt);

        $prepared_params[':city_name'] = $output->name;
        $prepared_params[':temperature'] = $output->main->temp;
        $prepared_params[':wind_speed'] = $output->wind->speed;
        $prepared_params[':weather_description'] = $output->weather[0]->description;
        $prepared_params[':weather_icon'] = $output->weather[0]->icon;

    // city_name, temperature, wind_speed, weather_description, weather_icon, dt 
        $sql = "INSERT IGNORE INTO weather (city_name, temperature, wind_speed, weather_description, weather_icon)
                VALUES (:city_name, :temperature, :wind_speed, :weather_description, :weather_icon)";

        $prepare = $pdo->prepare($sql);
        $prepare->execute($prepared_params);
        $prepare->fetchAll();
    } 
    catch (PDOException $e) {
        /**
         * Запись ошибки в файл
         */
        file_put_contents('./errors.txt', date('Y-m-d H:i:s') .' '. $e->getMessage() . PHP_EOL, FILE_APPEND);
    }
}