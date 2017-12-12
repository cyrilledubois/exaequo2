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
            ->add('sex', ChoiceType::class, array('label' => ' ',
               'choices' => array('Mlle' => 'Mlle', 'Mme' => 'Mme', 'M.' => 'M.')))
            ///////////////////////////////////////////////////////////
            ->add('username', TextType::class, array(
                'label' => 'Nom*',
                'constraints'     => array(
                                            new Assert\NotBlank(),
                                            new Assert\Length(array(
                                                'min' => 2,
                                                'max' => 50,
                                                'minMessage' => 'le nom ne peut comporter moins de 2 caractère',
                                                'maxMessage' => 'le nom ne peut comporter plus de 50 caractère'
                                                ))
                                            )
            ))
            /////////////////////////////////////////////////////////////
            ->add('datedenaissance',  TextType::class, array(
                'label' => 'Date de naissance*',
                'constraints'     => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 8,
                        'max' => 8,
                        'minMessage' => 'la date de naissance doit être au format jj/mm/aa',
                        'maxMessage' => 'la date de naissance doit être au format jj/mm/aa'
                ))),
                'attr' => array(
                'placeholder' => 'jj/mm/aa'         
             )))
            /////////////////////////////////////////////////////////////
            ->add('firstname', TextType::class, array(
                'label' => 'Prénom*',
                'constraints'     => array(
                                            new Assert\NotBlank(),
                                            new Assert\Length(array(
                                                'min' => 2,
                                                'max' => 50,
                                                'minMessage' => 'le prénom ne peut comporter moins de 2 caractère',
                                                'maxMessage' => 'le prénom ne peut comporter plus de 50 caractère'
                                                )))
            ))
            ///////////////////////////////////////////////
            ->add('adress', TextType::class, array(
                'label' => 'Adresse*',
                'constraints'     => array(
                                            new Assert\NotBlank(),
                                            new Assert\Length(array(
                                                'min' => 2,
                                                'max' => 255,
                                                'minMessage' => 'L\'adresse ne peut comporter moins de 2 caractères',
                                                'maxMessage' => 'L\adresse ne peut comporter plus de 255 caractères'
                                                )))
            ))
            //////////////////////////////////////////////
            ->add('cp', TextType::class, array(
                'label' => 'Code Postal*',
                'constraints' => array (
                                        new Assert\NotBlank(),
                                        new Assert\Length(array(
                                                                'min' => 5,
                                                                'max' => 5,
                                                                'minMessage' => 'Le code postal ne peut comporter moins de 5 caractères',
                                                                    'maxMessage' => 'Le code postal ne peut comporter plus de 5 caractères'
                                        )))
            ))
            ////////////////////////////////////////////////
            ->add('town', TextType::class, array(
                'label' => 'Ville*',
                'constraints'     => array(
                                            new Assert\NotBlank(),
                                            new Assert\Length(array(
                                                'min' => 2,
                                                'max' => 255,
                                                'minMessage' => 'La ville ne peut comporter moins de 2 caractères',
                                                'maxMessage' => 'Le ville ne peut comporter plus de 255 caractères'
                                                ))
                                            )
            ))
            ///////////////////////////////////////////////////
            ->add('profession', TextType::class, array(
                'label' => 'Profession',
                'required'      => false,
            ))
            ///////////////////////////////////////////////////////////////////
            ->add('groupeclient', TextType::class, array(
                'label' => 'Groupe Client',
                'required'      => false,
            ))
            ////////////////////////////////////////////////////////////////////
            ->add('email', TextType::class, array(
                'label' => 'Email*',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'form-control',
                ),
                'constraints' => new Assert\Email()
            ))

            ////////////////////////////////////////////////////////////
            ->add('phone', TextType::class, array(
                'label' => 'Téléphone*',
                'constraints'     => array(
                                        new Assert\NotBlank(),
                                        new Assert\Length(array(
                                            'min' => 10,
                                            'max' => 10,
                                            'minMessage' => 'le numéro de téléphone, ne peut comporter moins de 10 numéros',
                                            'maxMessage' => 'le numéro de téléphone, ne peut comporter plus de 10 numéros'
                                        ))
                                    )

        ))
            ////////////////////////////////////////////////////////////
            ->add('password', RepeatedType::class, array(
                'type'            => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre',
                'options'         => array('required' => true),
                'first_options'   => array('label' => 'Mot de passe*'),
                'second_options'  => array('label' => 'Répétez le mot de passe*'),
                'constraints'     => array(
                                        new Assert\NotBlank(),
                                        new Assert\Length(array(
                                            'min' => 5,
                                            'max' => 50,
                                            'minMessage' => 'le mdp doit faire au moins 5 caractères',
                                            'maxMessage' => 'le mdp ne doit faire plus de 50 caractères'
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
