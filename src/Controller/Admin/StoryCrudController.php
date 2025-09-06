<?php

namespace App\Controller\Admin;

use App\Entity\Story;
use App\Enum\StoryGenreEnum;
use App\Enum\StoryStatus;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field as EasyAdmin;

class StoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Story::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EasyAdmin\IdField::new('id')->hideOnForm(),
            EasyAdmin\TextField::new('title'),
            EasyAdmin\TextEditorField::new('summary'),
            EasyAdmin\AssociationField::new('owner'),
            EasyAdmin\ChoiceField::new('genre')
                ->setChoices(array_combine(
                    array_map(fn($case) => $case->value, StoryGenreEnum::cases()),
                    array_map(fn($case) => $case->value, StoryGenreEnum::cases())
                )),
            EasyAdmin\ChoiceField::new('storyStatus')
                ->setChoices(array_combine(
                    array_map(fn($case) => $case->value, StoryStatus::cases()),
                    array_map(fn($case) => $case->value, StoryStatus::cases())
                )),
            EasyAdmin\TextField::new('template'),
            EasyAdmin\DateTimeField::new('createdAt')->hideOnForm(),
            EasyAdmin\DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
