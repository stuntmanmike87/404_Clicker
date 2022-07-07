<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ResetPasswordRequestFormType extends AbstractType
{
    /**
     * Fonction qui permet la construction du formulaire
     * de réinitialisation du mot de passe d'un utilisateur
     *
     * @param array<string> $options
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {//Unused parameter $options.
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'autocomplete' => 'email',
                    'class' => 'form-control',
                    'placeholder' => "reset@domaine.fr",
                ],
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
