<?php

namespace App\Form;

use App\Entity\Chapter;
use App\Entity\Story;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoryVersionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('summary', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Summary',
                    'style' => 'height: 150px'
                ],
            ])
        ;

        /** @var Story|null $story */
        $story = $options['data'] ?? null;

        if ($story && !$story->getChapters()->isEmpty()) {
            $builder->add('beginning', EntityType::class, [
                'class' => Chapter::class,
                'choice_label' => 'title',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($story) {
                    return $er->createQueryBuilder('c')
                        ->where('c.story = :story')
                        ->setParameter('story', $story)
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
        ]);
    }
}
