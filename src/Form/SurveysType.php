<?php

namespace App\Form;

use App\Entity\Surveys;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SurveysType extends AbstractType
{
    public const BEGINING_LABEL = 'Date du début de l\'évènement';
    public const END_LABEL = 'Date de fin de l\'évènement';
    public const CONTENT_FR_LABEL = 'Contenu en français';
    public const CONTENT_EN_LABEL = 'Contenu en anglais';
    public const QUESTION_FR_LABEL = 'Question en français';
    public const QUESTION_EN_LABEL = 'Question en anglais';
    
    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sur_begining', DateTimeType::class, [
                'label' => $this->translator->trans(self::BEGINING_LABEL),
                'date_widget' => 'single_text',
                'data' => new \DateTime(),
                'constraints' => [
                    new DateTime(),
                ],
            ])
            ->add('sur_end', DateTimeType::class, [
                'label' => $this->translator->trans(self::END_LABEL),
                'date_widget' => 'single_text',
                'data' => new \DateTime(),
                'constraints' => [
                    new DateTime(),
                ],
            ])
            ->add('sur_content_fr', CKEditorType::class, [
                'label' => $this->translator->trans(self::CONTENT_FR_LABEL),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('sur_content_en', CKEditorType::class, [
                'label' => $this->translator->trans(self::CONTENT_EN_LABEL),
            ])
            ->add('sur_question_fr', TextType::class, [
                'label' => $this->translator->trans(self::QUESTION_FR_LABEL),
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                    ]),
                ]
            ])
            ->add('sur_question_en', TextType::class, [
                'label' => $this->translator->trans(self::QUESTION_EN_LABEL),
                'constraints' => [
                    new Length([
                        'max' => 255,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Surveys::class,
        ]);
    }
}
