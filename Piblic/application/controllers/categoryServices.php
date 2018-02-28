<?php
    /**
     * @author Yassine EL HALAOUI on 20/01/2018
     * @filesource categoryServices.php
     */

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    require_once '../dbConfig.php';
    require_once '../models/category.php';
    
    $statement = "SELECT * FROM ecommerce._category";
    $isSelect = true;
    
    if (isset($_GET["action"]) && ! empty($_GET["action"])) {
        $action = $_GET["action"];
        
        switch ($action) {
            case "readById":
                if (isset($_GET["idCat"]) && !empty($_GET["idCat"])) {
                    $idCat = htmlspecialchars($_GET['idCat']);
                    $statement = $statement . " where id_cat = ?";
                    $params = array($idCat);
                } else {
                    die(json_encode(array("error" => "400 Bad Request.")));
                }
                break;
        }
    }
    
    // execute the statement
    $stmt = dbConfig::getConnextion()->prepare($statement);
    $stmt->execute($params);
        
    if ($isSelect) {
        $productList = array();
        $productList["result"] = array();

        // check if more than 0 record found
        if ($stmt->rowCount() > 0) {
            while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($rs);
                $product = array(
                    'id' => $id_cat,
                    'name' => $name_cat,
                    'desc' => $description
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

