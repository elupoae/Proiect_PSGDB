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
        var_dump($_POST);
//        $_POST['subject'];$_POST['message'];
    }
    public function add_carload()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }
        var_dump($_POST);
    }
}