<?php
    /**
     * Afro framework.
     * @package    Spoon
     * @version    1.0
     * @author     DAVID Pierre
     */

    /**
     * Classe de gestion des vues de telechargement
     */
    namespace Afro\View;
    
    class Download extends \Afro\View{

        /**
         * Constructeur d'une réponse de controller
         * @param string Données
         * @param array  Headers
         */
        public function __construct($filepath, array $headers=array()){
            if(!is_file($filepath)){
                throw new \Exception('Download view error, file '.$filepath.' not found');
            }
            $this->data     = $filepath;
            $this->headers  = array_merge(
                array(
                    'Content-Length'            => filesize($filepath),
                    'Content-Transfer-Encoding' => 'binary',
                    'Content-Type'              => 'application/force-download; name='.basename($filepath) . '"',
                    'Content-Disposition'       => 'attachment; filename="'.basename($filepath).'"',
                    'Expires'                   => '0',
                    'Cache-Control'             => 'no-cache, must-revalidate',
                    'Pragma'                    => 'no-cache'
                ),
                $headers
            );
        }
        
        /**
         * Rendu de la réponse
         * @access public
         */
        public function render(){
            foreach($this->headers as $name => $value){
                header($name.': '.$value);
            }
            @readfile($this->data);
        }
        
    }