<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordRequestFormType extends AbstractType
{
    /**
     * Fonction qui permet la construction du formulaire de réinitialisation du mot de passe d'un utilisateur
     *
     * @param FormBuilderInterface $builder : variable qui permet la création d'un formulaire
     * @param array $options : tableau qui permet de lister les champs du formulaire
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
            'attr' => ['autocomplete' => 'email','class' => 'form-control','placeholder' => "reset@domaine.fr"],
            //'label' => "Adresse email :",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre adresse e-mail',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}