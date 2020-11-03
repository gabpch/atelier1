<?php
namespace mf\auth;
class Authentification extends AbstractAuthentification
{
    public function __construct()
    {
        if(isset($_SESSION['user_login']))
        {
            $this->user_login = $_SESSION['user_login'];
            $this->access_level = $_SESSION['access_level'];
            $this->logged_in = true;
        }
        else
        {
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
        unsset($_SESSION['user_login']);
        unsset($_SESSION['access_right']);
        $this->user_login = null;
        $this->access_level =  self::ACCESS_LEVEL_NONE;
        $this->logged_in = false;
    }
    public function checkAccessRight($requested)
    {
        if($requested >= $this->access_level)
            return true;
        else
            return false;
    }
    public function login($username, $db_pass, $given_pass, $level)
    {
        if(!password_verify($given_pass,$db_pass))
        {
            echo "Mot de passe invalide";
        }
        else
        {
            $this->updateSession($username,$level);
        }
    }
    protected function hashPassword($password)
    {
        $hash = password_hash($password,PASSWORD_DEFAULT);
        return $hash;
    }
    protected function verifyPassword($password, $hash)
    {
        return password_verify($password,$hash);
    }
}