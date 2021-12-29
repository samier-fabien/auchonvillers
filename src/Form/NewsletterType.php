<?php

namespace App\Form;

use App\Entity\Newsletter;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterType extends AbstractType
{
        // $builder->add('event', CollectionType::class, [
        //     'entry_type' => EventType::class,
        //     'entry_options' => ['label' => false],
        //     'allow_add' => true,
        //     'allow_delete' => false,
        //     'by_reference' => false,
        // ]);

        // $builder
        //     ->add('event', EntityType::class, [
        //         'class' => Event::class,
        //         'mapped' => false,
        //         'choice_label' => 'eve_location_osm'
        //     ])
        // ;


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_content_fr', CKEditorType::class, [])
            ->add('new_content_en', CKEditorType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Newsletter::class,
        ]);
    }
}
