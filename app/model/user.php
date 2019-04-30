<?php

    /**
     * Classe de modèle d'une table
     */
    namespace App\Model;

    class User extends \Afro\Model{
        
        // Nom de la connexion utilisé (defini en configuration)
        protected static $connexion    = 'default';
        
        /**
         * Liste tous les utilisateurs
         * @return array Utilisateurs
         */
        public static function listAll(){
            $query = '
            SELECT  userid, 
                    login,
                    firstname, 
                    lastname
            FROM    user
            ORDER   BY login ASC;';
            return static::query($query);
        }
        
    }

    