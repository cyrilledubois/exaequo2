<?php
namespace WF3\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\ArticleType;
use WF3\Domain\Article;
use WF3\Domain\Planning;
use WF3\Form\Type\UserType;
use WF3\Domain\User;
use WF3\Domain\Cours;
use WF3\Domain\PlanningModel;
use WF3\Form\Type\PlanningType;
use Symfony\Component\Validator\Constraints\DateTime;

class AdminController   {
    
    //page d'accueil du back office
    public function indexAction(Application $app){  
        $datecible = new \DateTime;
        $dataffich = $datecible->format('Y-m-d');
        $planning = $app['dao.planning']->getInfoPlanning($dataffich);
        $reserv = $app['dao.user']->getInfoReserv($dataffich);
        $users = $app['dao.user']->getAlluser();
       // PAGINATION COMMENTER  $nombreDePages = $app['dao.user']->paginationUser();
       // $nombreDePages = ceil($nombreArticles/$nombreParPage);
       // if(isset($_GET['numero']) AND is_numeric($_GET['numero']) AND $_GET['numero'] <= $nombreDePages AND $_GET['numero'] > 0){
        //    $page = $_GET['numero'];
        
       // else{
         //   $page = 1;
       // }
        //$offset = ($page - 1) * $nombreParPage;
        return $app['twig']->render('admin/index.admin.html.twig', array(
                                        'planning'=>$planning,
                                        'users'=>$users,
                                        'reserv'=>$reserv,
                                        'dataffich' => $dataffich
                                        //'page' => $page,
                                       // 'offset' => $offset,
                                        //'nombre' => $nombre,
                                        //'nombrearticles' => $nombreArticles,
                                        //'nombredepage' => $nombreDePages
                                    ));
    }

    //suppression de cours
    //page de suppression de cours
    public function deleteCoursAction(Application $app, $id){
        $cours = $app['dao.cours']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Cours bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('homeAdmin'));
    }


 	// Update planning 
     public function updatePlanning(Application $app, Request $request, $id){
		//on récupère les infos de la periode 
                ////$period = $app['dao.planning']->findAll($id);
        $period = $app['dao.planning']->selectPeriod(date('Y-m-d'), $id);
        $cours = $app['dao.cours']->find($id);
        $period->setCoursid($cours->getNom());
            var_dump($period);  
            
		//on crée le planning et on lui passe la periode en paramètre
        //il va utiliser $planning pour pré remplir les champs
        $planningForm = $app['form.factory']->create(PlanningType::class, $period);	
        	
        $planningForm->handleRequest($request);
		if($planningForm->isSubmitted() && $planningForm->isValid()){
            //si le formulaire a été soumis
            //on update avec les données envoyées par l'utilisateur
           //// $app['dao.planning']->update($id, $period);
           $app['dao.planning']->update($period->getId(),$period);
           
        }
	
        return $app['twig']->render('admin/update.planning.html.twig', array(
                'planningForm' => $planningForm->createView(),

        ));
    }



    public function addCoursAction(Application $app, Request $request){
        $cours = new Cours();

        $coursForm = $app['form.factory']->create(CoursType::class, $cours);

        $coursForm->handleRequest($request);

        if($coursForm->isSubmitted() AND $coursForm->isValid()){
            $app['dao.cours']->insert(array(
                'title'=>$cours->getTitle(),
                'content'=>$cours->getContent()
            ));
        }

        return $app['twig']->render('admin/index.admin.html.twig', array(
                'coursForm' => $coursForm->createView(),
                'title' => 'ajout'
        ));
    }
    
    /**
     * Admin user add controller.
     *
     * @param Application $app Silex application
     * @param Request $request the http request
     */
    public function addUserAction(Application $app, Request $request){	
        $user = new User();
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // on génère un salt
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            //on récupère le mot de passe en clair (envoyé par l'utilisateur) 
            $plainPassword = $user->getPassword();
            // on récupère l'encoder de silex
            $encoder = $app['security.encoder.bcrypt'];
            // on encode le mdp
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            //on remplace le mdp en clair par le mdp crypté
            $user->setPassword($password);
            $app['dao.user']->insert($user);
            $app['session']->getFlashBag()->add('success', 'The user was successfully created.');
            // Redirect to admin home page
            return $app->redirect($app['url_generator']->generate('homeAdmin'));
        }
        return $app['twig']->render('admin/admin.ajout.user.html.twig', array(
            'title' => 'Add user',
            'userForm' => $userForm->createView(),
            'user' => $user
        ));
    }
    
    //suppression d'article
    //page de suppression d'article
    public function deleteUserAction(Application $app, $id){
        //on va supprimer les articles écrits par l'utilisateur
        $nbArticlesSupprimes = $app['dao.article']->deleteAllArticlesFromUser($id);
        $article = $app['dao.user']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Utilisateur bien supprimé, ses ' . $nbArticlesSupprimes . ' article(s) pourri(s) aussi supprimés');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('homeAdmin'));
    }

    //modification d'article
    public function updateUserAction(Application $app, Request $request, $id){
        $user = $app['dao.user']->find($id);
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // on génère un salt
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            //on récupère le mot de passe en clair (envoyé par l'utilisateur) 
            $plainPassword = $user->getPassword();
            // on récupère l'encoder de silex
            $encoder = $app['security.encoder.bcrypt'];
            // on encode le mdp
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            //on remplace le mdp en clair par le mdp crypté
            $user->setPassword($password);
            $app['dao.user']->update($id, $user);
            $app['session']->getFlashBag()->add('success', 'The user was successfully updated.');
            // Redirect to admin home page
            return $app->redirect($app['url_generator']->generate('homeAdmin'));
        }
        return $app['twig']->render('admin/admin.ajout.user.html.twig', array(
            'title' => 'update user',
            'userForm' => $userForm->createView(),
            'user' => $user
        ));

    }
    //Génération du planning sur un mois 
    public function generationPlanning (Application $app){
        //trouve la dernière date générée dans le planning et la retourne sous forme de tableau à une entrée
        $datedeb = $app['dao.planning']->lastDate();
        //Génère un objet DateTime contenant la date la plus lointaine générée
        $datedebgeneration = new \DateTime($datedeb['MAX(date_cours)']);
        //Ajoute 1 pour obtenir la nouvelle date, début de la période de génération
        $datedebgeneration = $datedebgeneration->modify('+ 1 day');
        //Cherche le numéro du jour de la semaine de cette date 
        $joursemaine = date_format($datedebgeneration, 'w');
        /*if($joursemaine == '0'){
        $joursemaine = '1';             
        }*/

        //Chagement des données de la table planning_type dans un tableau de tableaux
        $planning_type = $app['dao.planningtype']->findAll();
        
        foreach($planning_type as $cle){
            //boucle de changement du tableau $planning_Type pour créer la date attendue dans la table Planning
            for($i = $joursemaine ; $i <= 6 ; $i++){
                foreach($cle as $jour) 
                $jour['jour'] = date_format($datedebgeneration, 'Y-m-d'). ' ' . $jour['heure'];        
            } 
            
        }
        


        return $app['twig']->render('planninggenere.html.twig', array(
            'datedeb' => $datedeb,
            'dategen' => $datedebgeneration,
            'joursemaine' => $joursemaine,
            //'dateinsert' => $dateinsert,
            'planning_type' => $planning_type
        ));        
    // En attente de modification ....    
    }
}