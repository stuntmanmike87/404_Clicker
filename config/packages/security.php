<?php

declare(strict_types=1);

use App\Entity\User;
use App\Security\UserAuthenticator;
use App\Security\UserChecker;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('security', [/* 'enable_authenticator_manager' => true,  */'password_hashers' => [PasswordAuthenticatedUserInterface::class => 'auto', User::class => ['algorithm' => 'auto']], 'providers' => ['app_user_provider' => ['entity' => ['class' => User::class, 'property' => 'email']]], 'firewalls' => ['dev' => ['pattern' => '^/(_(profiler|wdt)|css|images|js)/', 'security' => false], 'main' => ['lazy' => true, 'provider' => 'app_user_provider', 'custom_authenticator' => UserAuthenticator::class, 'logout' => ['path' => 'app_logout'], 'pattern' => '^/', 'user_checker' => UserChecker::class]], 'access_control' => [['path' => '^/jouer', 'roles' => 'ROLE_USER'], ['path' => '^/user/id', 'roles' => 'ROLE_USER'], ['path' => '^/user/id/edit', 'roles' => 'ROLE_USER']]]);
    if ($containerConfigurator->env() === 'test') {
        $containerConfigurator->extension('security', ['password_hashers' => [PasswordAuthenticatedUserInterface::class => ['algorithm' => 'auto', 'cost' => 4, 'time_cost' => 3, 'memory_cost' => 10]]]);
    }
};
