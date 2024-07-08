<?php

namespace App\Form;

use App\Entity\Equipment;
use App\Entity\PropertyType;
use App\Entity\Rule;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'data' => $options['currentPropertyType'],
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
            ->add('rooms', ChoiceType::class, [
                'label' => 'search.form.rooms',
                'required' => false,
                'choices' => [
                    "1" => 1,
                    "2" => 2,
                    "3" => 3,
                    "4 o más" => ">=4"
                ],
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'rooms']
            ])
            ->add('bathrooms', ChoiceType::class, [
                'label' => 'search.form.bathrooms',
                'required' => false,
                'choices' => [
                    "1" => 1,
                    "2" => 2,
                    "3 o más" => ">=3"
                ],
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'bathrooms']
            ])
            ->add('states', EntityType::class, [
                'label' => 'search.form.state',
                'class' => State::class,
                'choice_label' => 'label',
                'choice_translation_domain' => true,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('rules', EntityType::class, [
                'label' => 'search.form.rules',
                'class' => Rule::class,
                'choice_label' => 'label',
                'multiple' => true,
                'choice_translation_domain' => true,
                'expanded' => true,
                'required'   => false
            ])
            ->add('equipments', EntityType::class, [
                'label' => 'search.form.equipments',
                'class' => Equipment::class,
                'choice_label' => 'label',
                'multiple' => true,
                'choice_translation_domain' => true,
                'expanded' => true,
                'required'   => false
            ])
            ->add('search', SubmitType::class, ['label' => 'base.search']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'currentPropertyType' => null,
        ]);
    }
}
