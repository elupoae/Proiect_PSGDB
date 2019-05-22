<?php
/**
 * Created by PhpStorm.
 * User: NicuNeculache
 * Date: 22.05.2019
 * Time: 13:11
 */

class trainController
{
    public function add_train()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }

        $conn = Database::getConnection();
        $query = "BEGIN :r := ADAUGA_TREN( :tip ); END;";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":r", $return, 1000);
        oci_bind_by_name($stid, ":tip", $_POST['type']);
        oci_execute($stid);

        if($return > 0)
            Application::redirectTo("/home/trains");
    }
    public function add_carload()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }

        $conn = Database::getConnection();
        $query = "BEGIN :r := ADAUGA_VAGON( :tren, :clasa, :locuri ); END;";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":r", $return, 1000);
        oci_bind_by_name($stid, ":tren", $_POST['id_train']);
        oci_bind_by_name($stid, ":clasa", $_POST['type_carload']);
        oci_bind_by_name($stid, ":locuri", $_POST['nr_seats']);
        oci_execute($stid);

        if($return > 0)
            Application::redirectTo("/home/trains");
    }

    public function update_status()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }

        $conn = Database::getConnection();
        $query = "UPDATE STATUS SET STATUS = :status, STATUS_TIME = :delay WHERE ID_TRAIN= :id";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":r", $return);
        oci_bind_by_name($stid, ":status", $_POST['status']);
        oci_bind_by_name($stid, ":delay", $_POST['delay']);
        oci_bind_by_name($stid, ":id", $_POST['id_train']);
        oci_execute($stid);

        Application::redirectTo("/home/trains");
    }
}
