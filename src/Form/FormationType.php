<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prix', MoneyType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'choice_label' => 'nom', // Display module names instead of IDs
                'attr' => ['class' => 'form-control selectpicker'], // Add Bootstrap class and 'selectpicker' class for better styling
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
