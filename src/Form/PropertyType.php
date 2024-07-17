<?php

namespace App\Form;

use App\Entity\Property;
use App\Entity\PropertyType as EntityPropertyType;
use App\Entity\Rule;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        switch ($options['flow_step']) {
            case 1:
                $builder
                    ->add('type', EntityType::class, [
                        'label' => 'property.form.type',
                        'class' => EntityPropertyType::class,
                        'choice_label' => 'label',
                        'choice_translation_domain' => true,
                        'required' => true,
                        'placeholder' => 'property.form.type_placeholder'
                    ])
                    ->add('name', TextType::class, [
                        'label' => 'property.form.title',
                        'required'   => true,
                    ])
                    ->add('shortDescription', TextareaType::class, [
                        'label' => 'property.form.short_description',
                        'required'   => true,
                    ])
                    ->add('description', TextareaType::class, [
                        'label' => 'property.form.description',
                        'required'   => false,
                    ]);
                break;
            case 2:
                $builder->add('address', AddressType::class);
                break;
            case 3:
                $builder
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
                    ]);
                break;
            case 4:
                $builder->add('room', RoomType::class, [
                    'label' => 'property.form.room',
                    'required'   => false
                ]);
                break;
            case 5:  // map not used in form  
                $test = 10;
                $builder
                    ->add('images', FileType::class, [
                        'label' => 'Upload Files',
                        'multiple' => true,
                        'mapped' => false,
                        'required' => true,
                        'constraints' => [
                            new File([
                                'maxSize' => '5096k',
                                'mimeTypes' => [
                                    'image/*',
                                ],
                                'mimeTypesMessage' => 'Please upload a valid Image file',
                            ])
                        ],
                    ]);
                break;
        };
    }

    public function getBlockPrefix()
    {
        return 'property';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
