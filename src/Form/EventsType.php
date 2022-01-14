<?php

namespace App\Form;

use App\Entity\Events;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventsType extends AbstractType
{
    public const BEGINING_LABEL = 'Date du début de l\'évènement';
    public const END_LABEL = 'Date de fin de l\'évènement';
    public const CONTENT_FR_LABEL = 'Contenu en français';
    public const CONTENT_EN_LABEL = 'Contenu en anglais';
    public const LOCATION_LABEL = 'Longitude et latitude pour openstreetmap';

    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eve_begining', DateTimeType::class, [
                'label' => $this->translator->trans(self::BEGINING_LABEL),
                'date_widget' => 'single_text',
                'data' => new \DateTime(),
            ])
            ->add('eve_end', DateTimeType::class, [
                'label' => $this->translator->trans(self::END_LABEL),
                'date_widget' => 'single_text',
                'data' => new \DateTime(),
            ])
            ->add('eve_content_fr', CKEditorType::class, [
                'label' => $this->translator->trans(self::CONTENT_FR_LABEL),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('eve_content_en', CKEditorType::class, [
                'label' => $this->translator->trans(self::CONTENT_EN_LABEL),
            ])
            ->add('eve_location_osm', TextType::class, [
                'label' => $this->translator->trans(self::LOCATION_LABEL),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
