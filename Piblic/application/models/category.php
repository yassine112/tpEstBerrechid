<?php

/**
 * 
 * @author Yassine EL HALAOUI on 15/01/2018
 * @filesource category.php
 */
class category {
    
    private $idCat;
    private $nameCat;
    private $description;

    /**
     * constructor with params
     * 
     * @param int $id_cat
     * @param String $name_cat
     * @param String $description
     */
    public function __construct($id_cat, $name_cat, $description) {
        $this->idCat = $id_cat;
        $this->nameCat = $name_cat;
        $this->description = $description;
    }
    
    /**
     * @return mixed
     */
    public function getId_cat()
    {
        return $this->idCat;
    }

    /**
     * @return mixed
     */
    public function getName_cat()
    {
        return $this->nameCat;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $id_cat
     */
    public function setId_cat($id_cat)
    {
        $this->idCat = $id_cat;
    }

    /**
     * @param mixed $name_cat
     */
    public function setName_cat($name_cat)
    {
        $this->nameCat = $name_cat;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

}