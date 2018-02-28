<?php
    /**
     * @author Yassine EL HALAOUI on 18/01/2018
     * @filesource costomerServices.php
     */

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    
    require_once '../dbConfig.php';
    require_once '../models/costomer.php';
    
    $statement = "SELECT * FROM ecommerce._costomer";
    $isSelect = true;

    function emailExists($email) {
        $stat = "SELECT * FROM ecommerce._costomer WHERE email = ?";
        $stmt = dbConfig::getConnextion()->prepare($stat);
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
    
    if (isset($_GET["action"]) && !empty($_GET["action"])) {
        $action = $_GET["action"];
        $json = json_decode(file_get_contents("php://input"));

        switch ($action) {
            case "check" :

                if (isset($json->data) && count($json->data) === 2) {
                    $data = $json->data;

                    $statement = $statement . " where email = ? and pass = ?";
                    $params = $data;
                } else {
                    die(json_encode(array("error" => "400 Bad Request.")));
                }

                break;

            case "register" :

                if (isset($json->data) && count($json->data) === 4) {
                    $data = $json->data;

                    if (!emailExists($data[2])) {
                        $statement = "INSERT INTO `ecommerce`.`_costomer` (`fname`,`lname`,`email`,`pass`) VALUES (?,?,?,?)";
                        $params = $data;

                        $isSelect = false;
                    } else {
                        die(json_encode(array("error" => "email already exists.")));
                    }

                } else {
                    die(json_encode(array("error" => "400 Bad Request.")));
                }

                break;
            default :
                break;

        }
    }

    //execute the statement
    try {
        $stmt = dbConfig::getConnextion()->prepare($statement);
        $stmt->execute($params);
    } catch (PDOException $exception) {
        die("no");
    }
    
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
                    "id" => $id_costomer,
                    "firstname" => $fname,
                    "lastname" => $lname,
                    "email" => $email
                );
                array_push($productList["result"], $product);
            }
            
            die(json_encode($productList));
        } else {
            die(json_encode(array("error" => "No result found.")));
        }
    } else {
        die(json_encode(array("success" => "")));
    }