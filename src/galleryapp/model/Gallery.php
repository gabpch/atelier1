<?php

namespace galleryapp\model;

class Gallery extends \Illuminate\Database\Eloquent\Model {

    protected $table      = 'gallery';  /* le nom de la table */
    protected $primaryKey = 'id';     /* le nom de la clé primaire */
    public    $timestamps = false;    /* si vrai la table doit contenir les deux colonnes updated_at, created_at */

    public function Images()
    {
        return $this->hasMany('galleryapp\model\Image','id_gal');
    }

    public function User()
    {
        return $this->belongsTo('galleryapp\model\user','id');
    }
}