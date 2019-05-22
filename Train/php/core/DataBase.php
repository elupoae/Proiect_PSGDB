<?php

class Database
{
    const HOST = "localhost/orcl12";
    const USER = "EDUARD";
    const PASSWORD = "EDUARD";
    private static $conn = null;

    public static function setConnection()
    {
        $conn = oci_connect(self::USER, self::PASSWORD, self::HOST);
        if (!$conn) {
            $m = oci_error();
            echo $m['message'], "\n";
            exit;
        }
    }

    public static function getConnection()
    {
        return oci_connect(self::USER, self::PASSWORD, self::HOST);
//        return self::$conn;

    }
}
