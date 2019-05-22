<?php


class stationController
{
    public function add_route()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }

        $conn = Database::getConnection();
        $query = "BEGIN :r := ADAUGA_TRASEU( :tren, :plecare, :destinatie, :distanta ); END;";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":r", $return, 1000);
        oci_bind_by_name($stid, ":tren", $_POST['id_train']);
        oci_bind_by_name($stid, ":plecare", $_POST['plecare']);
        oci_bind_by_name($stid, ":destinatie", $_POST['destinatie']);
//        oci_bind_by_name($stid, ":ora_plecare", $_POST['ora_plecare']);
//        oci_bind_by_name($stid, ":ora_sosire", $_POST['ora_sosire']);
        oci_bind_by_name($stid, ":distanta", $_POST['distanta']);
        oci_execute($stid);

        if($return > 0) {
            $_SESSION['route_message'] = "Route is insert with id $return";
            Application::redirectTo("/home/station");
        }
    }

    public function add_station()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }

        $conn = Database::getConnection();
        $query = "BEGIN :r := ADAUGA_STATIE( :name, :time ); END;";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":r", $return,1000);
        oci_bind_by_name($stid, ":name", $_POST['name']);
        oci_bind_by_name($stid, ":time", $_POST['time']);
        oci_execute($stid);

        if($return > 0) {
            Application::redirectTo("/home/station");
        }
    }

    public function update_route()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }

        $conn = Database::getConnection();
        $query = "UPDATE ROUTE SET HOUR_DEPARTURE = str_to_date(:ora_plecare, 'HH24:MI'), HOUR_ARRIVAL = str_to_date(:ora_sosire, 'HH24:MI') WHERE id= :id";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":id", $_POST['id_route']);
        oci_bind_by_name($stid, ":ora_plecare", $_POST['ora_plecare']);
        oci_bind_by_name($stid, ":ora_sosire", $_POST['ora_sosire']);
        oci_execute($stid);

        Application::redirectTo("/home/station");
    }

    public function update_station()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }

        $conn = Database::getConnection();
        $query = "UPDATE STATION SET WAITING_TIME = :time WHERE id= :id";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":id", $_POST['id_statie']);
        oci_bind_by_name($stid, ":time", $_POST['time']);
        oci_execute($stid);

        Application::redirectTo("/home/station");
    }
}
