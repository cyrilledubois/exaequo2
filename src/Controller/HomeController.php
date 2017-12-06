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
//permet de générer des erreurs 403 (accès interdit)
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class HomeController{

	//page d'accueil
	public function homePageAction(Application $app){

	 	return $app['twig']->render('accueil.html.twig');
	}

    //page des cours collectifs
    public function homePageCoursCollectifs(Application $app){

        return $app['twig']->render('cours_collectifs.html.twig');
    }

    //page Fitness Cardio Physique
    public function homePageFitnessCardioPhysique(Application $app){

        return $app['twig']->render('fitness_cardio_physique.html.twig');
    }

     //page Boxe
    public function homePageCoursBoxe(Application $app){

        return $app['twig']->render('cours_boxe.html.twig');
    }

     //page Pilates
    public function homePageCoursPilates(Application $app){

        return $app['twig']->render('cours_pilates.html.twig');
    }

     //page Yoga
    public function homePageCoursYoga(Application $app){

        return $app['twig']->render('cours_yoga.html.twig');
    }

     //page Notre Equipe
    public function homePageEquipe(Application $app){

        return $app['twig']->render('equipe.html.twig');
    }

     //page Nos Tarifs
    public function homePageTarifs(Application $app){

        return $app['twig']->render('tarifs.html.twig');
    }

     //page Nos Partenaires
    public function homePagePartenaires(Application $app){

        return $app['twig']->render('partenaires.html.twig');
    }

     //page Contact
    public function homePageContact(Application $app){

        return $app['twig']->render('contact.html.twig');
    }
    
    
    
	//page contact
	public function contactAction(Application $app, Request $request){
        $contactForm = $app['form.factory']->create(ContactType::class);
        $contactForm->handleRequest($request);
        
        if ($contactForm->isSubmitted() && $contactForm->isValid())
        {
            $data = $contactForm->getData();
            $message = \Swift_Message::newInstance()
                        ->setSubject($data['subject'])
                        ->setFrom(array('promo5wf3@gmx.fr'))
                        ->setTo(array('votre@mail.com'))
                        ->setBody($app['twig']->render('contact.email.html.twig',
                            array('name'=>$data['name'],
                                   'email' => $data['email'],
                                   'message' => $data['message']
                            )
                        ), 'text/html');

            $app['mailer']->send($message);


        }
        return $app['twig']->render('contact.html.twig', array(
            'title' => 'Contact Us',
            'contactForm' => $contactForm->createView(),
            'data' => $contactForm->getData()
        ));
	}
    
   
    public function loginAction(Application $app, Request $request){
    	//j'appelle la vue qui contient le formulaire de connexion
    	//error va contenir les éventuels messages d'erreur
    	return $app['twig']->render('login.html.twig', array(
    		'error' => $app['security.last_error']($request),
    		'last_username' => $app['session']->get('_security.last_username')
    	));
    }

    public function ajoutArticleAction(Application $app, Request $request){
    	//on va vérifier que l'utilisateur est connecté
    	if(!$app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')){
            //je peux rediriger l'utilisateur non authentifié
            //return $app->redirect($app['url_generator']->generate('home'));
            throw new AccessDeniedHttpException();
        }
        //on récupère le token si l'utilisateur est connecté
        $token = $app['security.token_storage']->getToken();
        if(NULL !== $token){
            $user = $token->getUser();
        }

    	//je crée un objet article vide
    	$article = new Article();
    	//je crée mon objet formulaire à partir de la classe ArticleType
    	$articleForm = $app['form.factory']->create(ArticleType::class, $article);
    	//on envoie les paramètres de la requête à notre objet formulaire
    	$articleForm->handleRequest($request);
    	//on vérifie si le formulaire a été envoyé
    	//et si les données envoyées sont valides
    	if($articleForm->isSubmitted() && $articleForm->isValid()){
    		//c'est l'utilisateur connecté qui est l'auteur de l'article
    		$article->setAuthor($user->getId());
    		//on insère dans la base
    		$app['dao.article']->insert(array(
    			'title'=>$article->getTitle(),
    			'content'=>$article->getContent(),
    			'author'=>$article->getAuthor()
    		));
    		//on stocke en session un message de réussite
    		$app['session']->getFlashBag()->add('success', 'Article bien enregistré');

    	}

    	//j'envoie à la vue le formulaire grâce à $articleForm->createView() 
    	return $app['twig']->render('ajout.article.html.twig', array(
    			'articleForm' => $articleForm->createView()
    	));
    }
    
    /**
     * User sign in controller.
     *
     * @param Application $app Silex application
     * @param Request $request the http request
     */
    public function signInAction(Application $app, Request $request){	
        $user = new User();
        $userForm = $app['form.factory']->create(UserRegisterType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // generate a random salt value
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            //get plain password 
            $plainPassword = $user->getPassword();
            // find the default encoder
            $encoder = $app['security.encoder.bcrypt'];
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            //new users role is ROLE_USER by default
            $user->setRole('ROLE_USER');
            $app['dao.user']->insert($user);

            /*//this code automatically login new user 
            $token = new UsernamePasswordToken(
                $user, 
                $user->getPassword(), 
                'main',                 //key of the firewall you are trying to authenticate 
                array('ROLE_USER')
            );
            $app['security.token_storage']->setToken($token);

            // _security_main is, again, the key of the firewall
            $app['session']->set('_security_main', serialize($token));
            $app['session']->save(); // this will be done automatically but it does not hurt to do it explicitly*/


            $app['session']->getFlashBag()->add('success', 'Hello ' .  $user->getUsername());
            // Redirect to admin home page
            return $app->redirect($app['url_generator']->generate('homepage'));
        }
        return $app['twig']->render('user_register.html.twig', array(
            'title' => 'Sign in',
            'userForm' => $userForm->createView()
        ));
    }

}