<?php

namespace App\Form;

use App\Entity\Story;
use App\Entity\UserProfile;
use App\Enum\StoryGenreEnum;
use App\Service\StoryTemplateManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewStoryType extends AbstractType
{
    public function __construct(
        private readonly StoryTemplateManager $templateManager,
    ) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Title',
                ],
            ])
            ->add('summary', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Summary',
                    'style' => 'height: 150px'
                ],
            ])
            ->add('genre', EnumType::class, [
                'class' => StoryGenreEnum::class,
                'choice_label' => fn(StoryGenreEnum $genre) => $genre->value,
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('template', ChoiceType::class, [
                'label' => 'Visual Template',
                'choices' => $this->templateManager->getChoices(),
                'attr' => ['class' => 'form-select'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Story::class,
        ]);
    }
}
