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

}

