<?php

namespace galleryapp\auth;

use galleryapp\model\User;

class GalleryAuthentification extends \mf\auth\Authentification
{
    public function __construct()
    {
        parent::__construct();
    }

    const ACCESS_LEVEL_VISITOR  = 0;
    const ACCESS_LEVEL_USER = 1;

    public function createUser($username, $pass, $fullname, $level = self::ACCESS_LEVEL_USER)
    {
    }

    public function loginUser($username, $password)
    {
    }
}
