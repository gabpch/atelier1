<?php

namespace galleryapp\view;

use mf\router\Router;
use galleryapp\auth\GalleryAuthentification;

class GalleryView extends \mf\view\AbstractView
{

    public function __construct($data)
    {
        parent::__construct($data);
    }

    private function renderHeader()
    {
        $auth = new GalleryAuthentification;
        $rooter = new Router;
        $urlForHome = $rooter->urlFor('home', null);
        $urlForLogout = $rooter->urlFor('logout', null);
        $urlForAuth = $rooter->urlFor('viewAuth', null);
        $urlForMesGal = $rooter->urlFor('viewMyGal', null);
        $header = "";

        // Header utilisateur connecté;
        if ($auth->logged_in) {
            $header .=  <<<EOT
                    <nav>
                        <ul>
                            <li><a class='active' href="${urlForHome}">MEDIA PHOTO</a></li>
                            <li><a  href="${urlForMesGal}">MES GALLERIES</a></li>
                            <li><a href="${urlForLogout}">Déconnexion</a></li>
                        </ul>
                    </nav>
                    
EOT;
        } else {
            $header .= <<<EOT
            <nav>
            <ul>
                <li><a class='active' href="${urlForHome}">MEDIA PHOTO</a></li>
                <li><a href="${urlForAuth}">Connexion</a></li>
            </ul>
        
        </nav>
EOT;
        }


        return $header;
    }

    private function renderFooter()
    {
        return '<h1>Media Photo 2020</h1>';
    }

    private function renderHome()
    {

        //echo $_SESSION['user_login'];
        $chaine = "";

        $router = new \mf\router\Router();

        foreach ($this->data as $key => $value) {

            if($value->access_mod != 1){

                $chaine = $chaine . "<div class='img'> <div class='Info-gal'> <p>Nom de l'auteur </p> <p>$value->name</p> </div> <a href=\"" . $router->urlFor('viewGallery', [['id', $value->id]]) . "\" ><img src='$key' alt='Image introuvable'></a> </div>";

            }else{
                if(isset($_SESSION['user_login'])){
                    $user = \galleryapp\model\User::where('user_name', '=', $_SESSION['user_login'])->first();

                    if($value->id_user == $user->id){

                        $chaine = $chaine . "<div class='img'> <div class='Info-gal'> <p>Nom de l'auteur </p> <p>$value->name</p> </div> <a href=\"" . $router->urlFor('viewGallery', [['id', $value->id]]) . "\" ><img src='$key' alt='Image introuvable'></a> </div>";

                    }

                    $consult = \galleryapp\model\Consult::where('id_gal', '=', $value->id)->get();

                    foreach ($consult as $k => $v) {

                        if($v->id_user == $user->id){

                            $chaine = $chaine . "<div class='img'> <div class='Info-gal'> <p>Nom de l'auteur </p> <p>$value->name</p> </div> <a href=\"" . $router->urlFor('viewGallery', [['id', $value->id]]) . "\" ><img src='$key' alt='Image introuvable'></a> </div>";

                        }
                        # code...
                    }
                }
            }


            
        }

        $chaine;

        $router = new \mf\router\Router();

        $urlForCon = $router->urlFor('viewAuth');

        $result = <<< EOT

         <section class='main'>


            ${chaine}

         </section>

EOT;

        return $result;
    }

    private function renderGallery()
    {
        $chaine = "";
        $btn ="";
        $consult = "";

        $nom_gal = $this->data['gallery']['name'];
        $desc_gal = $this->data['gallery']['description'];
        $keyword_gal = $this->data['gallery']['keyword'];
        $creator = $this->data['user']['user_name'];

        //penser à ajouter la date de création de la galerie

        $nb_img = count($this->data['image']);

        $router = new Router;

        foreach ($this->data['image'] as $key => $value) {

            $chaine = $chaine . "<div class='img'> <div class='Info-gal'><p></p> <p>$value->title</p> </div> <a href=\"" . $router->urlFor('viewImg', [['id', $value->id]]) . "\" ><img src='../../$value->path' alt='Image introuvable'></a> </div>";

        }

        $chaine;

        if(isset($_SESSION['user_login'])){

            if($this->data['user']['user_name'] === $_SESSION['user_login']){

                $btn .= "<div><a href=\"" . $router->urlFor('viewNewImg') . "\" >Ajouter une nouvelle image </a></div>";

                if($this->data['gallery']['access_mod'] === 1){
                    $consult = "<div><a href=\"" . $router->urlFor('viewNewImg') . "\" >Donner l'authorisation de voir votre galerie </a></div>";
                }
                
            }

        }
        

        $result = <<< EOT

        <h1 class='ingoUti'>Nom de la galerie : ${nom_gal}</h1>
        <h1 class='ingoUti'>Nom de l'auteur : ${creator}</h1>

        <p>Description : ${desc_gal}</p>

         <section class='main'>
            ${chaine}
         </section>

         ${btn}
         ${consult}

         <p>Mots clés : ${keyword_gal}</p>
         <p>nombre d'image dans la galerie : ${nb_img} images</p>

EOT;

        return $result;
    }

    private function renderImg()
    {
        $path = $this->data['path'];
        $titre = $this->data['title'];
        $create_at = $this->data['created_at'];
        $key = $this->data['keyword'];


        $chaine = "<img src='../../$path' alt='Image introuvable'>";

        $result = <<< EOT

        <h1 class='ingoUti'>titre de l'image : ${titre}</h1>
        <h1 class='ingoUti'>l'image a été ajouté le : ${create_at}</h1>

         <section class='main'>
            ${chaine}
         </section>

         <p>Mots clés : ${key}</p>
         

EOT;

        return $result;

    }

    private function renderMyGal(){

        $chaine = "";
        $router = new \mf\router\Router();
        $username = $_SESSION['user_login'];
        $btn = "<div><a href=\"" . $router->urlFor('viewNewGal') . "\" >Ajouter une Galerie </a></div>";

        foreach ($this->data as $key => $value) {

            $chaine = $chaine . "<div class='img'> <div class='Info-gal'> <p>Nom de l'auteur : $username </p> <p>Nom de la galerie : $value->name</p> </div> <a href=\"" . $router->urlFor('viewGallery', [['id', $value->id]]) . "\" ><img src='../../$key' alt='Image introuvable'></a> </div>";
        }

        $chaine;

        $router = new \mf\router\Router();

        $urlForCon = $router->urlFor('viewAuth');

        $result = <<< EOT

         <section class='main'>


            ${chaine}

         </section>

         ${btn}

EOT;

        return $result;


    }

    private function renderNewGal()
    {
        $result = <<<EOT
        <div class="form">
            <h1>Ajouter une galerie</h1>
            <form action="../sendNewGal/" method="post">
                <input type="text" name="name" placeholder="Nom de la galerie" required>
                <textarea name="desc" placeholder="Description de la galerie" required></textarea>
                <input class="keyword" type="text" name="keyword" placeholder="Mot clé" required>
                <input type="file" name="img">
                <select name="access">
                    <option value="0">Public</option>
                    <option value="1">Privé</option>
                </select>
                <button class="submit-btn" type="submit" name="submitBtn">Ajouter</button>
            </form>
        </div>
EOT;
        return $result;
    }

    private function renderNewImg()
    {
        $result = <<<EOT
        <div class="form">
            <h1>Ajouter une photo</h1>
            <form action="../sendNewImg/" method="post">
                <input type="text" name="title" placeholder="Titre de la photo" required>
                <input class="keyword" type="text" name="keyword" placeholder="Mot clé" required>
                <input type="file" name="img">
                <button class="submit-btn" type="submit">Ajouter</button>
            </form>
        </div>
EOT;
        return $result;
    }

    private function renderAuth()
    {
        $rooter = new Router();
        $urlForConnect = $rooter->urlFor('login', null);
        $urlForCreate = $rooter->urlFor('addUser', null);

        $result = <<<EOT
        <div class="forms">

            <div class="log-in">
                <h1>Se connecter</h1>
                <form action="${urlForConnect}" method="post">
                    <input type="text" id="user_name1" name="user_name" placeholder="Nom d'utilisateur" required>
                    <input type="password" id="password1" name="password" placeholder="Mot de passe" required>
                    <button class="submit-btn" type="submit">Connexion</button>
                </form>
            </div>

            <div class="bar"></div>

            <div class="sign-in">
                <h1>S'inscrire</h1>
                <form action="${urlForCreate}" method="post">
                    <input type="text" name="first_name" placeholder="Prénom" required>
                    <input type="text" name="name" placeholder="Nom" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="text" name="user_name" placeholder="Nom d'utilisateur" required>
                    <input type="password" name="password" placeholder="Mot de passe" required>
                    <button class="submit-btn" type="submit">Inscription</button>
                </form>
            </div>

        </div>
EOT;
        return $result;
    }

    protected function renderBody($selector)
    {

        $header = $this->renderHeader();
        $footer = $this->renderFooter();

        $section = '';

        switch ($selector) {
            case 'home':
                $section = $this->renderHome();
                break;
            case 'gallery':
                $section = $this->renderGallery();
                break;
            case 'newGal':
                $section = $this->renderNewGal();
                break;
            case 'newImg':
                $section = $this->renderNewImg();
                break;
            case 'auth':
                $section = $this->renderAuth();
                break;
            case 'img':
                $section = $this->renderImg();
                break;
            case 'myGallery':
                $section = $this->renderMyGal();
                break;
        }

        $body = <<<EOT
        <header> ${header} </header>
            ${section}
        <footer> ${footer} </footer>
EOT;

        return $body;
    }
}
