<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Action;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('act_begining', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
            ])
            ->add('act_end', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
            ])
            ->add('act_content_fr', CKEditorType::class, [])
            ->add('act_content_en', CKEditorType::class, [])
            // ->add('eve_location_osm', TextType::class, [
            //     'mapped' => false,
            // ])
        ;

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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Action::class,
        ]);
    }
}
