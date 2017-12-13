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

    public function coursJour(Application $app, $jour){
        //navigation : calcul de l'écart entre le jour actuel et le jour demandé
        $ecart = $jour - date('w');
        $calcul = date("d")+$ecart;

        $dataffich = mktime(0, 0, 0, date("m")  , date("d") , date("Y"));
        //$dataffich = '2017-12-11';   
                
        $planning = $app['dao.planning']->getInfoPlanning($dataffich);
        return $app['twig']->render('reservation.html.twig', array(
            'planning'=>$planning,
           
            //'ecart'=>$ecart,
            //'calcul'=>$datecalculee

            
        ));
    }

	//page d'accueil
	public function homePageAction(Application $app){

	 	return $app['twig']->render('accueil.html.twig');
	}

}    