<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Critique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class CritiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('category' ,EntityType::class, [
                'class'=> Category::class,
                'choice_label' => 'title'
            ])
            ->add('content')
            ->add('image', FileType::class, [
                'label' => 'Insérer l\'image',
                'data_class' => null,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Critique::class,
        ]);
    }
}
