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

    public function checkLogin()
    {
        $auth = new GalleryAuthentification();
        $auth->loginUser($this->request->post['user'], $this->request->post['password'], null);
    }
}
