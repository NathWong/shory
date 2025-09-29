<?php

namespace App\Security\Listener;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class RedirectionListener
{
    public function __construct(
        private Security $security,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[AsEventListener(KernelEvents::REQUEST)]
    public function redirectAuthenticated(RequestEvent $event): void
    {
        if (!$user = $this->security->getUser()) {
            return;
        }

        if ('app_login' !== $event->getRequest()->attributes->get('_route')) {
            return;
        }

        if (!($user instanceof User)) {
            return;
        }

        $route = $user->getUserProfile() ? 'app_home' : 'app_profile_new';
        $response = new RedirectResponse($this->urlGenerator->generate($route));
        $event->setResponse($response);
    }
}
