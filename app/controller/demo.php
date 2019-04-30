<?php

    /**
     * Controller par defaut
     */
    namespace App\Controller;
    
    use \App\Model\User as ModelUser;

    class Demo{

        public function index($p1=null, $p2=null, $p3=null){
            return new \Afro\View\Json(['status'=> 1, 'data' => ['p1' => $p1, 'p2' => $p2, 'p3' => $p3]], 200);
        }

        public function data(){
            return new \Afro\View\Json(['status'=> 1, 'data' => ModelUser::listAll()], 200);
        }
        
        public function notfound(){
            return new \Afro\View\Json(['status'=> 0, 'error' => 'not found'], 404);
        }
       
    }