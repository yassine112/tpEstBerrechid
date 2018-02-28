<?php

/**
 * 
 * @author Yassine EL HALAOUI on 15/01/2018
 * @filesource product.php
 */
class product {
    private $idProd;
    private $nameProd;
    private $description;
    private $price;
    private $quantityInStock;
    private $category;

    /**
     * Constructor with params
     * 
     * @param int $id_prod
     * @param String $nameProd
     * @param String $description
     * @param float $price
     * @param int $quantityInStock
     * @param category $category
     */
    public function __construct($id_prod, $nameProd, $description, $price, $quantityInStock, $category) {
        $this->idProd = $id_prod;
        $this->nameProd = $nameProd;
        $this->description = $description;
        $this->price = $price;
        $this->quantityInStock = $quantityInStock;
        $this->category = $category;
    }
    
    /**
     * @return int
     */
    public function getIdProd()
    {
        return $this->idProd;
    }

    /**
     * @return String
     */
    public function getNameProd()
    {
        return $this->nameProd;
    }

    /**
     * @return String
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getQuantityInStock()
    {
        return $this->quantityInStock;
    }

    /**
     * @return category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param int $idProd
     */
    public function setIdProd($idProd)
    {
        $this->idProd = $idProd;
    }

    /**
     * @param String $nameProd
     */
    public function setNameProd($nameProd)
    {
        $this->nameProd = $nameProd;
    }

    /**
     * @param String $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param int $quantityInStock
     */
    public function setQuantityInStock($quantityInStock)
    {
        $this->quantityInStock = $quantityInStock;
    }

    /**
     * @param category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

}