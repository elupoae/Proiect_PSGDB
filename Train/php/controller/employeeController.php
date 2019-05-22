<?php
/**
 * Created by PhpStorm.
 * User: NicuNeculache
 * Date: 22.05.2019
 * Time: 13:30
 */

class employeeController
{
    public function add()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }

        $conn = Database::getConnection();
        $query = "BEGIN :r := ADAUGA_ANGAJAT(:nume, :prenume, :telefon, :job, :tren); END;";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":r", $return,1000);
        oci_bind_by_name($stid, ":nume", $_POST['last_name']);
        oci_bind_by_name($stid, ":prenume", $_POST['first_name']);
        oci_bind_by_name($stid, ":telefon", $_POST['phone']);
        oci_bind_by_name($stid, ":job", $_POST['job']);
        oci_bind_by_name($stid, ":tren", $_POST['id_train']);
        oci_execute($stid);

        if($return > 0)
        {
            Application::redirectTo("/home/employee");
        }
    }

    public function update_phone()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }

        $conn = Database::getConnection();
        $query = "UPDATE EMPLOYEE SET PHONE_NUMBER = :telefon WHERE ID= :id";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":id", $_POST['id']);
        oci_bind_by_name($stid, ":telefon", $_POST['phone']);
        $r = oci_execute($stid);

//        var_dump($r);
        Application::redirectTo("/home/employee");
    }

    public  function update_train()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }

        $conn = Database::getConnection();
        $query = "UPDATE JOB SET ID_TRAIN = :train WHERE ID_EMPLOYEE= :id";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":id", $_POST['id']);
        oci_bind_by_name($stid, ":train", $_POST['id_train']);
        $r = oci_execute($stid);

//        var_dump($r);
        Application::redirectTo("/home/employee");
    }
}
