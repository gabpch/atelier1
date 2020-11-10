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
        $lastGal = Gallery::select()->orderBy('id', 'DESC')->first();
        $user = User::Where('user_name', '=', $_SESSION['user_login'])->first();
        $g = new Gallery;
        $g->name = $this->request->post['name'];
        $g->description = $this->request->post['desc'];
        $g->keyword = $this->request->post['keyword'];
        $g->access_mod = $this->request->post['access'];
        $g->id_user = $user->id;
        $g->save();
        $img_Path = "src/img/";
        $i = new Image;
        $lastImg = Image::select()->latest()->first();
        $lastImg->id += 1;
        $rename = rename($_FILES['img']['tmp_name'], $img_Path . $lastImg->id . '.jpg');
        //var_dump($rename); //retourne vrai ou faux
        $i->path = str_replace("\\", "", $img_Path . $lastImg->id . '.jpg'); // <=== IMAGE A PASSER DANS FOLDER IMG ET AJOUTER PATH
        $i->title = $this->request->post['nameImg'];
        $i->keyword = $this->request->post['keywordImg'];
        $i->id_gal = $lastGal->id + 1;
        $i->save();
        \mf\router\router::executeRoute('viewMyGal');
    }

    public function viewAuth()
    {
        $vue = new \galleryapp\view\Galleryview(null);
        $vue->render('auth');
    }

    public function viewNewImg()
    {
        $user = User::where('user_name', '=', $_SESSION['user_login'])->first();
        $gal = Gallery::where('id_user', '=', $user['id'])->get();
        $vue = new \galleryapp\view\GalleryView($gal);
        $vue->render('newImg');
    }

    public function viewModifImg()
    {
        $id = $this->request->get;
        $img = Image::where('id_gal', $id['id'])->get();;
        $user = User::where('user_name', '=', $_SESSION['user_login'])->first();
        $gal = Gallery::where('id_user', '=', $user['id'])->get();
        $data = array(
            'gallery' => $gal,
            'image' => $img
        );

        $vue = new \galleryapp\view\GalleryView($data);
        $vue->render('modifImg');
    }

    public function sendModifImg()
    {

        $img = Image::where('id', '=',  $this->request->post['img'])
            ->update(

                array(
                    'title' => $this->request->post['title'],
                    'keyword' => $this->request->post['keyword'],
                    'id_gal' => $this->request->post['gallery']

                )

            );

        $rooter = new \mf\router\Router();
        $urlForHome = $rooter->urlFor('home', null);
        header("Location: $urlForHome", true, 302);
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

    public function sendNewImg()
    {
        $img_Path = "src/img/";
        $i = new Image;
        $lastImg = Image::select()->orderBy('id', 'DESC')->first();
        $lastImg->id += 1;
        $rename = rename($_FILES['img']['tmp_name'], $img_Path . $lastImg->id . '.jpg');
        //var_dump($rename); //retourne vrai ou faux
        $i->path = str_replace("\\", "", $img_Path . $lastImg->id . '.jpg'); // <=== IMAGE A PASSER DANS FOLDER IMG ET AJOUTER PATH
        $i->title = $this->request->post['title'];
        $i->keyword = $this->request->post['keyword'];
        $i->id_gal = $this->request->post['gallery'];
        $i->save();
    }

    public function viewModifGal()
    {

        $user = User::where('user_name', '=', $_SESSION['user_login'])->first();
        $gal = Gallery::where('id_user', '=', $user['id'])->get();

        $vue = new \galleryapp\view\GalleryView($gal);
        $vue->render('modifGal');
    }

    public function sendModifGal()
    {
        $gal = Gallery::where('id', '=',  $this->request->post['gallery'])
            ->update(

                array(
                    'name' => $this->request->post['name'],
                    'description' => $this->request->post['desc'],
                    'keyword' => $this->request->post['keyword'],
                    'access_mod' => $this->request->post['access']

                )

            );

        $rooter = new \mf\router\Router();
        $urlForHome = $rooter->urlFor('home', null);
        header("Location: $urlForHome", true, 302);
    }

    public function viewDelGal()
    {

        $id = $this->request->get;
        $gal = Gallery::where('id', '=', $id)->first();
        $vue = new \galleryapp\view\GalleryView($gal);
        $vue->render('delGal');
    }

    public function deleteGal()
    {

        $id = $this->request->get;
        $delImg = Image::where('id_gal', '=', $id)->delete();
        $delCons = \galleryapp\model\Consult::where('id_gal', '=', $id)->delete();
        $delGal = Gallery::where('id', '=', $id)->delete();

        $rooter = new \mf\router\Router();
        $urlForHome = $rooter->urlFor('home', null);
        header("Location: $urlForHome", true, 302);
    }

    public function viewDelImg()
    {

        $id = $this->request->get;
        $img = Image::where('id', '=', $id)->first();
        $vue = new \galleryapp\view\GalleryView($img);
        $vue->render('delImg');
    }

    public function deleteImg()
    {

        $id = $this->request->get;
        $delImg = Image::where('id', '=', $id)->delete();

        $rooter = new \mf\router\Router();
        $urlForHome = $rooter->urlFor('home', null);
        header("Location: $urlForHome", true, 302);
    }
}
