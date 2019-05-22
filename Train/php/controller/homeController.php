<?php

class homeController extends Controller
{
    public function index()
    {
        $this->view('index');
        $this->view->render();
    }

    public function buy_ticket()
    {
        $data = [];
        $data['stations'] = [];
        $data['routes'] = [];
        $conn = Database::getConnection();
        $query = 'select id,name_station from station';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {
            $data['stations']['id_statie'] = $row[0];
            $data['stations']['nume_statie'] = $row[1];
        }

        $this->view('buy-ticket', $data);
        $this->view->render();
    }

    public function employees()
    {
        $data = [];
        $data['angajati'] = [];
        $data['trains'] = [];
        $conn = Database::getConnection();
        $query = '';// adauga si restul detaliilor
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {
            $data['angajati']['id_angajat'] = $row[0];
            $data['angajati']['nume_angajat'] = $row[1];
            $data['angajati']['phone'] = $row[2];
            $data['angajati']['tren'] = $row[3];
            $data['angajati']['job'] = $row[4];
        }
        $query = 'select t.id, p.type from trains t join type_price p on p.id_train=t.id';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {
            $data['trains']['id_tren'] = $row[0];
            $data['trains']['type'] = $row[1];
        }

        $this->view('employees', $data);
        $this->view->render();
    }

    public function stations()
    {
        $this->view('stations');
        $this->view->render();
    }

    public function trains()
    {
        $data = [];
        $data['vagoane'] = [];
        $data['trains'] = [];
        $data['stations'] = [];
        $conn = Database::getConnection();
        $query = 'select id,name_station from station';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {
            $data['stations']['id_statie'] = $row[0];
            $data['stations']['nume_statie'] = $row[1];
        }
        $query = 'select t.id, p.type, (SELECT count(*) from CARLOAD WHERE id_train=t.id), 
(SELECT sum(number_seats) from CARLOAD WHERE id_train=t.id),
(SELECT count(*) from job where id_train=t.id), s.status, s.status_time, s.last_station
from trains t join type_price p on p.id_train=t.id join status s on s.id_train=t.id';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {
            $data['trains']['id_tren'] = $row[0];
            $data['trains']['type'] = $row[1];
            $data['trains']['nr_vagoane'] = $row[2];
            $data['trains']['nr_locuri'] = $row[3];
            $data['trains']['nr_angajati'] = $row[4];
            $data['trains']['status'] = $row[5];
            $data['trains']['status_time'] = $row[6];
            $data['trains']['last_station'] = $row[7];
        }
        $query = 'select id, id_train, class_type, number_seats from CARLOAD';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {
            $data['vagoane']['id_vagon'] = $row[0];
            $data['vagoane']['id_tren'] = $row[1];
            $data['vagoane']['clasa'] = $row[2];
            $data['vagoane']['nr_locuri'] = $row[3];
        }

        $this->view('trains', $data);
        $this->view->render();
    }
//
//    public function contact()
//    {
//        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
//            http_response_code(400);
//            return;
//        }
////        $_POST['subject'];$_POST['message'];
//
//        Application::redirectTo("/home/infoPage/Thank you!/The message was successfully sent.");
//    }
}
