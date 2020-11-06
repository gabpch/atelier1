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
            $ctrl_name = self::$routes[$path_info][0];
            $mth_name = self::$routes[$path_info][1];
            $c = new $ctrl_name();
            $c->$mth_name();
        } else {
            $default_url = self::$aliases['default'];
            $ctrl_name = self::$routes[$default_url][0];
            $mth_name = self::$routes[$default_url][1];
            $c = new $ctrl_name();
            $c->$mth_name();
        }
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

    public static function executeRoute($alias)
    {
        $url = self::$aliases[$alias];

        $ctrl = self::$routes[$url][0];
        $mth = self::$routes[$url][1];

        $c = new $ctrl();
        $c->$mth();
    }
}
