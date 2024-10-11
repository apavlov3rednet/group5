<?php

namespace Core\Main;

final class Settings
{
    private $arSettings = [];
    public $e;

    public function __construct() {
        if(file_exists('../../.settings.php')) {
            self::$arSettings = require_once('../../.settings.php');
            //include, include_once, require, require_once - подключение файла
            //$this->arSettings;
        }
        else {
            $this->e = new \Exception('Файл отсутствует');
        }
    }

    static public function getDbParams(string $dbName = 'default'): array 
    {
        return self::$arSettings['connections']['value'][$dbName] ?? [];
    }

    public function getSessionParams(): array
    {
        return self::$arSettings['session'] ?? [];
    }

    public function getCookieParams(): array
    {
        return self::$arSettings['cookie'] ?? [];
    }

    public function getCacheParams(): array
    {
        return self::$arSettings['cache_flags'] ?? [];
    }
}
