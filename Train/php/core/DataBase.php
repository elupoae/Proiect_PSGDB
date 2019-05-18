<?php


class Database
{
    const HOST = "jdbc:oracle:thin:@localhost:1521:XE";
    const USER = "EDUARD";
    const PASSWORD = "EDUARD";
    const DATABASE = "maxlock";
    private static $conn = null;

    public static function setConnection()
    {
        $conn = oci_connect(self::USER, self::PASSWORD, self::HOST);
        if (!$conn) {
            $m = oci_error();
            echo $m['message'], "\n";
            exit;
        }
        else {
            print "Connected to Oracle!";
        }
    }

    public static function getConnection()
    {
        return self::$conn;
    }
}
