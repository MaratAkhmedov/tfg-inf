<?php

namespace App\Form;

use App\Entity\PropertyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $priceChoices = array_combine(range(200, 1600, 200), range(200, 1600, 200));
        $squareChoices = array_combine(range(20, 400, 20), range(20, 400, 20));

        $builder
            ->add('type', EntityType::class, [
                'label' => 'property.form.type',
                'class' => PropertyType::class,
                'choice_label' => 'label',
                'choice_translation_domain' => true,
                'required' => true
            ])
            ->add('priceMin', ChoiceType::class, [
                'required' => false,
                'choices' => $priceChoices,
                'placeholder' => 'min',
                'attr' => ['class' => 'price-min-selector']
            ])
            ->add('priceMax', ChoiceType::class, [
                'required' => false,
                'choices' => $priceChoices,
                'placeholder' => 'max',
                'attr' => ['class' => 'price-max-selector']
            ])
            ->add('squareMin', ChoiceType::class, [
                'required' => false,
                'choices' => $squareChoices,
                'placeholder' => 'min',
                'attr' => ['class' => 'square-min-selector']
            ])
            ->add('squareMax', ChoiceType::class, [
                'required' => false,
                'choices' => $squareChoices,
                'placeholder' => 'max',
                'attr' => ['class' => 'square-max-selector']
            ])
            ->add('search', SubmitType::class, ['label' => 'base.search']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
