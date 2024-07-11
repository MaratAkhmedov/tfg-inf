<?php

namespace App\Form;

use App\Entity\AttributeRoom;
use App\Entity\Room;
use App\Enum\BedType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attributeRooms', EntityType::class, [
                'label' => 'room.form.attributes_label',
                'class' => AttributeRoom::class,
                'choice_label' => 'label',
                'multiple' => true,
                'choice_translation_domain' => true,
                'expanded' => true,
                'required'   => false
            ])
            ->add('bedType', EnumType::class, [
                'label' => 'room.form.bed_type',
                'class' => BedType::class,
                'placeholder' => false,
                // 'choice_label' => 'label',
                // 'multiple' => true,
                // 'choice_translation_domain' => true,
                // 'expanded' => true,
                'required'   => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
