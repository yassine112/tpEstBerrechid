<?php

/**
 * 
 * @author Yassine EL HALAOUI on 15/01/2018
 * @filesource order.php
 */
class order{
    private $idOrder;
    private $total;
    private $dateOrder;
    private $costomer;

    /**
     * Constructor with params
     * 
     * @param int $idOrdre
     * @param float $total
     * @param DateTime $dateOrder
     * @param costomer $costomer
     */
    public function __construct($idOrdre, $total, $dateOrder, $costomer) {
        $this->idOrder = $idOrdre;
        $this->total = $total;
        $this->dateOrder = $dateOrder;
        $this->costomer = $costomer;
    }
    
    /**
     * @return int
     */
    public function getIdOrder()
    {
        return $this->idOrder;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return DateTime
     */
    public function getDateOrder()
    {
        return $this->dateOrder;
    }

    /**
     * @return costomer
     */
    public function getCostomer()
    {
        return $this->costomer;
    }

    /**
     * @param int $idOrder
     */
    public function setIdOrder($idOrder)
    {
        $this->idOrder = $idOrder;
    }

    /**
     * @param float $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @param DateTime $dateOrder
     */
    public function setDateOrder($dateOrder)
    {
        $this->dateOrder = $dateOrder;
    }

    /**
     * @param costomer $costomer
     */
    public function setCostomer($costomer)
    {
        $this->costomer = $costomer;
    }

}