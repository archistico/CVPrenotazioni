<?php

namespace App\Form;

use App\Entity\Albergo;
use App\Entity\Porteur;
use App\Entity\Prenotazione;
use App\Entity\Tariffa;
use App\Entity\TipologiaOspitalita;
use App\Entity\TipologiaSistemazione;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PrenotazioneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fkPorteur', EntityType::class, [
                'class' => Porteur::class,
                'choice_label' => 'Descrizione',
                'label' => 'Porteur',
                'required' => true,
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('Pin', PasswordType::class, [
                'label' => 'Pin',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('Cliente', TextType::class, [
                'label' => 'Cliente', 
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Inserire il nome del cliente'
                ]
            ])
            ->add('Dal', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Dal', 
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])            
            ->add('Al', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Al', 
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])      
            ->add('PaxAdulti', IntegerType::class, [
                'label' => 'N° persone adulte', 
                'required' => true,
                'data' => 1,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 9,
                ]
            ])
            ->add('PaxBambini', IntegerType::class, [
                'label' => 'N° persone 5-14 anni', 
                'required' => true,
                'data' => 0,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 9,
                ]
            ])
            ->add('PaxAdolescenti', IntegerType::class, [
                'label' => 'N° persone >15 anni', 
                'required' => true,
                'data' => 0,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 9,
                ]
            ])
            ->add('fkTipologiaOspitalita', EntityType::class, [
                'class' => TipologiaOspitalita::class,
                'choice_label' => 'Descrizione',
                'label' => 'Tipologia ospitalità',
                'required' => true,
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('fkAlbergo', EntityType::class, [
                'class' => Albergo::class,
                'choice_label' => 'Descrizione',
                'label' => 'Albergo',
                'required' => true,
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('fkTipologiaSistemazione', EntityType::class, [
                'class' => TipologiaSistemazione::class,
                'choice_label' => 'Descrizione',
                'label' => 'Tipologia sistemazione',
                'required' => true,
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('fkTariffa', EntityType::class, [
                'class' => Tariffa::class,
                'choice_label' => 'Descrizione',
                'label' => 'Tariffa',
                'required' => true,
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('Note', TextareaType::class, [
                'label' => 'Note', 
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
                'data' => "",
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prenotazione::class,
        ]);
    }
}
