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
//paypal
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use WF3\Domain\PaypalInvoice;
use WF3\Domain\Sale;

class HomeController{

    public function homePageReserv(Application $app, Request $request){
        //NE PAS SUPPRIMER : 
        //initialisation de l'affichage du planning de réservation avec la date du jour.
        $dataffich = date("Y"). '-' .date("m") . '-' . date("d");
        //Pour les tests on peut afficher le planning de réervation à une date donnée, commenter la ligne du dessus !!! 
        $planning = $app['dao.planning']->getInfoPlanning($dataffich);
        $user = $app['user'];
        $idplanning = $request->query->get('id');
        if($request->query->get('id')){
            $idplanning = $request->query->get('id');
            $reservation = $app['dao.user']->reservAction($user->getId(), $request->query->get('id'));
        }
        return $app['twig']->render('reservation.html.twig', array(
            'planning'=> $planning,
            'user' => $user->getId(),
            'idplanning' => $idplanning
            //'reservation' => $reservation
        ));
    }

    public function updateUserAction(Application $app, Request $request ){
   //  On  utilise la ligne suivante afin de récuperer l'user en objet
       $user = $app['user'];
       
       $userForm = $app['form.factory']->create(UserType::class, $user);
       $userForm->handleRequest($request);
       if ($userForm->isSubmitted() && $userForm->isValid()) {
     
           //on récupère le mot de passe en clair (envoyé par l'utilisateur)
           $plainPassword = $user->getPassword();
           // on récupère l'encoder de silex
           $encoder = $app['security.encoder.bcrypt'];
           // on encode le mdp
           $password = $encoder->encodePassword($plainPassword, $user->getSalt());
           // on remplace le mdp en clair par le mdp crypté
           $user->setPassword($password);
           $app['dao.user']->update($user->getId(), $user);
           $app['session']->getFlashBag()->add('success', 'The user was successfully updated.');
           // Redirect to admin home page
           return $app->redirect($app['url_generator']->generate('back'));
       }
       return $app['twig']->render('back.html.twig', array(
           'title' => 'update user',
           'userForm' => $userForm->createView(),
           'user' => $user
       ));
   }



   
    // Back user
   public function backUser(Application $app){
       return $app['twig']->render('back.user.html.twig');
   }

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

     //page Nos Partenaires
     public function homePageReservation(Application $app){
        
            return $app['twig']->render('reservation.html.twig');
    }

     //page Contact
    public function homePageContact(Application $app){

        return $app['twig']->render('contact.html.twig');
    }
    
	//page paiemenb accepté
    public function paiementAccepte(Application $app, Request $request){
        
        $user = $app['user'];

        $paymentId = $request->query->get('paymentId');
        $payerId = $request->query->get('PayerID');
        $executionSuccessful = $app['paypal']->executePayment($payerId, $paymentId);
        $payment = Payment::get($paymentId, $app['paypal']->getPayPalApiContext());


        $payer = $payment->getPayer()->getPayerInfo();

       $email = $payer->getEmail();

       $firstName = $payer->getFirstName();

       $lastName = $payer->getLastName();

       $shipping = $payer->getShippingAddress();

       $city = $shipping->getCity();

       $state = $shipping->getState();

       $postalCode = $shipping->getPostalCode();

       $phone = $payer->getPhone();

       $country = $shipping->getCountryCode();        

       $adress = $firstName . ' ' . $lastName . ' - ' .

               $shipping->getLine1() . ' - ' . $shipping->getLine2() . ' - ' .

               $city . ' - ' . $postalCode . ' - ' . $state . ' - ' . $country;

       $createtime = $payment->getCreateTime();


       foreach($payment->getTransactions() as $transaction)
        {
            //$shipping = $transaction->getItemList()->getShippingAddress();
            $amount = $transaction->getAmount()->getTotal();
            $currency = $transaction->getAmount()->getCurrency();
            $tab = explode('--', $transaction->getDescription());
            $abonnementID = $tab[0];
            $itemDescription = $tab[1];
            //on récupère l'abonnement avec l'id renvoyée par paypal
            $abonnement = $app['dao.abonnement']->find($abonnementID);
            if($abonnement->getPrix() == $amount AND $currency == 'EUR' AND $executionSuccessful){
                //prices matches, product ids matches,currency is US Dollar and payment is valid
                $status = 'valid';
                $message = 'Congratulations, your payment has been accepted !'
                        . '<br>The artist have been notified of your purchase.';
                //send notifications
                //first purchase confirmation to customer
                $notification = \Swift_Message::newInstance()
                ->setSubject('Purchase notification on peddle-art.com')
                ->setFrom(array('no-reply@peddle-art.com'))
                ->setTo(array($user->getEmail()))
                /*->setBody($app['twig']->render('email/purchase.confirmation.email.html.twig',   // email template
                    array('name'      => $user->getUsername(),
                          'product'     => $product,
                    )),'text/html');*/
                ->setBody($message);
                $app['mailer']->send($notification);
                
                //then sale notification to exaequo
                $notification3 = \Swift_Message::newInstance()
                ->setSubject('Vente d\'un abonnement')
                ->setFrom(array('no-reply@exaequo.com'))
                ->setTo(array('adresse email de la salle de sport'))
                /*->setBody($app['twig']->render('email/sale.confirmation.email.html.twig',   // email template
                    array('name'      => $seller->getUsername(),
                          'product'     => $product,
                          'adress' => $adress,
                          'buyer' => $user->getUsername()
                    )),'text/html');*/
                ->setBody($message);

                $app['mailer']->send($notification3);
            }
            else{
                $status = 'invalid';
                $message = 'Unfortunalty, Your payment have not been accepted.'
                        . '<br>Contact us for any question you may have.';
            
                $notification4 = \Swift_Message::newInstance()
                ->setSubject('error : Sale notification on peddle-art.com')
                ->setFrom(array('no-reply@peddle-art.com'))
                ->setTo(array('contact@le-web-developpeur.com'))
                /*->setBody($app['twig']->render('email/sale.confirmation.email.html.twig',   // email template
                    array('name'      => $seller->getUsername(),
                          'product'     => $product,
                          'adress' => $adress,
                          'buyer' => $user->getUsername(). ' - ' . $productID . ' / ' . $productId . ' -- ' . $dbAmount . ' / ' . $amount . ' -- ' . $currency . ' -- ' . $executionSuccessful
                    )),'text/html');*/
                    ->setBody($message);

                $app['mailer']->send($notification4);
            }
                
            
        }
       //ici c'est l'enregistrement de la transaction dans la base: il faut créer une classe saleDAO (tu l'appelles comme tu veux) et la table associée qui va stocker les informations 
        $sale = new Sale();
        $sale
           ->setAmount($amount)
           ->setBuyerid($user->getId())
           ->setPaymentid($paymentId)
           ->setPayerid($payerId)
           ->setAbonnementid($abonnementID)
           ->setEmail($email)
           ->setCreatetime($createtime)
           ->setPhone($phone)
           ->setAdress($adress)
           ->setStatus($status);
        $app['dao.sale']->insert($sale);

        return $app['twig']->render('paiement_accepte.html.twig',array('request'=>$payment));
    }

    //page paiemenb refusé
    public function paiementRefuse(Application $app){

        return $app['twig']->render('paiement_refuse.html.twig');
    }

    //mot de passe perdu
    public function mdpPerdu(Application $app){

        return $app['twig']->render('mdp_perdu.html.twig');
    }


	public function contactAction(Application $app, Request $request){
        $contactForm = $app['form.factory']->create(ContactType::class);
        $contactForm->handleRequest($request);
        
        if ($contactForm->isSubmitted() && $contactForm->isValid())
        {
            $data = $contactForm->getData();
            $message = \Swift_Message::newInstance()
                        ->setSubject($data['subject'])
                        ->setFrom(array('promo5wf3@gmx.fr'))
                        ->setTo(array('desporout@gmail.com'))
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

            /*//this code automatically login new user ..
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



            $app['session']->getFlashBag()->add('success', 'Vous êtes bien enregistré ' .  $user->getUsername());

            // Redirect to admin home page
            return $app->redirect($app['url_generator']->generate('accueil'));
        }
        return $app['twig']->render('user_register.html.twig', array(
            'title' => 'Sign in',
            'userForm' => $userForm->createView()
        ));
    }



    public function achatPaypal(Application $app, Request $request, $id){
        //on va vérifier que l'utilisateur est connecté
        if(!$app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')){
            //je peux rediriger l'utilisateur non authentifié
            //return $app->redirect($app['url_generator']->generate('home'));
            $app['session']->getFlashBag()->add('success', 'Vous devez vous connecter pour effectuer un achat.');
            return $app->redirect($app['url_generator']->generate('connexion'));

        }
        //on récupère le token si l'utilisateur est connecté
        $token = $app['security.token_storage']->getToken();
        if(NULL !== $token){
            $user = $token->getUser();
        }

        $abonnement = $app['dao.abonnement']->find($id);

        $expressCheckout = $app['paypal']->createExpressCheckout();

        $invoice = new PaypalInvoice();
        $ppinvoice = $app['dao.paypalInvoice']->insert($invoice);
        $numeroUnique = $app['db']->lastInsertId();
        $expressCheckout
               ->addItem($abonnement->getNom(), 1, 'sku0', $abonnement->getPrix(), 'tarifs.html.twig')
               ->setDescription($abonnement->getId() . '--' . $abonnement->getDescriptif())
               ->setInvoiceNumber($numeroUnique)
               ->setSuccessUrl('https://localhost/exaequo2/web/paiement_accepte') //la route qui va gérer les infos de paiement accepté
               ->setFailureUrl('https://localhost/exaequo2/web/paiement_refuse'); //la route qui va gérer les erreurs de paiement

        $approvalUrl = $expressCheckout->getApprovalUrl($app['paypal']->getPayPalApiContext());
        return $app->redirect($approvalUrl);


        return  $abonnement->getNom();

    }   




}