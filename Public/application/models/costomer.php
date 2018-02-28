<?php

/**
 *
 * @author Yassine EL HALAOUI on 15/01/2018
 * @filesource costomer.php
 */
class costomer {
    private $idCost;
    private $fname;
    private $lname;
    private $email;
    private $password;

    /**
     * Constructor with params
     * 
     * @param int $id_cost
     * @param String $fname
     * @param String $lname
     * @param String $email
     * @param String $password
     */
    public function __construct($id_cost, $fname, $lname, $email, $password) {
        $this->idCost = $id_cost;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->password = md5($password);
    }
    
    /**
     * @return mixed
     */
    public function getId_cost()
    {
        return $this->idCost;
    }

    /**
     * @return mixed
     */
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * @return mixed
     */
    public function getLname()
    {
        return $this->lname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $id_cost
     */
    public function setId_cost($id_cost)
    {
        $this->idCost = $id_cost;
    }

    /**
     * @param mixed $fname
     */
    public function setFname($fname)
    {
        $this->fname = $fname;
    }

    /**
     * @param mixed $lname
     */
    public function setLname($lname)
    {
        $this->lname = $lname;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = md5($password);
    }

}