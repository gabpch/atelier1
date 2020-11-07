<?php

namespace galleryapp\control;

use galleryapp\auth\GalleryAuthentification;
use galleryapp\view\GalleryView;

class GalleryAdminController extends \mf\control\AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $vue = new GalleryView(null);
        $vue->render('auth');
    }

    public function logout()
    {
        $auth = new \mf\auth\Authentification;
        $auth->logout();
        \mf\router\Router::executeRoute('home');
    }

    public function checkLogin()
    {
        $auth = new GalleryAuthentification();
        $auth->loginUser($this->request->post['user_name'], $this->request->post['password']);
        \mf\router\Router::executeRoute('home');
    }

    public function checkSignup()
    {
        $auth = new GalleryAuthentification();
        $vue = new GalleryView(null);

        if (isset($this->request->post['name'], $this->request->post['first_name'], $this->request->post['email'], $this->request->post['password'])) {
            $auth->createUser($this->request->post['name'], $this->request->post['first_name'], $this->request->post['email'], $this->request->post['password'], $this->request->post['user_name']);
            \mf\router\Router::executeRoute('home');
        } else {
            $vue->render('auth');
        }
    }
}
