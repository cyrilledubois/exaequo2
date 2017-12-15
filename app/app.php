<?php
//on utie des composants Symfony qui vont nous permettre d'avoir des erreurs plus précises
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Silex\Provider;

//On enregistre ces services dans l'application Silex
ErrorHandler::register();
ExceptionHandler::register();

$app->register(new Provider\HttpFragmentServiceProvider());
$app->register(new Provider\ServiceControllerServiceProvider());

//Paypal//paypal
$app->register(new SKoziel\Silex\PayPalRest\PayPalServiceProvider(), array(
    'paypal.settings'=>array(
        'mode'=>'sandbox', //'live' or 'sandbox'(default)
        'clientID'=>'jhgjhgfgufu', //Checkout PayPal Documentation for more info
        'secret'=>'gfchgfcfhygtcfy', //Checkout PayPal Documentation for more info
        'connectionTimeOut'=>30, //Connection time out in seconds, optional, default = 30
        'logEnabled'=>false, //This parameter is optional, default = true
        'logdir'=>'logs', //This parameter is optional, default = ROOT/logs
        'currency'=>'EUR' //This parameter is optional, default = EUR
    )));

//On enregistre le service dbal
$app->register(new Silex\Provider\DoctrineServiceProvider());

//on enregistre le service twig
$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));

//enregistrement du service Symfony asset 
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1'
));

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/connexion', 'check_path' => '/login_check'),
            'users' => function () use ($app) {
                return new WF3\DAO\UserDAO($app['db'], 'users', 'WF3\Domain\User');
            },
            'logout' => array('logout_path' => '/logout', 'invalidate_session' => true)
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER')
    ),
    'security.access_rules' => array(


        array('^/admin', 'ROLE_ADMIN')

    )
));


//service web profiler de symfony
$app->register(new Provider\WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => __DIR__.'/../cache/profiler',
    'profiler.mount_prefix' => '/_profiler', // this is the default
));
//ajout du odule dbal au webprofiler
$app->register(new Sorien\Provider\DoctrineProfilerServiceProvider());

//enregistrement du composant form
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

//enregistrement du service Validator
$app->register(new Silex\Provider\ValidatorServiceProvider());

//enregistrement du service SwiftMailer
$app->register(new Silex\Provider\SwiftmailerServiceProvider());
//swiftmailer
$app['swiftmailer.options'] = array(
    'host' => 'mail.gmx.com',
    'port' => '465',
    'username' => 'promo5wf3@gmx.fr',
    'password' => 'ttttttttt33',
    'encryption' => 'SSL',
    'auth_mode' => null
);


$app['dao.planning'] = function($app){
    return new WF3\DAO\PlanningDAO($app['db'], 'planning', 'WF3\Domain\Planning');
};

$app['dao.cours'] = function($app){
    return new WF3\DAO\CoursDAO($app['db'], 'cours', 'WF3\Domain\cours');
};

//on enregistre un nouveau service :
//on pourra ainsi accéder à notre classe UserDAO grâce à $app['dao.user'] 
$app['dao.user'] = function($app){
	return new WF3\DAO\UserDAO($app['db'], 'users', 'WF3\Domain\User');
};

//on pourra ainsi accéder à notre classe planningmodelDAO grâce à $app['dao.planningmodel'] 
$app['dao.planningmodel'] = function($app){
    return new WF3\DAO\PlanningModelDAO($app['db'], 'planningmodel', 'WF3\Domain\PlanningModel');
};

$app['dao.abo'] = function($app){
   return new WF3\DAO\AboDAO($app['db'], 'abo', 'WF3\Domain\abo');
};

