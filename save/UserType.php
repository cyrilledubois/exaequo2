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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('sex', ChoiceType::class, array('label' => ' ',
               //'choices' => array('Mlle' => 1, 'Mme' => 2, 'M.' => 3)))
            ->add('username', TextType::class, array('label' => 'Nom'))
            ->add('firstname', TextType::class, array('label' => 'Prénom'))
            /*->add('date_de_naissance', BirthdayType::class, array(
            'placeholder' => array('day' => 'Jour','month' => 'Mois','year' => 'Année')
            ))*/

            ->add('adress', TextType::class, array('label' => 'Adresse'))
            ->add('cp', TextType::class, array('label' => 'Code Postal'))
            ->add('town', TextType::class, array('label' => 'Ville'))
            ->add('profession', TextType::class, array('label' => 'Profession'))
            ->add('groupe_client', TextType::class, array('label' => 'Groupe Client'))
            ->add('email', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Votre email',
                    'class' => 'form-control'
                ),
                'constraints' => new Assert\Email()
            ))
            ->add('phone', TextType::class, array('label' => 'Téléphone'))
            ->add('password', RepeatedType::class, array(
                'type'            => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre',
                'options'         => array('required' => true),
                'first_options'   => array('label' => 'Mot de passe'),
                'second_options'  => array('label' => 'Répétez le mot de passe'),
                'constraints'     => array(
                                        new Assert\NotBlank(),
                                        new Assert\Length(array(
                                            'min' => 5,
                                            'max' => 12,
                                            'minMessage' => 'le mdp doit faire au moins 5 caractères',
                                            'maxMessage' => 'le mdp ne doit faire plus de 12 caractères'
                                        ))
                                    )
             ))
;
    }

    public function getName()
    {
        return 'user';
    }
}