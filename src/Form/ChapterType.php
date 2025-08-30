<?php

namespace App\Form;

use App\Entity\Chapter;
use App\Service\ChapterLinkTypeManager;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChapterType extends AbstractType
{
    public function __construct(
        private readonly ChapterLinkTypeManager $chapterLinkTypeManager,
    ) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Chapter Title',
                'attr' => [
                    'placeholder' => 'Chapter Title',
                    'class' => 'form-control',
                    ],
            ])
            ->add('content', CKEditorType::class, [
                'label' => false,
                'attr' => [
                    'data-controller' => 'ckeditor',
                ],
                'config' => ['height' => '60vh'],
            ])
            ->add('linkType', ChoiceType::class, [
                'label' => 'Link Type',
                'choices' => \array_flip($this->chapterLinkTypeManager->getChapterLinkTypes()),
                'attr' => [
                    'placeholder' => 'Link Type',
                    'class' => 'form-select',
                    ],
            ])
            ->add('chapterLinks', CollectionType::class, [
            'entry_type' => ChapterLinkType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => 'Liens du chapitre',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chapter::class,
        ]);
    }
}
