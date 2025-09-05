<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Story;
use App\Entity\Chapter;
use App\Entity\ModerationMessage;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Controller\Admin\UserCrudController;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // Option 1. Rediriger vers la liste des utilisateurs (ou toute autre entité par défaut)
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. Vous pouvez aussi rendre un template de tableau de bord personnalisé si vous en avez un
        // return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Shory Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Users & Roles');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);

        yield MenuItem::section('Stories Management');
        yield MenuItem::linkToCrud('Stories', 'fa fa-book', Story::class);
        yield MenuItem::linkToCrud('Chapters', 'fa fa-file-alt', Chapter::class);

        yield MenuItem::section('Moderation');
        yield MenuItem::linkToCrud('Moderation Messages', 'fa fa-envelope', ModerationMessage::class);
    }
}
