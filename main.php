<?php

/* USE */
use galleryapp\model\User;
use galleryapp\model\Gallery;
use galleryapp\model\Image;

/* AUTOLOADER ELOQUENT */
require_once('vendor/autoload.php');


/* AUTOLOADER */
require_once('src/mf/utils/AbstractClassLoader.php');
require_once('src/mf/utils/ClassLoader.php');

$loader = new \mf\utils\ClassLoader('src');
$loader->register();

/* ACCES DB */
$config_ini = parse_ini_file("conf/config.ini");

/* INSTANCE DE CONNEXION  */
$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($config_ini); /* configuration avec nos paramètres */
$db->setAsGlobal();              /* rendre la connexion visible dans tout le projet */
$db->bootEloquent();             /* établir la connexion */


/* ========== MAIN ========== */


$usersReq = User::select()->get();

foreach ($usersReq as $user) {
    echo $user;
}