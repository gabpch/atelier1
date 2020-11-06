<?php

namespace galleryapp\control;

use galleryapp\model\Gallery;
use galleryapp\model\Image;
use galleryapp\model\User;

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
        print_r($galImg);
        echo count($galImg);
        $vue = new \galleryapp\view\Galleryview($galImg);
        $vue->render('home');
    }

    public function viewGallery()
    {
        $id = $this->request->get;
        echo $gal = Gallery::where('id', '=', $id)->first();
        $imgs = $gal->Images()->get();
        echo "<br>" . $imgs;
        $vue = new \galleryapp\view\GalleryView($imgs);
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
        // $g->path = $this->request->post['img'];
        $g->save();
    }

    public function viewAuth()
    {
        $vue = new \galleryapp\view\Galleryview(null);
        $vue->render('auth');
    }

    public function viewNewImg()
    {
        $vue = new \galleryapp\view\Galleryview(null);
        $vue->render('newImg');
    }
}
