<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('art_content_fr', CKEditorType::class, ['label' => false])
            ->add('art_content_en', CKEditorType::class, ['label' => false])
            ->add('art_order_of_appearance', NumberType::class, ['label' => false])
            ->add('art_title_fr', TextType::class, ['label' => false])
            ->add('art_title_en', TextType::class, ['label' => false])
            ->add('category', EntityType::class, [
                'label' => false,
                'class' => Category::class,
                'choice_label' => 'cat_name_fr',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
