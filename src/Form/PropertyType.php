<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Equipment;
use App\Entity\Property;
use App\Entity\Rule;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'property.form.title',
                'required'   => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'property.form.description',
                'required'   => false,
            ])
            ->add('price', MoneyType::class, [
                'label' => 'property.form.price',
            ])
            ->add('numBathrooms', IntegerType::class, [
                'label' => 'property.form.num_bathrooms',
                'required'   => false
            ])
            ->add('numRooms', IntegerType::class, [
                'label' => 'property.form.num_rooms',
                'required'   => false
            ])
            ->add('maxPersons', IntegerType::class, [
                'label' => 'property.form.max_persons',
                'required'   => false
            ])
            ->add('floor', IntegerType::class, [
                'label' => 'property.form.floor',
                'required'   => false
            ])
            ->add('lastPlant', CheckboxType::class, [
                'label' => 'property.form.last_plant',
                'required'   => false
            ])
            ->add('square', NumberType::class, [
                'label' => 'property.form.square',
                'required'   => false
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'property.form.type',
                'required'   => true,
                'choices' => [
                    'property.type.room' => 'room',
                    'property.type.flat' => 'flat',
                ],
            ])
            ->add('state', EntityType::class, [
                'class' => State::class,
                'choice_label' => 'name',
                'choice_translation_domain' => true
            ])
            ->add('equipments', EntityType::class, [
                'class' => Equipment::class,
                'choice_label' => 'name',
                'multiple' => true,
                'choice_translation_domain' => true,
                'multiple' => true,
                'expanded' => true,
                'required'   => false
            ])
            ->add('rules', EntityType::class, [
                'class' => Rule::class,
                'choice_label' => 'name',
                'multiple' => true,
                'choice_translation_domain' => true,
                'multiple' => true,
                'expanded' => true,
                'required'   => false
            ])
            ->add('address', EntityType::class, [
                'class' => Address::class,
                'choice_label' => 'name',
                'required'   => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
