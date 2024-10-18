<?php

namespace Main;

final class Settings
{
    private $arSettings = [];
    public $e;

    public function __construct() {
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/core/.settings.php')) {
            $this->arSettings = require_once($_SERVER['DOCUMENT_ROOT'] . '/core/.settings.php');
            //include, include_once, require, require_once - подключение файла
            //$this->arSettings;
        }
        else {
            $this->e = new \Exception('Файл отсутствует');
        }
    }

    public function getDbParams(string $dbName = 'default'): array 
    {
        return $this->arSettings['connections']['value'][$dbName];
    }

    public function getSessionParams(): array
    {
        return $this->arSettings['session'];
    }

    public function getCookieParams(): array
    {
        return $this->arSettings['cookie'];
    }

    public function getCacheParams(): array
    {
        return $this->arSettings['cache_flags'];
    }
}
