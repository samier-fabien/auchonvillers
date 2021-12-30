<?php

namespace App\Form;

use App\Entity\Events;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eve_begining', DateTimeType::class, [
                'label' => false,
                'date_widget' => 'single_text',
                /*'format' => 'yyyy-MM-dd',*/
                'data' => new \DateTime(),
            ])
            ->add('eve_end', DateTimeType::class, [
                'label' => false,
                'date_widget' => 'single_text',
                /*'format' => 'yyyy-MM-dd',*/
                'data' => new \DateTime(),
            ])
            ->add('eve_content_fr', CKEditorType::class, ['label' => false])
            ->add('eve_content_en', CKEditorType::class, ['label' => false])
            ->add('eve_location_osm', TextType::class, ['label' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
