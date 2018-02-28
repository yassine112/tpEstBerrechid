<?php

/***
 * 
 * @author Yassine EL HALAOUI on 15/01/2018
 * @filesource dbConfig.php
 */
class dbConfig
{
    const DB_HOST = "localhost";
    const DB_NAME = "ecommerce";
    const DB_USER = "root";
    const DB_PASS = "qLMaKF94-Bai";

    private static $PDOInstance;
    
    private function __construct() {}

    /**
     * Creates a PDO instance representing a connection to a database 
     * @return PDO
     */
    public static function getConnextion()
    {
        if (empty(self::$PDOInstance)) {
            try {
                self::$PDOInstance = new PDO('mysql:dbname='.self::DB_NAME.';host='.self::DB_HOST,self::DB_USER ,self::DB_PASS);
            } catch (PDOException $e) {
                print $e->getMessage();
            }
        }
        
        return self::$PDOInstance;
    }
}