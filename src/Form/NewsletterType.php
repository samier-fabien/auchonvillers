<?php

namespace App\Form;

use App\Entity\Newsletter;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewsletterType extends AbstractType
{
    public const CONTENT_FR_LABEL = 'Contenu en français';
    public const CONTENT_EN_LABEL = 'Contenu en anglais';

    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_content_fr', CKEditorType::class, [
                'label' => $this->translator->trans(self::CONTENT_FR_LABEL),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('new_content_en', CKEditorType::class, [
                'label' => $this->translator->trans(self::CONTENT_EN_LABEL),
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
