<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\RoleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('role', EnumType::class, [
                'mapped' => false,
                'label' => 'user.form.role_title',
                'class' => RoleType::class,
                'required'   => true,
                //'multiple' => false,
                //'expanded' => true,
            ])
            ->add('ownerData', OwnerType::class, [
                'label' => 'user.form.owner',
                'required' => false
            ])
            
        ;

        if(!$options['isEdit']) {
            $builder->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ]),
                ],
            ]);

            $builder->add('register', SubmitType::class, [
                'label' => 'user.form.create'
            ]);
        }else{
            $builder->add('register', SubmitType::class, [
                'label' => 'user.form.update'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'isEdit' => false,
        ]);
    }
}
