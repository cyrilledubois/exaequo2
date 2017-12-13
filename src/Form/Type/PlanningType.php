<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

//on utilise le validator de symfony
use Symfony\Component\Validator\Constraints as Assert;

class PlanningType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('cours_id', TextType::class, array(
                'label' => 'id ou nom du cour a changer'))
               
            ///////////////////////////////////////////////////////////
            
            /////////////////////////////////////////////////////////////
            ->add('date_cours',  TextType::class, array(
                'label' => 'Date du cour'
                   
             ))
            /////////////////////////////////////////////////////////////
            ->add('duree', TextType::class, array(
                'label' => 'duree'
            ));
          
}
}
