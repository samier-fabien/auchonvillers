<?php

namespace App\Form;

use App\Entity\Events;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eve_begining', DateTimeType::class, [
                'date_widget' => 'single_text',
                /*'format' => 'yyyy-MM-dd',*/
                'data' => new \DateTime(),
            ])
            ->add('eve_end', DateTimeType::class, [
                'date_widget' => 'single_text',
                /*'format' => 'yyyy-MM-dd',*/
                'data' => new \DateTime(),
            ])
            ->add('eve_content_fr', CKEditorType::class, [])
            ->add('eve_content_en', CKEditorType::class, [])
            ->add('eve_location_osm')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
