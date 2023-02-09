<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends AbstractController
{
    /**
     * Fonction qui traite le formulaire de contact
     * contact/index.html.twig : page de contact
     */
    #[Route(path: '/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var array<string> $contactFormData */
            $contactFormData = $form->getData();

            $message = (new Email())->from($contactFormData['email']);
            $message = (new Email())->to('your@mail.com');
            $message = (new Email())->subject('vous avez reçu un e-mail');
            $message = (new Email())->text(
                'Sender : '.$contactFormData['email']
                .\PHP_EOL.$contactFormData['message'],
                'text/plain'
            );

            $mailer->send($message);

            $this->addFlash('success', 'Votre message a été envoyé');

            return $this->redirectToRoute('home');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
