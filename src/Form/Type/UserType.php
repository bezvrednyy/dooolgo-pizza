<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'field',
                    'placeholder' => 'FCS',
                    'maxlength' => '255',
                    'autofocus' => 'autofocus'
                ],
                'label' => false
            ])
            ->add('email',  EmailType::class, [
                'attr' => [
                    'class' => 'field',
                    'placeholder' => 'email',
                    'maxlength' => '255'
                ],
                'label' => false
            ])
            ->add('password',  PasswordType::class, [
                'attr' => [
                    'class' => 'field',
                    'placeholder' => 'password',
                    'maxlength' => '255'
                ],
                'label' => false
            ])
            ->add('address',  TextType::class, [
                'attr' => [
                    'class' => 'field',
                    'placeholder' => 'address',
                    'maxlength' => '255'
                ],
                'label' => false
            ])
            ->add('registration', SubmitType::class, [
                'attr' => [
                    'class' => 'submit'
                ],
                'label' => 'signUp'
            ])
        ;
    }
}