<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// use Symfony\Component\Validator\Constraints\IsTrue;
// use Symfony\Component\Validator\Constraints\Length;
// use Symfony\Component\Validator\Constraints\NotBlank;
// use Symfony\Component\Validator\Constraints as Assert;

final class RegistrationFormType extends AbstractType
{
    /**
     * Fonction qui permet la construction du formulaire
     * d'enregistrement d'un utilisateur.
     */
    // @param array<string> $options
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void // Unused parameter $options.
    {
        $builder->add('email', EmailType::class);
        $builder->add('username', TextType::class);
        $builder->add(
            'plainPassword',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                // 'attr' => ['autocomplete' => 'new-password'],
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                // 'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ]
        );
        // $builder->add('register', SubmitType::class);
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
