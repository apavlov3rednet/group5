<?php

spl_autoload_register(function($class) 
{
    //Корневая директория классов
    $baseDir = $_SERVER['DOCUMENT_ROOT'] . '/core/modules/';

    //Получаем относительный путь к файлу класса
    $relativeClass = str_replace('\\','/', subject: $class);

    //полный путь к файлу класса
    $file = $baseDir . $relativeClass .'.php';

    //Если файл существует то подключаем его
    if(file_exists($file)) {
        require $file;
    }
});