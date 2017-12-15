<?php
namespace WF3\Controller;

use Silex\Application;
//cette ligne nous permet d'utiliser le service fourni par symfony pour gérer 
// les $_GET et $_POST
use Symfony\Component\HttpFoundation\Request;
use WF3\Domain\Article;
use WF3\Form\Type\ArticleType;
use WF3\Form\Type\ContactType;
use WF3\Domain\User;
use WF3\Form\Type\UserRegisterType;
use WF3\Form\Type\SearchEngineType;
use Symfony\Component\Validator\Constraints\DateTime;
//permet de générer des erreurs 403 (accès interdit)
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AjaxHomeController{

    public function jourCours(Application $app, $j){
        //navigation : calcul de l'écart entre le jour actuel et le jour demandé dans le planning  : est récupéré par $j
        $ecart = $j;
        //$ecart = $j - date('w');
        $datecible = new \DateTime;
        //ajoute l'écart en jour pour aller à la date cible
        $datecible->modify('+'.$ecart.' day');
        //Transforme ensuite le format pour qu'il soit compatible SQL
        $dataffich = $datecible->format('Y-m-d');
                
        $planning = $app['dao.planning']->getInfoPlanning($dataffich);
        return $app['twig']->render('jour.html.twig', array(
            'planning'=>$planning,
            //'ecart'=>$ecart
           
        ));
    }

	//page d'accueil
	public function homePageAction(Application $app){

	 	return $app['twig']->render('accueil.html.twig');
	}

}    