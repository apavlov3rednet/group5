<?php

namespace Main;

class Application {
    public function __construct() {
    }

    static public function getCurPage(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Summary of includeComponent
     * @param string $name - exmpl, news.list
     * @param string $template - exmpl, default
     * @param array $parameters = [
     *  
     * ]
     * @return void
     */
    static public function includeComponent(string $name, string $template, array $parameters = []):void 
    {
        $arResult = [];

        $componentPath = $_SERVER['DOCUMENT_ROOT'] . '/core/components/' . $name . '/';

        if($template == '') $template = 'default';

        $templatePath = $componentPath . 'templates/'. $template;

        //Подключили дополнительные функции перед вызовом компонента
        if(file_exists($componentPath . '/function.php')) {
            require $componentPath . '/function.php';
        }

        //Подключили параметры шаблона
        if(file_exists($templatePath . '/.parameters.php')) {
            $arParams = require $templatePath . '/.parameters.php';
        }
        $arParams = array_merge($arParams, $parameters);

        //Старт работы компонента
        if(file_exists($componentPath . '/component.php')) {
            require $componentPath . '/component.php';
        }

        //Удаляем переменные после завершения работы компонента. Высвобождаем память сервера
        unset($arResult, $arParams, $templatePath, $componentPath);
    }
}