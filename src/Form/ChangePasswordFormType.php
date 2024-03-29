<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Validator\Constraints\Length;
// use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

final class ChangePasswordFormType extends AbstractType
{
    /**
     * Fonction qui permet la construction du formulaire
     * de changement de mot de passe.
     */
    // @param array<string> $options
    #[Assert\NotCompromisedPassword(message: "Ce mot de passe a été divulgué lors d'une fuite de données, veuillez utiliser un autre mot de passe pour votre sécurité.")]
    #[Assert\NotBlank(message: 'Veuillez saisir un mot de passe')]
    #[Assert\Regex(pattern: '/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*\d)(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{12,}$/', message: 'Le mot de passe doit être composé de 12 caractères dont au minimum : 1 lettre minuscule, 1 lettre majuscule, 1 chiffre, 1 caractère spécial (dans un ordre aléatoire).')]
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Unused parameter $options.
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                    ],
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                    ],
                    'label' => 'Saisir à nouveau le mot de passe',
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
