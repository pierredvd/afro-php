<?php

    /**
     * Afro framework
     * @package    Afro
     * @version    1.0
     * @author     DAVID Pierre
     */

    /**
     * Classe de gestion des prérequis systeme
     */
    namespace Afro;
    
    abstract class Autotest{

        /**
         * Chemin d'accès du dossier "app"
         */
        private static $rootPath                = '';
        
        /**
         * Version php minimale requise
         */
        private static $requireMinPHPVersion    = 5.3;
        
        /**
         * Modules apaches requis
         */
        private static $requireApacheModules    = array('mod_rewrite');
        
        /**
         * Dossier applicatifs requis
         */
        private static $requireAppDirs          =  array(
            'app/cache', 
            'app/class', 
            'app/config', 
            'app/controller', 
            'app/lang', 
            'app/log', 
            'app/model' 
        );

        /**
         * Dossier applicatifs en ecriture requis
         */
        private static $requireAppWritableDirs =  array(
            'app/cache', 
            'app/log', 
        );

        /**
         * Initialise le module
         */
        public static function init($rootPath){
            self::$rootPath = $rootPath;
            $trace = array();
            if(!self::test($trace)){
                
                $html = '<table>';
                foreach($trace as $name=>$value){
                    $html .= '<tr><td>'.$name.'</td><td>'.($value?'<span class="success">Yes</span>':'<span class="error">No</span>').'</td></tr>';
                }
                $html .= '<table>';
                $template = self::getNativeTemplate();
                $template = str_replace('[TRACE]', $html, $template);
                ob_clean();
                ob_start();
                    header('Content-Type', 'text/html');
                    header('Content-Length', strlen($template));
                    echo $template;
                ob_flush();
                exit();
            }
        }
        
        /**
         * Monte le systeme de capture d'erreur
         * @access private
         * @param string    Message d'erreur en reference
         * @return boolean  Resultat du test
         */
        public static function test(&$trace=array()){
            
            $error = false;
            
            try{

                // Root path
                $trace['Spoon Root path'] = is_dir(self::$rootPath);
                
                // Test php version
                if(function_exists('phpversion')){
                    $phpVersion     = explode('.', phpversion());
                    $major          = array_shift($phpVersion);
                    $phpVersion     = floatval($major . '.' . implode('', $phpVersion));
                    if($phpVersion<self::$requireMinPHPVersion){
                        $trace['PHP version '.self::$requireMinPHPVersion.'+ require'] = false;
                        $error = true;
                    } else {
                        $trace['PHP version '.self::$requireMinPHPVersion.'+ require'] = true;
                    }
                } else {
                    $trace['Ignore phpversion test, function "phpversion" not found'];
                }


                // Test apache module
                if(function_exists('apache_get_modules')){
                    $modules = @apache_get_modules();
                    foreach(self::$requireApacheModules as $requiredModule){
                        if(!in_array($requiredModule, $modules)){
                            $trace['Apache module "'.$requiredModule.'" required'] = false;
                            $error = true;
                        } else {
                            $trace['Apache module "'.$requiredModule.'" required'] = true;
                        }
                    }
                } else {
                    $trace['Ignore apache module test, function "apache_get_modules" not found'] = true;
                }
                
                // Test required dirs
                foreach(self::$requireAppDirs as $requireDir){
                    $path = str_replace('/', DIRECTORY_SEPARATOR, self::$rootPath.$requireDir.DIRECTORY_SEPARATOR);
                    if(!is_dir($path)){
                        $trace['Dir "'.$requireDir.'" readable'] = false;
                        $error = true;
                    } else {
                        $trace['Dir "'.$requireDir.'" readable'] = true;
                    }
                }

                // Test dir path rights
                foreach(self::$requireAppWritableDirs as $requireWritableDir){
                    $path = str_replace('/', DIRECTORY_SEPARATOR, self::$rootPath.$requireWritableDir.DIRECTORY_SEPARATOR);
                    try{
                        $fp = fopen($path.'test.md', 'w+', 0);
                        if(is_resource($fp)){
                            @fwrite($fp, '-');
                            @fclose($fp);
                        }
                    } catch(\Exception $e){}
                    if(!is_file($path.'test.md')){
                        $trace['Dir "'.$requireWritableDir.'" writable'] = false;
                        $error = true;
                    } else {
                       $trace['Dir "'.$requireWritableDir.'" writable'] = true;
                       @unlink($path.'test.md');
                    }
                }
            } catch(\Exception $e){
                $trace['Test fail: '.$e->getMessage()];
                $error = true;
            }
            return !$error;
        }
        
        /**
         * Charge le template natif
         * @access private
         * @return string
         */
        private static function getNativeTemplate(){
            $data = file_get_contents(__FILE__);
            $template   = '';
            $start      = strrpos($data, "__halt_compiler();");
            if($start>=0){
                return substr($data, $start+18);
            }
            return false;
        }
        
    }
    __halt_compiler();
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <style>
            body{
                background-color        : #f0f0f0;
                margin                  : 0px;
                padding                 : 0px;
            }
            .title{
                display                 : block;
                height                  : 70px;
                line-height             : 70px;
                font-family             : Tahoma;
                font-size               : 34px;
                border-bottom           : solid 1px #666666;
                -webkit-box-sizing      : border-box;
                   -moz-box-sizing      : border-box;
                    -ms-box-sizing      : border-box;
                     -o-box-sizing      : border-box;
                        box-sizing      : border-box;
                background-color        : #444444;
                color                   : #f0f0f0;
                padding                 : 0px 10px;
            }
            .message{
                font-family             : Tahoma;
                font-size               : 13px;
                -webkit-box-sizing      : border-box;
                   -moz-box-sizing      : border-box;
                    -ms-box-sizing      : border-box;
                     -o-box-sizing      : border-box;
                        box-sizing      : border-box;
                background-color        : #f0f0f0;
                color                   : #444444;
                padding                 : 0px 0px;
            }
            table{
                width: 100%;
                padding: 0px;
                margin: 0px;
                border-spacing: 0px;
                border-collapse: separate;                
                border-left: solid 1px #666666;
                border-top: solid 1px #666666;
            }
            table tr:nth-child(2n) td{
                background-color        : #e5e5e5;
            }
            table tr td:first-child{
                width: 250px;
                text-align: left;
            }
            table tr td{
                padding: 5px 8px;
                margin: 0px;
                border-bottom: solid 1px #666666;
                border-right: solid 1px #666666;
            }
            .success{
                color: #008000;
            }
            .error{
                color: #800000;
                font-weight: bold;
            }
            
        </style>
    </head>
    <body>
        <span class="title">Afro requirement</span>
        <span class="message">
            [TRACE]
        </span>
    </body>
</html>