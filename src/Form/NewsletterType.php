<?php

namespace App\Form;

use App\Entity\Newsletter;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_content_fr', CKEditorType::class, [
                'help' => "Le contenu de la nouvelle en français",
                'config_name' => 'main_config'
            ])
            ->add('new_content_en', CKEditorType::class, [
                'help' => "Le contenu de la nouvelle en anglais"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Newsletter::class,
        ]);
    }
}
