<?php

namespace galleryapp\control;

use galleryapp\model\Gallery;
use galleryapp\model\Image;
use galleryapp\model\User;
use galleryapp\model\Consult;
use mf\router\Router;

class GalleryController extends \mf\control\AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function viewHome()
    {
        $gal = Gallery::select()->get();
        $galImg = array();
        foreach ($gal as $v) {
            $Img = $v->Images()->inRandomOrder()->first();
            $galImg[$Img->path] = $v;
        }
        $vue = new \galleryapp\view\Galleryview($galImg);
        $vue->render('home');
    }

    public function viewGallery() //récupère les données pour la vue renderGallery
    {
        $id = $this->request->get;
        $gal = Gallery::where('id', '=', $id)->first();
        $user = User::Where('id', '=', $gal->id_user)->first();
        $imgs = $gal->Images()->get();

        $data = array(
            'gallery' => $gal,
            'user' => $user,
            'image' => $imgs
        );
        $vue = new \galleryapp\view\GalleryView($data);
        $vue->render('gallery');
    }

    public function viewNewGal()
    {
        $vue = new \galleryapp\view\Galleryview(null);
        $vue->render('newGal');
    }

    public function sendNewGal()
    {
        print_r($this->request->post);
        $g = new Gallery;
        $g->name = $this->request->post['name'];
        $g->description = $this->request->post['desc'];
        $g->keyword = $this->request->post['keyword'];
        $g->access_mod = $this->request->post['access'];
        $g->save();
    }

    public function viewAuth()
    {
        $vue = new \galleryapp\view\Galleryview(null);
        $vue->render('auth');
    }

    public function viewNewImg()
    {
        $data = '';
        $vue = new \galleryapp\view\GalleryView($data);
        $vue->render('newImg');
    }

    public function sendNewImg()
    {
        print_r($this->request->post);
        $i = new Image;
        $i->title = $this->request->post['title'];
        $i->keyword = $this->request->post['keyword'];
        $i->path = 'un_path'; // <=== IMAGE A PASSER DANS FOLDER IMG ET AJOUTER PATH
        $i->id_gal = '1'; // AJOUTER L'ID DE LA GALLERIE A L'IMAGE
        $i->save();
    }

    public function viewImg()
    {

        $id = $this->request->get;
        $img = Image::where('id', '=', $id)->first();

        $vue = new \galleryapp\view\Galleryview($img);
        $vue->render('img');
    }

    public function viewMyGallery()
    {

        if (isset($_SESSION['user_login'])) {

            $user = User::where('user_name', '=', $_SESSION['user_login'])->first();
            $gal = Gallery::where('id_user', '=', $user['id'])->get();

            $galImg = array();
            foreach ($gal as $v) {
                $Img = $v->Images()->inRandomOrder()->first();
                $galImg[$Img->path] = $v;
            }

            $vue = new \galleryapp\view\GalleryView($galImg);
            $vue->render('myGallery');
        }
    }

    public function viewNewCons()
    {
        $gallery =  Gallery::where('id', '=', $this->request->get['id'])->first();
        $vue = new \galleryapp\view\GalleryView($gallery->id); //envoi l'id de l'utilisateur dans $this->data
        $vue->render('newCons');
    }

    public function sendNewCons()
    {
        $rooter = new Router();

        $user_name = $this->request->post['user_name']; // récupère le pseudo entré dans le formulaire
        $user = User::where('user_name', '=', $user_name)->first(); // requête qui récupère l'utilisateur correspondant au user_name dans la bdd
        $id_gal =  Gallery::where('id', '=', $this->request->get['id'])->first(); // requête qui récupère la galerie correspondant à l'id du GET dans la bdd

        $consult = new Consult;
        $consult->id_gal = $id_gal->id;
        $consult->id_user = $user->id;
        $consult->save(); // enregistre dans la bdd l'id de la galerie et de l'user

        // Redirection sur la page Mes galeries
        $urlForMyGal = $rooter->urlFor('viewMyGal', null);
        header("Location: $urlForMyGal", true, 302);
    }
}
