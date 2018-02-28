<?php
    /**
     * @author yassine el halaoui on 20/01/2018
     * @filesource orderServices.php
     */

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require_once '../dbConfig.php';
    require_once '../models/order.php';
    require_once '../models/product.php';


    // die(json_encode($data->data->idCustomer));

    if (isset($_GET["action"]) && !empty($_GET["action"])) {
        $action = $_GET["action"];

        switch ($action) {
            case "add" :
                // get and decode the content of post data
                $data = json_decode(file_get_contents("php://input"));
                if (isset($data->order)) {
                    $data = $data->order;

                    $idCustomer = $data->idCustomer;
                    $listProdsJ = $data->listProd;

                    $listProds = array();

                    $total = 0;
                    foreach ($listProdsJ as $item) {
                        $p = getProdByID($item->idProd);
                        if (!is_bool($p)) {
                            array_push($listProds, $p);
                            $price = $p->getPrice() * $item->qut;
                            $total = $total + $price;
                        }
                    }

                    if (customerExists($idCustomer)) {
                        $idOrder = addOrder($total, $idCustomer);
                        if (!is_bool($idOrder)) {
                            for ($i = 0 ; $i < count($listProds); $i++) {
                                $tot = $listProdsJ[$i]->qut * $listProds[$i]->getPrice();
                                addOrderLine($idOrder, $listProds[$i]->getIdProd(), $listProdsJ[$i]->qut, $tot);
                            }
                        }
                    } else {
                        die(json_encode(array("error" => "No customer found")));
                    }

                } else {
                    die(json_encode(array("error" => "400 Bad Request.")));
                }
                break;
        }
    }

    function addOrderLine($idOrder, $idProd, $quantity, $totale) {
        // echo $idOrder ."/".$idProd."/".$quantity."/".$totale."</br>";

        // $statement = "INSERT INTO _orderline VALUES (?, ?, ?, ?)";

        $statement = "INSERT INTO `ecommerce`.`_orderline` (`idOrder`, `idProd`, `qut`, `totale`) VALUES (?,?,?,?)";
        $stmt =  dbConfig::getConnextion()->prepare($statement);
        $stmt->execute(array($idOrder,$idProd,$quantity,$totale));
        unset($stmt);
    }

    function getProdByID($id) {
        $statement = "SELECT * FROM _product WHERE  id_prod = ?";
        $stmt = dbConfig::getConnextion()->prepare($statement);
        $stmt->execute(array($id));

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $prod = new product($id_prod, $name_prod, $description, $price, $quantity_in_stock, $id_cat);
                return $prod;
            }
        } else {
            return false;
        }
    }

    function customerExists($id) {
        $statement = "SELECT * FROM _costomer WHERE id_costomer = ?";
        $stmt = dbConfig::getConnextion()->prepare($statement);
        $stmt->execute(array($id));
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    function addOrder($total, $idCustomer) {
        $statement = "INSERT INTO _orderT (totale, date_order, id_costomer) VALUES (?, ?, ?)";
        $stmt =  dbConfig::getConnextion()->prepare($statement);
        if ($stmt->execute([$total, date('Y-m-d'), $idCustomer])) {
            return dbConfig::getConnextion()->lastInsertId();
        }
        return false;
    }

    die(json_encode(array("success" => "order added")));
