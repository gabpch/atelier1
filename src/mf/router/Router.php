<?php

namespace mf\router;

class Router extends AbstractRouter
{
    public function construct()
    {
        parent::__construct();
    }

    public function run()
    {
        $path = $this->http_req->path_info;
        if (array_key_exists($path, self::$routes)) {
            $auth = new \mf\auth\Authentification();
            if ($auth->checkAccessRight(self::$routes[$path][2])) {
                $cname = self::$routes[$path][0];
                $cmth = self::$routes[$path][1];
            }
            $cname = self::$routes[self::$aliases['default']][0];
            $cmth = self::$routes[self::$aliases['default']][1];
        } else {
            $cname = self::$routes[self::$aliases['default']][0];
            $cmth = self::$routes[self::$aliases['default']][1];
        }
        $c = new $cname();
        $c->$cmth();
    }
    public function urlFor($route_name, $param_list = [])
    {
        if (isset(self::$aliases[$route_name])) {
            $url_alias = self::$aliases[$route_name];
            $url = $this->http_req->script_name . $url_alias;

            if ($param_list != null) {
                $url .= "?";
                foreach ($param_list as $param)
                    $url = $url . $param[0] . "=" . $param[1];
            }
            return $url;
        }
    }
    public function setDefaultRoute($url)
    {
        self::$aliases['default'] = $url;
    }
    public function addRoute($name, $url, $ctrl, $mth, $level)
    {
        self::$routes[$url] = array($ctrl, $mth, $level);
        self::$aliases[$name] = $url;
    }
    static public function executeRoute($alias)
    {
        $rname = self::$aliases[$alias];
        $cname = self::$routes[$rname][0];
        $cmth = self::$routes[$rname][1];
        $c = new $cname();
        $c->$cmth();
    }
}
