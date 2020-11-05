<?php

namespace mf\router;

abstract class AbstractRouter {

    /*   Une instance de HttpRequest */
    
    protected $http_req = null;
    
    /*
     * Attribut statique qui stocke les routes possibles de l'application 
     * 
     * - Une route est représentée par un tableau :
     *       [ le controlleur, la methode, niveau requis ]
     * 
     * - Chaque route est stokèe dans le tableau sous la clé qui est son URL 
     * 
     */
    
    static public $routes = array ();

    /* 
     * Attribut statique qui stocke les alias des routes
     *
     * - Chaque URL est stocké dans une case ou la clé est son alias 
     *
     */

    static public $aliases = array ();
    
    /*
     * Un constructeur 
     * 
     *  - initialiser l'attribut httpRequest
     * 
     */ 

    public function __construct(){
        $this->http_req = new \mf\utils\HttpRequest();
    }
    
    /*
     * Méthode run : execute une route en fonction de la requête 
     *    (la requête est récupérée dans l'atribut $http_req)
     *
     * Algorithme :
     * 
     * - l'URL de la route est stockée dans l'attribut $path_info de 
     *         $http_request
     *   Et si une route existe dans le tableau $route sous le nom $path_info
     *     - créer une instance du controleur de la route
     *     - exécuter la méthode de la route 
     * - Sinon 
     *     - exécuter la route par défaut : 
     *        - créer une instance du controleur de la route par défault
     *        - exécuter la méthode de la route par défault
     * 
     */
    
    abstract public function run();

    /*
     * Méthode urlFor : retourne l'URL d'une route depuis son alias 
     * 
     * Paramètres :
     * 
     * - $route_name (String) : alias de la route
     * - $param_list (Array) optionnel : la liste des paramètres si l'URL prend
     *          de paramètre GET. Chaque paramètre est représenté sous la forme
     *          d'un tableau avec 2 entrées : le nom du paramètre et sa valeur  
     *
     * Algorthme:
     *  
     * - Depuis le nom du scripte et l'URL stocké dans self::$routes construire 
     *   l'URL complète 
     * - Si $param_list n'est pas vide 
     *      - Ajouter les paramètres GET a l'URL complète    
     * - retourner l'URL
     *
     */
    
    abstract public function urlFor($route_name, $param_list=[]);

    /*
     * Méthode setDefaultRoute : fixe la route par défault
     * 
     * Paramètres :
     * 
     * - $url (String) : l'URL de la route par default
     *
     * Algorthme:
     *  
     * - ajoute $url au tableau self::$aliases sous la clé 'default'
     *
     */

    abstract public function setDefaultRoute($url);
   
    /* 
     * Méthode addRoute : ajoute une route a la liste des routes 
     *
     * Paramètres :
     * 
     * - $name (String) : un nom pour la route
     * - $url (String)  : l'url de la route
     * - $ctrl (String) : le nom de la classe du Contrôleur 
     * - $mth (String)  : le nom de la méthode qui réalise la fonctionalité 
     *                     de la route
     *
     *
     * Algorithme :
     *
     * - Ajouter le tablau [ $ctrl, $mth ] au tableau self::$route 
     *   sous la clé $url
     * - Ajouter la chaîne $url au tableau self::$aliases sous la clé $name
     *
     */

    abstract public function addRoute($name, $url, $ctrl, $mth,$level);

}
