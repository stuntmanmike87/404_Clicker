<?php

declare(strict_types=1);

namespace App\Security;

use Override;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Classe qui traite l'authentification d'un utilisateur par le biais d'un formulaire
 */
final class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const string LOGIN_ROUTE = 'app_login';

    /**
     * Constructeur de la classe LoginFormAuthenticator
     * qui hérite de AbstractLoginFormAuthenticator
     */
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * Fonction qui gère la requête d'authentification
     */
    #[Override]
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $iSession = $request->getSession();
        $iSession->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        /** @param string UserBadge(strval($email)) */
        return new Passport(
            new UserBadge((string) $email),
            new PasswordCredentials(
                (string) $request->request->get('password', '')
            ),
            [
                new CsrfTokenBadge(
                    'authenticate',
                    (string) $request->request->get('_csrf_token')
                ),
            ]
        );
    }

    /**
     * Fonction qui traite la réponse d'une authentification qui a réussi
     */
    #[Override]
    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?Response {//Unused parameter $token
        //$user = $token->getUser();
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        if ($targetPath !== '') {
            return new RedirectResponse((string) $targetPath);
        }

        //For example:
        return new RedirectResponse($this->urlGenerator->generate('home'));
        //throw new \Exception('Fournir ici une redirection valide '.__FILE__);
        //to_do_task : provide a valid redirect inside
    }

    /**
     * Fonction qui récupère l'URL de connexion
     */
    #[Override]
    protected function getLoginUrl(Request $request): string
    {//Unused parameter $request.
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
