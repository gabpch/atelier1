<?php

namespace mf\router;

class Router extends \mf\router\AbstractRouter
{

    public function addRoute($name, $url, $ctrl, $mth, $access_level)
    {
        self::$routes[$url] = [$ctrl, $mth, $access_level];
        self::$aliases[$name] = $url;
    }

    public function setDefaultRoute($url)
    {
        self::$aliases['default'] = $url;
    }

    public function run()
    {
        $path_info = $this->http_req->path_info;

        if (isset(self::$routes[$path_info])) {
            $auth = new \mf\auth\Authentification;

            if ($auth->checkAccessRight(self::$routes[$path_info][2])) {
                $ctrl_name = self::$routes[$path_info][0];
                $mth_name = self::$routes[$path_info][1];
            } else {
                $default_url = self::$aliases['default'];
                $ctrl_name = self::$routes[$default_url][0];
                $mth_name = self::$routes[$default_url][1];
            }

            $c = new $ctrl_name();
            $c->$mth_name();
        }

        // $path = $this->http_req->path_info;
        // if (array_key_exists($path, self::$routes)) {
        //     $auth = new \mf\auth\Authentification();
        //     if ($auth->checkAccessRight(self::$routes[$path][2])) {
        //         $cname = self::$routes[$path][0];
        //         $cmth = self::$routes[$path][1];
        //     }
        // } else {
        //     $cname = self::$routes[self::$aliases['default']][0];
        //     $cmth = self::$routes[self::$aliases['default']][1];
        // }
        // $c = new $cname();
        // $c->$cmth();
    }

    public function urlFor($route_name, $param_list = [])
    {
        $href = $this->http_req->script_name;
        $url = self::$aliases[$route_name];
        $href = $href . $url;
        if (count($param_list) > 0) {
            $href .= "?";
            foreach ($param_list as $value) {
                $href .= $value[0];
                $href .= "=";
                $href .= $value[1];
            }
            // a faire cas multi parametres
        }
        return $href;
    }

    public function executeRoute()
    {
    }
}
