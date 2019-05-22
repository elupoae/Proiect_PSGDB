<?php


class ticketController
{
    public function new($id)
    {
        if($id < 1)
            echo "id gresit!";
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }

        $conn = Database::getConnection();
        $query = "BEGIN :r := CUMPARARE_BILET( :cnp, :legitimatie, :tren, CURRENT_DATE() ); END;";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":r", $return,100);
        oci_bind_by_name($stid, ":cnp", $_POST['cnp']);
        oci_bind_by_name($stid, ":legitimatie", $_POST['legitimatie']);
        oci_bind_by_name($stid, ":tren", $id);
        oci_execute($stid);

        if($return > 0)
            Application::redirectTo();
    }
}
