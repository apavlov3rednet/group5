<?php

namespace Main;

class Asset {

    static public function addExternalCss(string $css) {
        if(file_exists($css)) {
            echo '<link rel="stylesheet" href="' . $css . '"/>';
        }
    }

    /**
     * Summary of addExternalJs
     * @param string $js
     * @param array $params = [
     *  'defer' = true,
     *  'async' = true
     * ]
     * @return void
     */
    static public function addExternalJs(string $js, array $params = []) {
        if(file_exists($js)) {
            $defer = $params['defer'] == true ? 'defer' : '';
            $async = $params['async'] == true ? 'async' : '';

            echo '<script src="' . $js . '" '. $defer .' ' . $async . '></script>';
        }
    }

    static public function addHeadString($string) {
        
    }
}