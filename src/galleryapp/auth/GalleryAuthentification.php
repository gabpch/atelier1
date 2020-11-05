<?php

namespace galleryapp\auth;


class GalleryAuthentification extends \mf\auth\Authentification
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createUser($username, $pass, $fullname, $level = self::ACCESS_LEVEL_USER)
    {
    }

    public function loginUser($username, $password)
    {
    }
}
