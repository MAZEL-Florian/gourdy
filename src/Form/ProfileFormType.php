<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileFormType extends AbstractType
{
    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'label' => 'Votre email : ',
                'disabled' => true
            ])
            ->add('pseudo', null, [
                'label' => 'Votre pseudo : '
            ])
            ->add('avatar', FileType::class, [
                'data_class' => null,
                'required' => false,
                'empty_data' => $this->user->getAvatar()
            ])
            ->add('signature', null, [
                'label' => 'Votre signature : '
            ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
