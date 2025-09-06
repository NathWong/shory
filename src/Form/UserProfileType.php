<?php

namespace App\Form;

use App\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', type\TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lastName', type\TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('penName', type\TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('avatar', type\FileType::class, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '2048k',
                    )
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
