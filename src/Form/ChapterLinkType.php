<?php

namespace App\Form;

use App\Entity\Chapter;
use App\Entity\ChapterLink;
use App\Form\DataTransformer\ResponseToStringTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChapterLinkType extends AbstractType
{
    public function __construct(
        private readonly ResponseToStringTransformer $responseTransformer,
    ) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('responses', TextType::class, [
                'label' => 'response',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('target', EntityType::class, [
                'class' => Chapter::class,
                'choice_label' => 'title',
                'label' => 'Targeted Chapter',
                'required' => false,
                'attr' => ['class' => 'form-select']
            ])
            ->add('id', HiddenType::class, ['mapped' => false])
        ;

        $builder->get('responses')->addModelTransformer($this->responseTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChapterLink::class,
        ]);
    }
}
