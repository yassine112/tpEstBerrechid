<?php    
    /**
     * @author Yassine EL HALAOUI on 18/01/2018
     * @filesource productServices.php
     */

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    require_once '../dbConfig.php';
    require_once '../models/product.php';

    $statement = "SELECT * FROM _product";
    $isSelect = true;
    
    if (isset($_GET["action"]) && !empty($_GET["action"])) {
        $action = $_GET["action"];
        
        // Create sql statement dependent to action
        switch ($action) {
            case "readById" :
                if (isset($_GET["idprod"]) && !empty($_GET["idprod"])) {
                    $idprod = htmlspecialchars($_GET['idprod']);
                    $statement = $statement . " where  id_prod = ?";
                    $params = array($idprod);
                } else {
                    die(json_encode(array("error" => "400 Bad Request.")));
                }
                break;
                
            case "readByCat" :
                if (isset($_GET["idCat"]) && !empty($_GET["idCat"])) {
                    $idprod = htmlspecialchars($_GET['idCat']);
                    $statement = $statement . " where  id_cat = ?";
                    $params = array($idprod);
                } else {
                    die(json_encode(array("error" => "400 Bad Request.")));
                }
                break;
        }
    }

    //execute the statement
    $stmt = dbConfig::getConnextion()->prepare($statement);
    $stmt->execute($params);

    if ($isSelect) {
        $productList = array();
        $productList["result"] = array();

        // check if more than 0 record found
        if ($stmt->rowCount() > 0 ) {
            while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {

                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($rs);
                $product = array(
                    "id" => $id_prod,
                    "name" => $name_prod,
                    "desc" => $description,
                    "price" => $price,
                    "qut" => $quantity_in_stock,
                    "img" => $img,
                    "cat" => $id_cat
                );
                array_push($productList["result"], $product);
            }

            echo json_encode($productList);
        } else {
            echo json_encode(array("error" => "No result found."));
        }
    } else {
        die(json_encode(array("success" => "")));
    }
