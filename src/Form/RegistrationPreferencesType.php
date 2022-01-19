<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationPreferencesType extends AbstractType
{
    const FIRST_NAME_LABEL = 'Nom';
    const LAST_NAME_LABEL = 'Prénom';
    const NEWSLETTER_LABEL = 'Recevoir les actualités par email';
    const VOTE_LABEL = 'Recevoir un email quand publication d\'un nouveau vote';
    const EVENT_LABEL = 'Recevoir un email quand publication d\'un nouvel évènement';
    const SURVEY_LABEL = 'Recevoir un email quand publication d\'une nouvelle enquête';
    const CHOICE_TRUE = 'oui';
    const CHOICE_FALSE = 'non';

    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => $this->translator->trans(self::FIRST_NAME_LABEL),
                'constraints' => [
                    new Length([
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => $this->translator->trans(self::LAST_NAME_LABEL),
                'constraints' => [
                    new Length([
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('newsletter', ChoiceType::class, [
                'label' => $this->translator->trans(self::NEWSLETTER_LABEL),
                'choices' => [
                    self::CHOICE_TRUE => 1,
                    self::CHOICE_FALSE => 0,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('vote', ChoiceType::class, [
                'label' => $this->translator->trans(self::NEWSLETTER_LABEL),
                'choices' => [
                    self::CHOICE_TRUE => 1,
                    self::CHOICE_FALSE => 0,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('event', ChoiceType::class, [
                'label' => $this->translator->trans(self::NEWSLETTER_LABEL),
                'choices' => [
                    self::CHOICE_TRUE => 1,
                    self::CHOICE_FALSE => 0,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('survey', ChoiceType::class, [
                'label' => $this->translator->trans(self::NEWSLETTER_LABEL),
                'choices' => [
                    self::CHOICE_TRUE => 1,
                    self::CHOICE_FALSE => 0,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
