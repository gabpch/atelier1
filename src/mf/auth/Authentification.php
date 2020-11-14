<?php

namespace mf\auth;

use Exception;
use mf\router\Router;

class Authentification extends AbstractAuthentification
{
    public function __construct()
    {
        if (isset($_SESSION['user_login'])) {
            $this->user_login = $_SESSION['user_login'];
            $this->access_level = $_SESSION['access_level'];
            $this->logged_in = true;
        } else {
            $this->user_login = null;
            $this->access_level =  self::ACCESS_LEVEL_NONE;
            $this->logged_in = null;
        }
    }
    protected function updateSession($username, $level)
    {
        $this->user_login = $username;
        $this->access_level = $level;
        $_SESSION['user_login'] = $username;
        $_SESSION['access_level'] = $level;
        $this->logged_in = true;
    }
    public function logout()
    {
        unset($_SESSION['user_login']);
        unset($_SESSION['access_right']);
        $this->user_login = null;
        $this->access_level =  self::ACCESS_LEVEL_NONE;
        $this->logged_in = false;
    }
    public function checkAccessRight($requested)
    {
        if ($requested > $this->access_level)
            return false;
        else
            return true;
    }
    public function login($username, $db_pass, $given_pass, $level)
    {
        $rooter = new Router();
        $urlForAuth = $rooter->urlFor('viewAuth', null);
        $urlForHome = $rooter->urlFor('home', null);

        if (!password_verify($given_pass, $db_pass)) {
            header("Location: $urlForAuth", true, 302);  //redirige l'utilisateur sur le formulaire si le login ne marche pas
        } else {
            $this->updateSession($username, $level);
            header("Location: $urlForHome", true, 302); // redirige l'utilisateur sur le home lorsque la connexion s'est bien effectué
        }
    }
    protected function hashPassword($password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;
    }
    protected function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
