<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Category;
use App\Repository\TagRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
                'required' => false
            ])
            ->add('dateOrder', ChoiceType::class, [
                'choices' => [
                    'Croissant' => true,
                    'Décroissant' => false,
                    'required' => false
                ],
                'mapped' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('tag', EntityType::class, [
                'class' =>Tag::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
                'required' => false

            ])
            ->add('send', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary m-3']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
