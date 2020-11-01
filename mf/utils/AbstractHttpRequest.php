<?php

namespace mf\utils;

abstract class AbstractHttpRequest {

    protected $script_name=null, $path_info=null, $root=null;
    protected $method=null, $get=null, $post=null;
        
    public function __get($attr_name) {
        if (property_exists( $this, $attr_name)) 
            return $this->$attr_name;
        $emess = __CLASS__ . ": unknown member $attr_name (__get)";
        throw new \Exception($emess);
    }
    
    public function __set($attr_name, $attr_val) {
        if (property_exists( $this, $attr_name)) 
            $this->$attr_name=$attr_val; 
        else{
            $emess = __CLASS__ . ": unknown member $attr_name (__set)";
            throw new \Exception($emess);
        }
    }
}