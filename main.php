<?php

/* USE */
use galleryapp\model\User;
use galleryapp\model\Gallery;
use galleryapp\model\Image;
use galleryapp\control\GalleryController;

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

/* ROUTER */
$router = new \mf\router\Router();

$router->addRoute('home', '/home/', '\galleryapp\control\GalleryController', 'viewHome');
$router->addRoute('viewGallery', '/viewGallery/', '\galleryapp\control\GalleryController', 'viewGallery');

$router->setDefaultRoute('/home/');

$router->run();

/* ========== MAIN ========== */

var_dump($_SERVER);

$test = new GalleryController;
$test->viewHome();