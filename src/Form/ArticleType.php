<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\Request;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;

class ArticleType extends AbstractType
{
    public const TITLE_FR_LABEL = 'Titre en français.';
    public const TITLE_EN_LABEL = 'Titre en anglais.';
    public const CONTENT_FR_LABEL = 'Contenu en français.';
    public const CONTENT_EN_LABEL = 'Contenu en anglais.';   
    public const NUMBER_LABEL = 'Numéro d\'apparition.';
    public const CATEGORY_LABEL = 'Catégorie associée à l\'article.';

    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('art_title_fr', TextType::class, [
                'label' => $this->translator->trans(self::TITLE_FR_LABEL),
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                    ]),
                ]
            ])
            ->add('art_title_en', TextType::class, [
                'label' => $this->translator->trans(self::TITLE_EN_LABEL),
                'constraints' => [
                    new Length([
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('art_content_fr', CKEditorType::class, [
                'label' => $this->translator->trans(self::CONTENT_FR_LABEL),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('art_content_en', CKEditorType::class, [
                'label' => $this->translator->trans(self::CONTENT_EN_LABEL),
            ])
            ->add('art_order_of_appearance', NumberType::class, [
                'label' => $this->translator->trans(self::NUMBER_LABEL),
                'constraints' => [
                    new PositiveOrZero(),
                    new Length([
                        'max' => 11,
                    ]),
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => $this->translator->trans(self::CATEGORY_LABEL),
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
