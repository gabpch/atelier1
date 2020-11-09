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
        $user_login = "";
        if (isset($_SESSION['user_login'])) {

            $user_login = $_SESSION['user_login'];
        }

        $header = "";

        // Header utilisateur connecté;
        if ($auth->logged_in) {
            $header .=  <<<EOT
                    <nav>    
                        <ul>
                            <li><a class='active' href="${urlForHome}">MEDIA PHOTO</a></li>
                            <li><a  href="${urlForMesGal}">MES GALERIES</a></li>
                            <li><a href="${urlForLogout}">${user_login}  Déconnexion</a></li>
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

    private function renderHome() // affiche les galeries avec une photo random
    {
        $chaine = "";
        $router = new Router;
        $app_root = (new \mf\utils\HttpRequest())->root;

        foreach ($this->data as $key => $value) {

            if ($value->access_mod != 1) {
                $chaine .=
                    "<a class='img' href=\"" . $router->urlFor('viewGallery', [['id', $value->id]]) . "\" >
            <img src='$app_root/$key' alt='Image introuvable'> 
                <div class='info-gal'>
                    <p>Nom: $value->name</p>
                </div>
            </a>";
            } else {
                if (isset($_SESSION['user_login'])) { //vérifie si un utilisateur est connecté
                    $user = \galleryapp\model\User::where('user_name', '=', $_SESSION['user_login'])->first();

                    if ($value->id_user == $user->id) { // si id de la personne co est = à l'idée de la galerie ça veut dire que c'est sa galerie et qu'il faut l'afficher (meme si elle est privée)



                        $chaine .=
                            "<a class='img' href=\"" . $router->urlFor('viewGallery', [['id', $value->id]]) . "\" >
                                            <img src='$app_root/$key' alt='Image introuvable'> 
                                                <div class='info-gal'>
                                                    <p>Nom: $value->name</p>
                                                </div>
                                            </a>";
                    }

                    $consult = \galleryapp\model\Consult::where('id_gal', '=', $value->id)->get();

                    foreach ($consult as $k => $v) { // parcour dans la table consult les autorisations qui corespond à la galerie

                        if ($v->id_user == $user->id) { // si l'utilisateur connecté à l'autorisation de voir une galerie privée, affiche cette galereie



                            $chaine .=
                                "<a class='img' href=\"" . $router->urlFor('viewGallery', [['id', $value->id]]) . "\" >
                                    <img src='$key' alt='Image introuvable'> 
                                        <div class='info-gal'>
                                            <p>Nom: $value->name</p>
                                        </div>
                                    </a>";
                        }
                    }
                }
            }
        }
        $result = <<< EOT
         <section class='main'>
            ${chaine}
         </section>

EOT;
        return $result;
    }

    private function renderGallery() // affiche une galerie quand on click sur sa photo
    {
        $chaine = "";
        $btn = "";
        $consult = "";

        $nom_gal = $this->data['gallery']['name'];
        $desc_gal = $this->data['gallery']['description'];
        $keyword_gal = $this->data['gallery']['keyword'];
        $creator = $this->data['user']['user_name'];

        //penser à ajouter la date de création de la galerie

        $nb_img = count($this->data['image']); // récupère le nombre d'image de la galerie

        $router = new Router;

        foreach ($this->data['image'] as $key => $value) { // affiche les images de la galerie

            $chaine = $chaine . "<div class='img'> <div class='Info-gal'><p></p> <p>$value->title</p> </div> <a href=\"" . $router->urlFor('viewImg', [['id', $value->id]]) . "\" ><img src='../../$value->path' alt='Image introuvable'></a> </div>";
        }
        $chaine;

        if (isset($_SESSION['user_login'])) { // vérifie si une personne est connecté

            if ($this->data['user']['user_name'] === $_SESSION['user_login']) { // si le nom du créateur de la galerie est = à celui de la personne connecté, la galerie affiché lui appartient. donc rajouter 2 btn

                $btn .= "<div><a href=\"" . $router->urlFor('viewModifImg', [['id', $this->data['gallery']['id']]]) . "\" >Modifier une image </a></div>";

                if ($this->data['gallery']['access_mod'] === 1) { // si la galerie est privé alors rajouter un btn pour pouvoir donner des autorisations à d'autre user 
                    $consult = "<div><a href=\"" . $router->urlFor('viewNewCons', [['id', $this->data['gallery']['id']]]) . "\" >Donner l'authorisation de voir votre galerie </a></div>";
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

    private function renderMyGal() // affiche les galeries de la personne connecté quand il click sur le btn 'mes galeries'
    {

        $chaine = "";
        $router = new \mf\router\Router();
        $username = $_SESSION['user_login'];
        $btnAddGal = "<div><a href=\"" . $router->urlFor('viewNewGal') . "\" >Ajouter une Galerie </a></div>";
        $btnAddImg = "";


        if (count($this->data) != 0) {

            $btnAddImg = "<div><a href=\"" . $router->urlFor('viewNewImg') . "\" >Ajouter une nouvelle image </a></div>";
        }

        foreach ($this->data as $key => $value) {

            $chaine = $chaine . "<div class='img'> <div class='Info-gal'> <p>Nom de l'auteur : $username </p> <p>Nom de la galerie : $value->name</p> </div> <a href=\"" . $router->urlFor('viewGallery', [['id', $value->id]]) . "\" ><img src='../../$key' alt='Image introuvable'></a> </div>";
        }

        $chaine;

        $router = new \mf\router\Router();


        $result = <<< EOT

         <section class='main'>


            ${chaine}

         </section>

         ${btnAddGal}
         ${btnAddImg}

EOT;

        return $result;
    }

    private function renderNewGal()
    {
        $result = <<<EOT
        <div class="form">
            <h1>Ajouter une galerie</h1>
            <form action="../sendNewGal/" method="post" form enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Nom de la galerie" required>
                <textarea name="desc" placeholder="Description de la galerie" required></textarea>
                <input class="keyword" type="text" name="keyword" placeholder="Mot clé" required>
                <select name="access">
                    <option value="0">Public</option>
                    <option value="1">Privé</option>
                </select>
                <input type="file" name="img">
                <input type="text" name="nameImg" placeholder="Titre de l'image" required>
                <input class="keyword" type="text" name="keywordImg" placeholder="Mot clé de l'image" required>
                <button class="submit-btn" type="submit">Ajouter</button>
            </form>
        </div>
EOT;
        return $result;
    }

    private function renderNewCons() //affiche le formulaire qui permet de donner l'autorisation à un user de voir notre galerie privée
    {
        $rooter = new Router();
        $urlForAuthorizeUser = $rooter->urlFor('sendNewCons', [['id', $this->data]]);

        $result = <<<EOT
        <div class="form">
            <h1>Ajouter une autorisation</h1>
            <form action="${urlForAuthorizeUser}" method="post">
                <input type="text" name="user_name" placeholder="Pseudo de l'utilisateur" required>
                <button class="submit-btn" type="submit" name="submitBtn">Ajouter</button>
            </form>
        </div>
EOT;
        return $result;
    }

    private function renderNewImg()
    {
        $cbxGal = "";
        foreach ($this->data as $key => $value) {

            $cbxGal .= "<option value ='$value->id'>$value->name</option>";
            # code...
        }
        $result = <<<EOT
        <div class="form">
            <h1>Ajouter une photo</h1>
            <form action="../sendNewImg/" method="post" form enctype="multipart/form-data">
                ajouter la photo à la galerie :
                <select name="gallery">
                    ${cbxGal}
                </select>
                <input type="text" name="title" placeholder="Titre de la photo" required>
                <input class="keyword" type="text" name="keyword" placeholder="Mot clé" required>
                <input type="file" name="img">
                <button class="submit-btn" type="submit">Ajouter</button>
            </form>
        </div>
EOT;
        return $result;
    }

    private function renderModifImg()
    {
        $cbxGal = "";
        $cbxImg = "";


        foreach ($this->data['image'] as $key => $value) {

            $cbxImg .= "<option value ='$value->id'>$value->title</option>";
            # code...
        }
        foreach ($this->data['gallery'] as $key => $value) {

            $cbxGal .= "<option value ='$value->id'>$value->name</option>";
            # code...
        }


        $result = <<<EOT
        <div class="form">
            <h1>Modifier une image</h1>
            <form action="../sendModifImg/" method="post" form enctype="multipart/form-data">
                Choisir la photo à modifier :
                <select name="img">
                    ${cbxImg}
                </select>
                </br>
                changer de galerie la photo :
                <select name="gallery">
                    ${cbxGal}
                </select>
                <input type="text" name="title" placeholder="Titre de la photo" required>
                <input class="keyword" type="text" name="keyword" placeholder="Mot clé" required>
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
            case 'modifImg':
                $section = $this->renderModifImg();
                break;
            case 'newCons':
                $section = $this->renderNewCons();
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
