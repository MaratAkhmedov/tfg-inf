<?php

namespace App\Form;

use App\Entity\Owner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OwnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dni', TextType::class, [
                'label' => 'owner.form.dni',
                'required' => true
            ])
            ->add('firstName', TextType::class, [
                'label' => 'owner.form.first_name',
                'required' => true
            ])
            ->add('lastName', TextType::class, [
                'label' => 'owner.form.last_name',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'owner.form.description',
                'required' => true
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Owner::class,
        ]);
    }
}
