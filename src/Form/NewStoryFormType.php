<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewStoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('storyGroup', StoryGroupType::class, [
                'label' => false, // To avoid having a "Story Group" label
            ])
            ->add('version', StoryVersionType::class, [
                'label' => false, // To avoid having a "Version" label
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // No data_class here, as this form is a wrapper
        ]);
    }
}
