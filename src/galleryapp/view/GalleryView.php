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
        $header = "";

        // Header utilisateur connecté;
        if ($auth->logged_in) {
            $header .=  <<<EOT
                    <nav>    
                        <ul>
                            <li><a class='active' href="${urlForHome}">Media Photo</a></li>
                            <li><a  href="">Mes galleries</a></li>
                            <li><a href="${urlForLogout}">Déconnexion</a></li>     
                        </ul>
                        <form class="search" action="">
                            <input type="text" placeholder="Search images..." name="search">
                            <button type="submit"><i>OK</i></button>
                        </form>   
                    </nav>              
EOT;
        } else {
            $header .= <<<EOT
            <nav>
                <ul>
                    <li><a class='active' href="${urlForHome}">Media Photo</a></li>
                    <li><a href="${urlForAuth}">Connexion</a></li>
                </ul>
                <form class="search" action="">
                    <input type="text" placeholder="Search images..." name="search2">
                    <button type="submit"><i>OK</i></button>
            </form>
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
        $chaine = "";
        $router = new Router;

        foreach ($this->data as $key => $value) {
            // echo $value . '<br><br>';
            $chaine .=
                "<a class='img' href=\"" . $router->urlFor('viewGallery', [['id', $value->id]]) . "\" >
            <img src='/$key' alt='Image introuvable'> 
                <div class='info-gal'>
                    <p>Nom: $value->name</p>
                </div>
            </a>";
        }

        $chaine;

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

        foreach ($this->data as $key => $value) {

            $chaine .= "<div class='img'> <div class='info-gal'><p></p> <p>$value->title</p> </div> <img src='../../$value->path' alt='Image introuvable'> </div>";
        }
        $chaine;

        $result = <<< EOT
        <h1 class='ingoUti'>Nom de la galerie</h1>
        <h1 class='ingoUti'>Nom de l'auteur</h1>
         <section class='main'>
            ${chaine}
         </section>
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
        }

        $body = <<<EOT
        <header> ${header} </header>
            ${section}
        <footer> ${footer} </footer>
EOT;

        return $body;
    }
}
