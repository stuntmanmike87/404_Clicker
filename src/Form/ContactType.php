<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class ContactType extends AbstractType
{
    /**
     * Fonction qui élabore le formulaire de contact
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, [
                //'label' => 'Nom',
            ])
            ->add('email',EmailType::class, [
                //'label' => 'Adresse e-mail',
            ])
            /* ->add('sujet',TextType::class, [
                //'label' => 'Sujet',
            ]) */
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 6],
                //'label' => 'Votre message',
            ])
            ->add('captcha', CaptchaType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}