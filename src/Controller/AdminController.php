<?php
namespace WF3\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\ArticleType;
use WF3\Domain\Article;
use WF3\Form\Type\UserType;
use WF3\Domain\User;

class AdminController{
    
// MODIF 06/12 14h
    //page d'accueil du back office
    public function indexAction(Application $app){
        return $app['twig']->render('admin/index.admin.html.twig');
    }
}