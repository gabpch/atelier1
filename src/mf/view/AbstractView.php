<?php

namespace mf\view;

abstract class AbstractView {

    static protected $style_sheets = []; /* un tableau de fichiers style */
    static protected $app_title    = "MF app Title"; /* un titre de document */
    
    protected $data        = null; /* les données nécessaires */
    
    /* Constructeur 
     * 
     * Paramètres :  
     *
     * - $data (mixed) : selon la vue, une instance d'un modèle ou un tableau 
     *                   d'instances d'un modèle
     *  Algorithme 
     *  
     * - Stocker les données passées en paramètre dans l'attribut $data.
     *   
     *
     */

    public function __construct( $data ){
        $this->data = $data;
    }
    
    /* Méthode addStyleSheet
     * 
     * Permet d'ajouter une feuille de style à la liste:
     * 
     * Paramètres : 
     *
     * - $path_to_css_files (String) : le chemin vers le fichier 
     *                                 (relatif au script principal)
     *
     *
     */

    static public function addStyleSheet($path_to_css_files){
        self::$style_sheets[] = $path_to_css_files;
    }

    /* Méthode setAppTitle 
     * 
     * Permet de stocker un nom pour l'application (affiché sur le navigateur)
     * c'est le le titre du document HTML 
     *     
     * Paramètres : 
     *
     * - $title (String) : le titre du document HTML
     * 
     */
    
    static public function setAppTitle($title){
        self::$app_title = $title;
    }

    /* Méthode renderBody 
     * 
     * Retourne le contenu HTML de la 
     * balise body autrement dit le contenu du document. 
     *
     * Elle prend un sélecteur en paramètre dont la 
     * valeur indique quelle vue il faut générer.
     * 
     * Note : cette méthode est a définir dans les classes concrètes des vues, 
     * elle est appelée depuis la méthode render ci-dessous.
     * 
     * Paramètre : 
     * 
     * $selector (String) : une chaîne qui permet de savoir quelle vue générer
     * 
     * Retourne : 
     *
     * - (String) : le contenu HTML complet entre les balises <body> </body> 
     *
     */
    
    abstract protected function renderBody($selector);
    
    /* Méthodes render
     * 
     * cette méthode génère le code HTML d'une page complète depuis le <doctype 
     * jusqu'au </html>. 
     * 
     * Elle dèfinit les entêtes HTML, le titre de la page et lie les feuilles 
     * de style. Le contenu du document est le résultat des méthodes 
     * renderBody des sous-classes. 
     *
     * Elle utilise la syntaxe HEREDOC pour définir un patron et
     * écrire la chaine de caractère de la page entière. Voir la
     * documentation ici:
     *
     * http://php.net/manual/fr/language.types.string.php#language.types.string.syntax.heredoc
     *
     */
    
    public function render($selector){
        /* le titre du document */
        $title = self::$app_title;

        /* les feuilles de style */
        $app_root = (new \mf\utils\HttpRequest())->root;
        $styles = '';
        foreach ( self::$style_sheets as $file )
            $styles .= '<link rel="stylesheet" href="'.$app_root.'/'.$file.'"> ';

        /* on appele la methode renderBody de la sous classe */
        $body = $this->renderBody($selector);
        

        /* construire la structure de la page 
         * 
         *  Noter l'utilisation des variables ${title} ${style} et ${body}
         * 
         */
                
        $html = <<<EOT
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>${title}</title>
	    ${styles}
    </head>

    <body>
        
       ${body}

    </body>
</html>
EOT;

        /* Affichage de la page 
         *
         * C'est la seule instruction echo dans toute l'application 
         */
        
        echo $html;
    }
    
}
