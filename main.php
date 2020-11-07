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
$router->setDefaultRoute('/home/');
$router->addRoute('home', '/home/', '\galleryapp\control\GalleryController', 'viewHome', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_NONE);

// AFFICHE LA GALLERIE
$router->addRoute('viewGallery', '/viewGallery/', '\galleryapp\control\GalleryController', 'viewGallery', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_NONE);

// AFFICHE LE FORMULAIRE POUR CREE UNE GALLERIE
$router->addRoute('viewNewGal', '/viewNewGal/', '\galleryapp\control\GalleryController', 'viewNewGal', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_USER);

// ENVOIE LES DONNEES DU FORMULAIRE POUR LA CREATION D'UNE GALLERIE
$router->addRoute('sendNewGal', '/sendNewGal/', '\galleryapp\control\GalleryController', 'sendNewGal',  \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_USER);

// AFFICHE UNE IMAGE
$router->addRoute('viewNewImg', '/viewNewImg/', '\galleryapp\control\GalleryController', 'viewNewImg',  \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_NONE);

// AFFICHE LE FORMULAIRE POUR L'AUTHENTIFICATION (LOG OR CREATE ACCOUNT)
$router->addRoute('viewAuth', '/viewAuth/', '\galleryapp\control\GalleryController', 'viewAuth', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_NONE);

// ENVOIE LES DONNEES DU FORMULAIRE POUR SE CONNECTER
$router->addRoute('login', '/login/', '\galleryapp\control\GalleryAdminController', 'checkLogin', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_NONE);

// ENVOIE LES DONNEES DU FORMULAIRE POUR LA CREATION D'UN UTILISATEUR
$router->addRoute('addUser', '/check_signup/', '\galleryapp\control\GalleryAdminController', 'checkSignup', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_NONE);

// DECONNEXION DE L'UTILISATEUR
$router->addRoute('logout', '/logout/', '\galleryapp\control\GalleryAdminController', 'logout', \galleryapp\auth\GalleryAuthentification::ACCESS_LEVEL_USER);

/* STYLE */
galleryapp\view\GalleryView::addStyleSheet('html/css/style.css');

$router->run();

/* ========== MAIN ========== */

// $newUser = new GalleryAuthentification();

// $newUser->createUser('DE SOUZA', 'Alex', 'alexdu88rpz@gmail.com', 'coucou', 'Spaaace');
// $newUser->createUser('BEN', 'M', 'BEN@gmail.com', 'PWD', 'BM8');

// $login = new GalleryAuthentification();

// $login->loginUser('BM8', 'eee');

if (isset($_SESSION['user_login'], $_SESSION['access_level'])) {
    echo $_SESSION['user_login'];
    echo $_SESSION['access_level'];
}
