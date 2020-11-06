<?php

namespace galleryapp\control;

use galleryapp\model\Gallery;
use galleryapp\model\Image;
use galleryapp\model\User;

class GalleryController extends \mf\control\AbstractController {

    public function __construct() {
        parent::__construct();
    }

    public function viewHome() {
        $gal = Gallery::all();
        foreach ($gal as $v) {
            if ($v->access_mod === 0) {
                $img = Image::inRandomOrder()->where('id_gal', '=', $v->id)->first();
                echo "<img src=" . "../../" . $img->path . " alt=" . "../../" . $img->path . ">";
            }
        }
    }

    public function viewGallery() {
        $img = Image::select()->where('id_gal', '=', $_GET['id_gal'])->get();
        foreach ($img as $v) {
            echo "<img src=" . "../../" . $v->path . " alt=" . "../../" . $v->path . ">";
        }
    }

    public function viewNewGal() {
        $data = '';
        $vue = new \galleryapp\view\GalleryView($data);
        $vue->render('newGal');
    }

    public function sendNewGal() {
        print_r($this->request->post);
        $g = new Gallery;
        $g->name = $this->request->post['name'];
        $g->description = $this->request->post['desc'];
        $g->keyword = $this->request->post['keyword'];
        $g->access_mod = $this->request->post['access'];
        $g->save();
    }

    public function viewAuth() {
        $data = '';
        $vue = new \galleryapp\view\GalleryView($data);
        $vue->render('auth');
    }

    public function sendNewUser() {
        print_r($this->request->post);
        $u = new User;
        $u->first_name = $this->request->post['first_name'];
        $u->name = $this->request->post['name'];
        $u->mail = $this->request->post['email'];
        $u->user_name = $this->request->post['user_name'];
        $u->password = $this->request->post['password'];
        $u->save();
    }

}

