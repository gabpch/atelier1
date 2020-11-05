<?php

namespace galleryapp\view;

class GalleryView extends \mf\view\AbstractView {

    public function __construct($data) {
        parent::__construct($data);
    }

    private function renderHeader() {
        return '<h1>Media Photo</h1>';
    }

    private function renderFooter() {
        return '<h1>Media Photo 2020</h1>';
    }

    private function renderHome() {

    }

    private function renderGallery() {

    }

    private function renderNewGal() {

    }

    private function renderNewImg() {

    }

    private function renderAuth() {

    }

    private function renderBody() {

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

    }

}