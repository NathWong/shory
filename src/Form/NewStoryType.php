<?php

namespace App\Form;

use App\Entity\Chapter;
use App\Entity\Story;
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
                    'style' => 'height: 150px',
                ],
            ])
            ->add('genre', EnumType::class, [
                'class' => StoryGenreEnum::class,
                'choice_label' => fn (StoryGenreEnum $genre) => $genre->value,
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('template', ChoiceType::class, [
                'label' => 'Visual Template',
                'choices' => $this->templateManager->getChoices(),
                'attr' => ['class' => 'form-select'],
            ])
        ;

        // Add startingChapter field only if editing an existing story with chapters
        if ($options['story'] instanceof Story && !$options['story']->getChapters()->isEmpty()) {
            $builder->add('beginning', EntityType::class, [
                'class' => Chapter::class,
                'choice_label' => 'title',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.story = :story')
                        ->setParameter('story', $options['story'])
                        ->orderBy('c.title', 'ASC');
                },
                'placeholder' => 'Choose a starting chapter',
                'required' => false,
                'attr' => [
                    'class' => 'form-select',
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Story::class,
            'story' => null, // Add a default null value for the story option
        ]);

        $resolver->setAllowedTypes('story', [Story::class, 'null']);
    }
}
