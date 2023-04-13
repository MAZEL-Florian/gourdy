<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Sujet;
use App\Repository\CategorieRepository;
use DateTimeInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormTypeInterface;

class SujetType extends AbstractType
{
    private $dateCreation;

    public function __construct()
    {
        $this->dateCreation = new \DateTime('now');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'label' => 'Titre :',
                'attr' => [
                    'placeholder' => 'Titre'
                ]
            ])
            ->add('contenu', null, [
                'label' => 'Contenu :',
                'attr' => [
                    'placeholder' => 'Contenu'
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => "Catégories :", 
                'class' => Categorie::class,
                'multiple' => true,
                'by_reference' => false,
                'expanded' => true,
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sujet::class,
        ]);
    }
}
