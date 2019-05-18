<?php

class View
{
    protected $file;
    protected $data;
    protected $title = "";

    /**
     * View constructor.
     * @param $view_file
     * @param $view_data
     */
    public function __construct($view_file, $view_data)
    {
        $this->file = $view_file;
        $this->data = $view_data;
        $this->title = ucfirst(dirname($view_file));
    }

    public function render()
    {
        if (file_exists(VIEW . $this->file . '.phtml')) {
            include VIEW . $this->file . '.phtml';
        }
    }

    public function getAction()
    {
        return (explode('\\',$this->file))[1];
    }
}
