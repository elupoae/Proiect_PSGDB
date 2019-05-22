<?php

class homeController extends Controller
{
    public function index()
    {
        $data = [];
        $conn = Database::getConnection();
        $query = 'select count(*) from train';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH);
        $data['train'] = $row[0];
        $query = 'select count(*) from station';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH);
        $data['station'] = $row[0];
        $query = 'select count(*) from route';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH);
        $data['route'] = $row[0];
        $query = 'select count(*) from employee';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH);
        $data['employee'] = $row[0];
        $query = 'select count(*) from status WHERE status = \'INTARZIAT\'';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH);
        $data['delay'] = $row[0];

        $this->view('index',$data);
        $this->view->render();
    }

    public function buy_ticket($flag = 0)
    {
        $data = [];
        if($flag !=0)
        {
            $data['select_date'] = 1;
        }
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
        if (strtolower($_SERVER["REQUEST_METHOD"]) == "post") {
            $query = "BEGIN :r := find_train(:plecare, :destinatie); END;";
            $stid = oci_parse($conn, $query);
            oci_bind_by_name($stid, ":r", $return, 1000);
            oci_bind_by_name($stid, ":plecare", $_POST['plecare']);
            oci_bind_by_name($stid, ":destinatie", $_POST['sosire']);
            $r = oci_execute($stid);
            var_dump($r);
            if(is_string($return))
            {
                $route = explode("|", $return);
                $item = [];
                $item['type'] = $route[0];
                $item['id_tren'] = $route[1];
                $item['plecare'] = $route[2];
                $item['ora_plecare'] = $route[3];
                $item['destinatie'] = $route[4];
                $item['ora_sosire'] = $route[5];
                $item['distanta'] = $route[6];
                $item['pret'] = $route[7];
                array_push($data['routes'], $item);
            }
        }
        else
        {
            $query = 'select ID, id_train, find_station_name(id_DEPARTURE), TO_CHAR(HOUR_DEPARTURE, \'HH24:MI P.M.\'), find_station_name(id_arrival), TO_CHAR(HOUR_arrival, \'HH24:MI P.M.\'), distance,
       (select type from type_price where id in (select id_type from train where id = r.id_train)),
       (select full_price_1 from type_price where id in (select id_type from train where id = r.id_train)) from route r WHERE rownum < 100';
            $stid = oci_parse($conn, $query);
            $r = oci_execute($stid);
            while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH)) {
                $item = [];
                $item['id_route'] = $row[0];
                $item['id_tren'] = $row[1];
                $item['plecare'] = $row[2];
                $item['ora_plecare'] = $row[3];
                $item['destinatie'] = $row[4];
                $item['ora_sosire'] = $row[5];
                $item['distanta'] = $row[6];
                $item['type'] = $row[7];
                $item['pret'] = $row[8] * $row[6];
                array_push($data['routes'], $item);
            }
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
        $data['trains'] = [];
        $conn = Database::getConnection();
        $query = 'select ID,NAME_STATION,WAITING_TIME from station';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {
            $item = [];
            $item['id_statie'] = html_entity_decode($row['ID']);
            $item['nume_statie'] = htmlentities($row['NAME_STATION']);
            $item['timp_asteptare'] = htmlentities($row['WAITING_TIME']);
            array_push($data['stations'], $item);
        }
        $query = 'select ID, id_train, find_station_name(id_DEPARTURE), HOUR_DEPARTURE, find_station_name(id_arrival), HOUR_arrival, distance  from route WHERE rownum < 100';
        $stid = oci_parse($conn, $query);
        $r = oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_BOTH)) {
            $item = [];
            $item['id_route'] = $row[0];
            $item['id_tren'] = $row[1];
            $item['plecare'] = $row[2];
            $item['ora_plecare'] = $row[3];
            $item['destinatie'] = $row[4];
            $item['ora_sosire'] = $row[5];
            $item['distanta'] = $row[6];
            array_push($data['routes'], $item);
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
