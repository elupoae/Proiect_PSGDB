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
        $query = 'select ID,NAME_STATION from station';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {
            $item = [];
            $item['id_statie'] = html_entity_decode($row['ID']);
            $item['nume_statie'] = htmlentities($row['NAME_STATION']);
            array_push($data['stations'], $item);
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
        $query = 'select   e.ID,
                           e.FIRST_NAME || \' \' || e.LAST_NAME,
                           e.PHONE_NUMBER,
                           TP.TYPE || \'-\' || t.ID,
                           j.JOB
                            from EMPLOYEE e
                           join job j on e.ID = j.ID_EMPLOYEE
                           join TRAIN T on j.ID_TRAIN = T.ID
                           join TYPE_PRICE TP on T.ID_TYPE = TP.ID where rownum < 100';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH)) {
            $item = [];
            $item['id_angajat'] = $row[0];
            $item['nume_angajat'] = $row[1];
            $item['phone'] = $row[2];
            $item['tren'] = $row[3];
            $item['job'] = $row[4];
            array_push($data['angajati'], $item);
        }
        $query = 'select t.id, p.type from train t join type_price p on p.id=t.id_type where rownum < 100';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH)) {
            $item = [];
            $item['id_tren'] = $row[0];
            $item['type'] = $row[1];
            array_push($data['trains'], $item);
        }

        $this->view('employees', $data);
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
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH)) {
            $item = [];
            $item['id_statie'] = $row[0];
            $item['nume_statie'] = $row[1];
            array_push($data['stations'], $item);
        }
        $query = 'select t.id, p.type, (SELECT count(*) from CARLOAD WHERE id_train=t.id), 
(SELECT sum(number_seats) from CARLOAD WHERE id_train=t.id),
(SELECT count(*) from JOB where id_train=t.id), s.status, s.status_time, s.last_station
from TRAIN t join TYPE_PRICE p on p.id=t.id_type join status s on s.id_train=t.id where rownum < 100';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH)) {
            $item = [];
            $item['id_tren'] = $row[0];
            $item['type'] = $row[1];
            $item['nr_vagoane'] = $row[2];
            $item['nr_locuri'] = $row[3];
            $item['nr_angajati'] = $row[4];
            $item['status'] = $row[5];
            $item['status_time'] = $row[6];
            $item['last_station'] = $row[7];
            array_push($data['trains'], $item);
        }
        $query = 'select id, id_train, class_type, number_seats from CARLOAD where rownum < 100';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH)) {
            $item = [];
            $item['id_vagon'] = $row[0];
            $item['id_tren'] = $row[1];
            $item['clasa'] = $row[2];
            $item['nr_locuri'] = $row[3];
            array_push($data['vagoane'], $item);
        }

        $this->view('trains', $data);
        $this->view->render();
    }


    public function stations()
    {
        $data = [];
        $data['stations'] = [];
        $data['routes'] = [];
        $conn = Database::getConnection();
        $query = 'select ID,NAME_STATION from station';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {
            $item = [];
            $item['id_statie'] = html_entity_decode($row['ID']);
            $item['nume_statie'] = htmlentities($row['NAME_STATION']);
            array_push($data['stations'], $item);
        }

        $this->view('stations', $data);
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
