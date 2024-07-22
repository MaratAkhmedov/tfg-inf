<?php

namespace App\Form;

use App\Entity\PropertyType;
use App\Entity\Rule;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SearchPropertyType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator){}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $priceChoices = array_combine(
            array_map(
                fn ($key) => $key . ' €',
                range(200, 1600, 200)
            ),
            range(200, 1600, 200)
        );

        $squareChoices = array_combine(
            array_map(
                fn ($key) => $key . ' m²',
                range(20, 400, 20)
            ),
            range(20, 400, 20)
        );

        $orMore = $this->translator->trans('search.form.or_more');

        $builder
            ->add('type', EntityType::class, [
                'label' => 'property.form.type',
                'class' => PropertyType::class,
                'data' => $options['currentPropertyType'] ?? null,
                'choice_label' => 'label',
                'choice_translation_domain' => true,
                'required' => false,
                'placeholder' => 'search.form.type_placeholder',
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
                    "4 $orMore" => ">=4"
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
                    "3 $orMore" => ">=3"
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
            ->add('search', SubmitType::class, ['label' => 'base.search']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'currentPropertyType' => null,
        ]);
    }
}
