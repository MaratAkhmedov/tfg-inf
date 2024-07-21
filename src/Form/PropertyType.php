<?php

namespace App\Form;

use App\Entity\AttributeProperty;
use App\Entity\Property;
use App\Entity\PropertyType as EntityPropertyType;
use App\Entity\Rule;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            ->add('floor', IntegerType::class, [
                'label' => 'property.form.floor',
                'required'   => false
            ])
            ->add('lastFloor', CheckboxType::class, [
                'label' => 'property.form.last_floor',
                'required'   => false
            ])
            ->add('square', NumberType::class, [
                'label' => 'property.form.square',
                'required'   => false
            ])
            ->add('type', EntityType::class, [
                'label' => 'property.form.type',
                'class' => EntityPropertyType::class,
                'choice_label' => 'label',
                'choice_translation_domain' => true,
                'required' => true,
                'placeholder' => 'property.form.type_placeholder'
            ])
            ->add('state', EntityType::class, [
                'label' => 'property.form.state',
                'class' => State::class,
                'choice_label' => 'label',
                'choice_translation_domain' => true
            ])
            ->add('rules', EntityType::class, [
                'label' => 'property.form.rules',
                'class' => Rule::class,
                'choice_label' => 'label',
                'multiple' => true,
                'choice_translation_domain' => true,
                'expanded' => true,
                'required'   => false
            ])
            ->add('attributeProperties', EntityType::class, [
                'label' => 'property.form.attributes_label',
                'class' => AttributeProperty::class,
                'choice_label' => 'label',
                'multiple' => true,
                'choice_translation_domain' => true,
                'expanded' => true,
                'required'   => false
            ])
            ->add('room', RoomType::class, [
                'label' => 'property.form.room',
                'required'   => false
            ])
            // FIXME: fix problem with address type
            ->add('address', TextType::class, [
                'mapped' => false,
                'label' => 'property.form.address',
                'required'   => true
            ])
            ->add('mapPlaceId', HiddenType::class, [
                'mapped' => false
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
