<?php

namespace mf\utils;

class HttpRequest extends AbstractHttpRequest
{
    function __construct()
    {

        $this->script_name = $_SERVER['SCRIPT_NAME'];

        if (isset($_SERVER['PATH_INFO'])) {
            $this->path_info = $_SERVER['PATH_INFO'];
        }

        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->get = $_GET;
        $this->post = $_POST;
        $this->root = dirname($this->script_name);
    }
}
