<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NoSuspiciousCharacters;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'attr' => [
                    'placeholder' => 'Pseudo',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-2',
                ],
                'constraints' => [
                    new NoSuspiciousCharacters(),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]*$/',
                        'message' => 'Votre pseudo doit contenir uniquement des caractères alphanumériques.',
                    ]),
                    new NotBlank([
                        'message' => 'Vous devez choisir un pseudo',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre pseudo ne peut pas faire moins de {{ limit }} caractères',
                        'max' => 16,
                        'maxMessage' => 'Votre pseudo ne peut pas faire plus de {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-2',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez saisir un email',
                    ]),
                    new Email([
                        'message' => 'Vous devez saisir un email valide',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Mot de passe',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Mot de passe',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-2',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new NotCompromisedPassword([
                        'message' => 'Ce mot de passe est connu pour avoir été diffusé sur internet',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
