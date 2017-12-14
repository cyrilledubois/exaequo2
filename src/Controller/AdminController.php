<?php
namespace WF3\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WF3\Domain\Planning;
use WF3\Domain\PlanningModel;
use WF3\Form\Type\UserType;
use WF3\Domain\User;
use WF3\Domain\Cours;
use Symfony\Component\Validator\Constraints\DateTime;

class AdminController   {
    

    

// MODIF 06/12 14h
    //page d'accueil du back office
    public function indexAction(Application $app){   
        $planning = $app['dao.planning']->getInfoPlanning();
        $reserv = $app['dao.user']->getInfoReserv($dataffich);
        $users = $app['dao.user']->getAlluser($dataffich);
        return $app['twig']->render('admin/index.admin.html.twig', array(
                                        'planning'=>$planning,
                                        'users'=>$users,
                                        'reserv'=>$reserv
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

 	// Update periode du planning 
     public function updatePeriodPlanning(Application $app, Request $request, $id){
		//on récupère les infos de la periode 
				$period = $app['dao.planning']->find($id);
				
		//on crée le planning et on lui passe la periode en paramètre
        //il va utiliser $planning pour pré remplir les champs
		$planning = $app['form.factory']->create(PlanningType::class, $period);		
		
		$planning->handleRequest($request);

		if($planning->isSubmitted() && $planning->isValid()){
            //si le formulaire a été soumis
            //on update avec les données envoyées par l'utilisateur
            $app['dao.planning']->update($id, array(
                'cours'=>$planning->getCours(),
                'duree_cours'=>$planning->getContent(),
                'author'=>$planning->getAuthor()->getId()
            ));
        }
	
        return $app['twig']->render('admin/index.admin.html.twig', array(
                'planningForm' => $planningForm->createView(),
                'title' => 'modif'
        ));

    }

    public function addCoursAction(Application $app, Request $request){
        $cours = new Cours();

        $coursForm = $app['form.factory']->create(CoursType::class, $cours);

        $coursForm->handleRequest($request);

        if($coursForm->isSubmitted() AND $coursForm->isValid()){
            $app['dao.cours']->insert(array(
                'title'=>$cours->getTitle(),
                'content'=>$cours->getContent(),
                'author'=>$app['user']->getId()
            ));
        }

        return $app['twig']->render('admin/admin.ajout.cours.html.twig', array(
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
    //Génération du planning sur un mois, tests dans la route web/planninggenere 
    public function generationPlanning (Application $app){
        //trouve la dernière date générée dans le planning et la retourne sous forme de tableau à une entrée
        //nombre de semaines à générer 
        $nb = 10;
        for($k=1;$k<=$nb;$k++){

        $datedeb = $app['dao.planning']->lastDate();
        //Génère un objet DateTime contenant la date la plus lointaine générée
        $datedebgeneration = new \DateTime($datedeb['MAX(datecours)']);
        //Ajoute 1 pour obtenir la nouvelle date, début de la période de génération ()
        $datedebgeneration = $datedebgeneration->modify('+ 1 day');
        //Cherche le numéro du jour de la semaine de cette date 
        $joursemaine = date_format($datedebgeneration, 'w');
        //Gestion du cas du dimanche, on décale tout au lundi : on cale le planningmodel sur le lundi ($joursemaine = 1) et on cale la date à insérer dans le planning au jour après le dimanche
            if ($joursemaine == 0) {
                $joursemaine = 1;
                $datedebgeneration = $datedebgeneration->modify('+ 1 day');
            }
        //Chagement des données de la table planningmodel dans un tableau de tableaux
        $planningModel = $app['dao.planningmodel']->findAll();
        //iitialisation du tableau de données
        $i = 1;
        $objetCtrl = array();
            //Boucle sur la semaine à générer
            foreach($planningModel as $jour){
                //Gestion de la date de début de génération du planning dans le cas du Dimanche 
                if($joursemaine <= $jour->getJour()){                 
                //calcul de la différence de jours     
                $diff = $jour->getJour() - $joursemaine;
                $dataaentrer = $datedebgeneration->modify('+'. $diff . ' day');
                //récupération des données du tableau
                $numerodujour= $jour->getJour();
                $heure = $jour->getHeure();
                $duree = $jour->getDuree();
                $placemax = $jour->getPlacemax();
                $coursid = $jour->getCoursid();

                //Création de l'objet planning à insérer en base
                $planninginsert = new planning();   
                
                //modification du jour pour insérer la date et l'heure
                $planninginsert -> setDatecours(date_format($dataaentrer, 'Y-m-d').' '. $heure);
                $planninginsert -> setDuree($duree);
                $planninginsert -> setPlacemax($placemax);
                $planninginsert -> setCoursid($coursid);

                $objetCtrl[$i] = $planninginsert;
                $i+=1;
                //trace du jour de traitement pour comparaison si même journée planningmodel traitée
                //$gjour = $jour->getJour();
                $dataaentrer = $datedebgeneration->modify('-'. $diff . ' day');
                
                //insertion dans la base de l'objet planninginsert
                $app['dao.planning']->insert($planninginsert);
                }//Fin boucle if
            }//fin boucle foreach
        }//fin boucle des semaines à générer
        $app['session']->getFlashBag()->add('success', 'Le planning de la période demandée a été généré avec succès.');

             
        return $app['twig']->render('planninggenere.html.twig', array(
            'datedeb' => $datedeb,
            'dategen' => $datedebgeneration,
            //'dateinsert' => $dateinsert,
            'planningModel' => $planningModel,
            'planninginsert' => $planninginsert,
            'objetCtrl' => $objetCtrl

        
            
            
        ));        


    // En attente de modification ....    


    }
}