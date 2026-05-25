<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(
        private readonly RouterInterface        $router,
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * Called on every request. Return true if this authenticator should handle it.
     */
    public function supports(Request $request): bool
    {
        return $request->isMethod('POST')
            && $request->attributes->get('_route') === self::LOGIN_ROUTE;
    }

    /**
     * Build the Passport (credentials + badges) that Symfony will validate.
     */
    public function authenticate(Request $request): Passport
    {
        $email    = $request->request->get('_username', '');
        $password = $request->request->get('_password', '');

        // Store last entered email so the form can re-populate it on failure
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email, function (string $identifier) {
                $user = $this->em->getRepository(User::class)
                    ->findOneBy(['email' => $identifier]);

                if (!$user) {
                    throw new CustomUserMessageAuthenticationException(
                        'Invalid email or password.'
                    );
                }

                return $user;
            }),
            new PasswordCredentials($password),
            [
                // CSRF protection — token field name must match the template
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                // "Remember me" — checkbox name must match security.yaml remember_me_parameter
                new RememberMeBadge(),
            ]
        );
    }

    /**
     * Called on successful authentication — redirect to the intended page or home.
     */
    public function onAuthenticationSuccess(
        Request        $request,
        TokenInterface $token,
        string         $firewallName
    ): ?Response {
        // Redirect to the page the user originally tried to visit (e.g. ?redirect=/dashboard)
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->router->generate('app_dashboard'));
    }

    /**
     * Called on failed authentication — redirect back to the login page.
     */
    public function onAuthenticationFailure(
        Request                 $request,
        AuthenticationException $exception
    ): Response {
        if ($request->hasSession()) {
            $request->getSession()->set(
                SecurityRequestAttributes::AUTHENTICATION_ERROR,
                $exception
            );
        }

        return new RedirectResponse($this->router->generate(self::LOGIN_ROUTE));
    }

    /**
     * Where to redirect unauthenticated users (used by AbstractLoginFormAuthenticator).
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate(self::LOGIN_ROUTE);
    }
}
