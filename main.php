<?php
session_start();
/* USE */

use galleryapp\model\User;
use galleryapp\model\Gallery;
use galleryapp\model\Image;
use galleryapp\control\GalleryController;
use galleryapp\auth\GalleryAuthentification;

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

$router->addRoute('home', '/home/', '\galleryapp\control\GalleryController', 'viewHome', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('viewGallery', '/viewGallery/', '\galleryapp\control\GalleryController', 'viewGallery', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('viewNewGal', '/viewNewGal/', '\galleryapp\control\GalleryController', 'viewNewGal', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('sendNewGal', '/sendNewGal/', '\galleryapp\control\GalleryController', 'viewNewGal', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('viewAuth', '/viewAuth/', '\galleryapp\control\GalleryController', 'viewAuth', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_NONE);

$router->setDefaultRoute('/home/');

/* STYLE */
galleryapp\view\GalleryView::addStyleSheet('html/css/style.css');

$router->run();

/* ========== MAIN ========== */

$newUser = new GalleryAuthentification();

$newUser->createUser('DE SOUZA', 'Alex', 'alexdu88rpz@gmail.com', 'coucou', 'Spaaace');
$newUser->createUser('BEN', 'M', 'BEN@gmail.com', 'PWD', 'BM8');

$login = new GalleryAuthentification();

$login->loginUser('BM8', 'PWD');


/* ========== SESSION ========== */

$all_user = User::all();

foreach ($all_user as $user) {
    $_SESSION['user_login'] = $user->user_name;
    $_SESSION['access_level'] = $user->level;
    print_r($_SESSION);
}



echo "<br><br>";
print_r($router::$routes);
echo "<br><br>";
print_r($router::$aliases);
