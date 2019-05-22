<?php

class homeController extends Controller
{
    public function index()
    {
        $params = [];
        $this->model('Account');
        if ($this->model->checkLogin()) {
            $params['login'] = true;
            $params['username'] = $this->model->getUsername();
        } else $params['login'] = false;

        $this->view('home' . DIRECTORY_SEPARATOR . 'index', $params);
        $this->view->render();
    }

    public function contact()
    {
        if (strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
            http_response_code(400);
            return;
        }
//        $_POST['subject'];$_POST['message'];

        Application::redirectTo("/home/infoPage/Thank you!/The message was successfully sent.");
    }
}
