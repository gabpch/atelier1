<?php

namespace mf\utils;

class ClassLoader extends AbstractClassLoader {
    
    public function loadClass(string $classname) {
        $filename = $this->getFilename($classname);
        $path = $this->makePath($filename);
        
        if(file_exists($path)) {
            require_once($path);
        }
    }

    public function makePath(string $filename): string {
        $path = $this->prefix . DIRECTORY_SEPARATOR . $filename;
        return $path;
    }

    public function getFilename(string $classname): string {
        $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
        return $classname . '.php';
    }

}