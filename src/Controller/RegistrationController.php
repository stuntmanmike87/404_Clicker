<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\LevelRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function register(
        Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, LevelRepository $levelRepository): Response
    {
        if ($this->getUser()) {
            return $this->render('home/index.html.twig');
        }

        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(['ROLE_USER']);
            $user->setPoints(0);
            $user->setIsVerified(false);
            $level = $levelRepository->find(1);
            $user->setLevel($level);

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('mail@your-mail.com', 'Email Bot'))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmer votre adresse email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Vous êtes bien inscrit, merci de bien regarder vos mails pour faire la vérification.');

            $this->addFlash('success', 'Vous êtes bien inscrit, merci de bien regarder vos mails pour faire la vérification.');
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('home');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', "Votre adresse email vient d'être vérifiée avec succès.");

        return $this->redirectToRoute('inscription');
    }

    /**
     * @Route("/verifyMyEmail", name="app_verify_my_email")
     */
    /* public function newVerificationEmail(Request $request, User $user): Response
    {
        if($this->getUser()->getIsVerified()){
            return $this->render('home/index.html.twig');
            $this->addFlash('success', 'Votre adresse email a bien été vérifiée.');
        }else{
            return $this->render('registration/verify_my_email.html.twig');
        }
    } */

    /**
     * @Route("/newVerificationEmailUser", name="app_new_verification_email_user")
     */
    /* public function sendNewVerificationEmail(Request $request): Response
    {
        if($this->getUser() && !$this->getUser()->isVerified()){
            $user = $this->getUser();
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('mail@your-mail.com', 'Email Bot'))
                ->to($user->getEmail())
                ->subject('Veuillez confirmer votre adresse mail')
                ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            return $this->render('registration/check_email.html.twig');
        }else{
            return $this->redirectToRoute('inscription');
        }
    } */
}
