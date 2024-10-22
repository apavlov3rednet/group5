<?php

namespace Main;

class Application {
    public function __construct() {
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
    static public function includeComponent(string $name, string $template, array $parameters) {
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

        //Подключение мутатора результата
        if(file_exists($templatePath . '/result_modifer.php')) {
            require $templatePath . '/result_modifer.php';
        }

        //Подключение стилей и скриптов по умолчанию
        Asset::addExternalCss($templatePath . '/style.css');
        Asset::addExternalJs($templatePath . '/script.js');

        //Старт работы компонента
        if(file_exists($componentPath . '/component.php')) {
            require $componentPath . '/component.php';
        }
    }
}