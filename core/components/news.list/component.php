<?php

/**
 * @var array $arParams;
 * @var array $arResult;
 * @var string $templatePath;
 * @var string $componentPath;
 */

 //Используемые в компоненте классы
 use Db\Basic;
 use Main\Asset;
 use Main\Settings;
 use Main\Application;
 //use \Exception;

//Подключение дефолтных стилей и скриптов
Asset::addExternalCss($templatePath . '/style.css');
Asset::addExternalJs($templatePath . '/script.js');

//Подготовка параметров
$settings = new Settings();
$cache = $settings->getCacheParams();

$arParams['CACHE_TIME'] = (isset($arParams['CACHE_TIME'])) ? $arParams['CACHE_TIME'] : $cache['value']['config_options'];
$arParams['TABLE_NAME'] = (isset($arParams['TABLE_NAME'])) ? $arParams['TABLE_NAME'] : null;

if(!$arParams['TABLE_NAME']) {
   // throw new Exception('Не указано имя таблицы');
}

$arParams['COUNT_ELEMENT'] = (isset($arParams['COUNT_ELEMENT'])) ? $arParams['COUNT_ELEMENT'] : 10;
$arParams['QUERY'] = (isset($arParams['QUERY'])) ? $arParams['QUERY'] : [];

//Текущая страница и стартовая позиция запроса к бд
if(isset($_GET['page'])) {
    $offset =(int)$_GET['page'] * $arParams['COUNT_ELEMENT'] + 1 - $arParams['COUNT_ELEMENT'];
}
else {
    $offset = 1;
}


$curPage = Application::getCurPage() . $templatePath;

//Файл кеша
$nameCacheFile = md5($curPage) . '.html'; //хеширование
$cacheFile = $cache['value']['cache_path'] . $nameCacheFile;

//кеширование
//чтение кешированного файла
if(file_exists($cacheFile)) {
    if((time() - $arParams['CACHE_TIME']) < filemtime($cacheFile)) {
        echo file_get_contents($cacheFile);
        exit;
    }
}

//создание кеша
ob_start();

$params = [];

$ob = new Basic();
$arResult = $ob->getList($arParams['TABLE_NAME'], $params);

if(file_exists($templatePath . '/result_modifer.php')) {
    require $templatePath . '/result_modifer.php';
}

if(file_exists($templatePath . '/template.php')) {
    require $templatePath . '/template.php';
}

//открыли файл на перезапись
$handle = fopen($cacheFile, 'w');
//записали в файл данные из object buffer
fwrite($handle, ob_get_contents());
//закрыли файл
fclose($handle);
//вывели на экран содержимое object buffer
ob_end_flush();