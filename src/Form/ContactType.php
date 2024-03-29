<?php

declare(strict_types=1);

namespace App\Form;

// use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ContactType extends AbstractType
{
    /**
     * Fonction qui élabore le formulaire de contact.
     */
    // @param array<string> $options
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void // Unused parameter $options.
    {$builder->add(
        'nom',
        TextType::class,
        [/* 'label' => 'Nom', */]
    );
        $builder->add(
            'email',
            EmailType::class,
            [/* 'label' => 'Adresse e-mail', */]
        );
        /* $builder->add('sujet',TextType::class, ['label' => 'Sujet',]); */
        $builder->add(
            'message',
            TextareaType::class,
            ['attr' => ['rows' => 6]/* 'label' => 'Votre message', */]
        );
        $builder->add('captcha'/* , CaptchaType::class */);
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
