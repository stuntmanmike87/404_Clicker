<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\Validator\Constraints\Length;
//use Symfony\Component\Validator\Constraints\NotBlank;

final class UserType extends AbstractType
{
    /**
     * Fonction qui permet la construction du formulaire
     * de création d'un utilisateur
     * $builder : variable qui permet la création d'un formulaire
     * $options : tableau qui permet de lister les champs du formulaire
     */
    //@param array<string> $options
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {//Unused parameter $options.
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    /*,'class' => 'form-control'],*/
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Saisir à nouveau le mot de passe',
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre',
                //Instead of being set onto the object directly,
                //this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
