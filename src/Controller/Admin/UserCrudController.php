<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field as EA;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EA\IdField::new('id')->hideOnForm(),
            EA\TextField::new('email'),
            EA\ChoiceField::new('roles')
                ->setChoices([
                    'User' => 'ROLE_USER',
                    'Moderator' => 'ROLE_MODERATOR',
                    'Admin' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices()
                ->renderAsBadges([
                    'ROLE_USER' => 'secondary',
                    'ROLE_MODERATOR' => 'info',
                    'ROLE_ADMIN' => 'danger',
                ]),
            EA\BooleanField::new('isVerified'),
            EA\AssociationField::new('userProfile'),
            // Password field should not be edited directly in CRUD
            // TextField::new('password')->hideOnForm(),
        ];
    }
}
