<?php
// ce fichier contient la liste des routes = url ) que l'on va accepter
//silex va parcourir les routes de haut en bas et s'arrête à la première qui correspond

//page d'accueil
$app->get('/', 'WF3\Controller\HomeController::homePageAction')
->bind('accueil');
//bind permet de nommer une route
//on peut alors appeler cette route dans une vue twig pour générer l'url correspondante

//cours collectif
$app->get('/cours_collectifs', 'WF3\Controller\HomeController::homePageCoursCollectifs')
->bind('cours_collectifs');

//fitness_cardio_physique
$app->get('/fitness_cardio_physique', 'WF3\Controller\HomeController::homePageFitnessCardioPhysique')
->bind('fitness_cardio_physique');

//cours_boxe
$app->get('/cours_boxe', 'WF3\Controller\HomeController::homePageCoursBoxe')
->bind('cours_boxe');

//cours_pilates
$app->get('/cours_pilates', 'WF3\Controller\HomeController::homePageCoursPilates')
->bind('cours_pilates');

//cours_yoga
$app->get('/cours_yoga', 'WF3\Controller\HomeController::homePageCoursYoga')
->bind('cours_yoga');

//equipe
$app->match('/equipe', 'WF3\Controller\HomeController::homePageEquipe')
->bind('equipe');

//tarifs
$app->get('/tarifs', 'WF3\Controller\HomeController::homePageTarifs')
->bind('tarifs');

//partenaires
$app->match('/partenaires', 'WF3\Controller\HomeController::homePagePartenaires')
->bind('partenaires');

//reservation
$app->match('/reservation', 'WF3\Controller\HomeController::homePageReserv')

->bind('reservation');

//contact
$app->match('/contact', 'WF3\Controller\HomeController::contactAction')
->bind('contact');

//connexion
$app->match('/login', 'WF3\Controller\HomeController::loginAction')
->bind('connexion');

//inscription
$app->match('/inscription', 'WF3\Controller\HomeController::signInAction')
->bind('inscription');

//Back ADMIN 
$app->match('/administration', 'WF3\Controller\AdminController::indexAction')
->bind('admin');

//Back USER
$app->match('/administration', 'WF3\Controller\AdminController::indexAction')
->bind('admin');



//Affichage cours du jour 
$app->match('/ajax/jourcours/{j}', 'WF3\Controller\AjaxHomeController::jourCours')
->bind('ajaxJourCour');

//Back user 
$app->match('/back', 'WF3\Controller\HomeController::backUser')
->bind('back');

//Generation planning à partir de Planning_Type
$app->match('/planninggenere', 'WF3\Controller\AdminController::generationPlanning')
->bind('planning genere');