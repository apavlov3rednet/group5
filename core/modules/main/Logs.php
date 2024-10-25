<?php

namespace Main;

use DateTime;

//$_SERVER['DOCUMENT_ROOT'] - Cсерверный корень - /home/www/html_home/
define('CONST_LOG_FILE', $_SERVER['DOCUMENT_ROOT'] . '/core/logs.log'); 

final class Logs
{
    static public function add2Log(mixed $log, string $type = 'error'): void 
    {
        //fopen, fwrite, fclose
        $log = '----------\n\r';
        $log .= 'type: ' . $type . '\n\r';
        $log .= print($log);

        file_put_contents(CONST_LOG_FILE, $log);
    }
}