<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class CategoryType extends AbstractType
{
    public const NAME_FR_LABEL = 'Nom en français';
    public const NAME_EN_LABEL = 'Nom en anglais';
    public const DESCRIPTION_FR_LABEL = 'Description en français';
    public const DESCRIPTION_EN_LABEL = 'Description en anglais';
    public const NUMBER_LABEL = 'Numéro d\'apparition';

    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cat_name_fr', TextType::class, [
                'label' => $this->translator->trans(self::NAME_FR_LABEL),
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                    ]),
                ]
            ])
            ->add('cat_name_en', TextType::class, [
                'label' => $this->translator->trans(self::NAME_EN_LABEL),
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                    ]),
                ]
            ])
            ->add('cat_description_fr', TextType::class, [
                'label' => $this->translator->trans(self::DESCRIPTION_FR_LABEL),
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('cat_description_en', TextType::class, [
                'label' => $this->translator->trans(self::DESCRIPTION_EN_LABEL),
            ])
            ->add('cat_order_of_appearance', NumberType::class, [
                'label' => $this->translator->trans(self::NUMBER_LABEL),
                'constraints' => [
                    new PositiveOrZero(),
                    new Length([
                        'max' => 11,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
