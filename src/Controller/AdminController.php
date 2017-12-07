<?php
namespace WF3\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\ArticleType;
use WF3\Domain\Article;
use WF3\Domain\Planning;
use WF3\Form\Type\UserType;
use WF3\Domain\User;

class AdminController{
    

    public function getInfoPlanning(Application $app){
        $planning = $app['dao.planning']->getInfoPlanning($date_jour);
        return $app['twig']->render('admin/index.admin.html.twig', array(
            'planning'=>$planning
        ));
    }
// MODIF 06/12 14h
    //page d'accueil du back office
    public function indexAction(Application $app){
      
        $planning = $app['dao.planning']->findAll();
        $cours = $app['dao.cours']->findAll();
        $users = $app['dao.user']->findAll();
        return $app['twig']->render('admin/index.admin.html.twig', array(
                                        'planning'=>$planning,
                                        'cours'=>$cours,                                        
                                        'users' =>$users
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
}