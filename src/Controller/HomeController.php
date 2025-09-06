<?php

namespace App\Controller;

use App\Trait\ProfiledUserTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    use ProfiledUserTrait;

    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        // Check if the user is logged in and doesn't have a profile
        if ($this->getUser() && !$this->getUserProfile()) {
            return $this->redirectToRoute('app_profile_new');
        }

        return $this->render('home/index.html.twig');
    }
}
