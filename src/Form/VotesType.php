<?php

namespace App\Form;

use App\Entity\Votes;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class VotesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vot_begining', DateTimeType::class, [
                'label' => false,
                'date_widget' => 'single_text',
                'data' => new \DateTime(),
            ])
            ->add('vot_end', DateTimeType::class, [
                'label' => false,
                'date_widget' => 'single_text',
                'data' => new \DateTime(),
            ])
            ->add('vot_content_fr', CKEditorType::class, ['label' => false])
            ->add('vot_content_en', CKEditorType::class, ['label' => false])
            ->add('vot_question_fr', TextType::class, ['label' => false])
            ->add('vot_question_en', TextType::class, ['label' => false])
            ->add('vot_first_choice_fr', TextType::class, ['label' => false])
            ->add('vot_first_choice_en', TextType::class, ['label' => false])
            ->add('vot_second_choice_fr', TextType::class, ['label' => false])
            ->add('vot_second_choice_en', TextType::class, ['label' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Votes::class,
        ]);
    }
}
