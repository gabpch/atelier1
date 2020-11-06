<?php

namespace galleryapp\model;

class Image extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'image';  /* le nom de la table */
    protected $primaryKey = 'id';     /* le nom de la clé primaire */
    public $timestamps = true;    /* si vrai la table doit contenir les deux colonnes updated_at, created_at */

    public function Gallery()
    {
        return $this->belongsTo('galleryapp\model\Gallery','id_gal');
    }
}