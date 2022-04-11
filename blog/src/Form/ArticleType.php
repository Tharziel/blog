<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un titre pour l\'article'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 75,
                        'minMessage' => 'Le titre de l\'article doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le titre de l\'article ne doit pas contenir plus de {{ limit }} caractères.'
                    ])
                ]
            ])
            ->add('content',TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un contenu'
                    ]),
                    new Length([
                        'min' => 30,
                        'minMessage' => 'Votre article doit contenir au moins {{ limit }} caractères.'
                        
                    ])
                ]
            ])
            ->add('isPublished',CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
