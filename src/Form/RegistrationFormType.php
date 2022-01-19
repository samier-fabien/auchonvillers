<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public const EMAIL_LABEL = 'Veuillez entrer votre email s\'il vous plait.';
    public const PASSWORD_LABEL = 'Veuillez entrer votre mot de passe s\'il vous plait.';
    public const CONFIRM_PASSWORD_LABEL = 'Veuillez entrer une deuxième fois votre mot de passe s\'il vous plait.';
    public const RGPD_LABEL = 'En cochant ceci, vous acceptez nos conditions quant à l\'utilisation de vos données.';
    public const TERMS_OF_USE_LABEL = 'En cochant ceci, vous acceptez nos conditions générales d\'utilisation.';
    public const NEWSLETTER_LABEL = 'Cochez si vous voulez recevoir les actualités par email.';
    public const EVENT_LABEL = 'Cochez si vous voulez recevoir des notifications par email quand de nouveaux évènements sont publiés.';
    public const SURVEY_LABEL = 'Cochez si vous voulez recevoir des notifications par email quand de nouvelles enquêtes sont publiés.';
    public const VOTE_LABEL = 'Cochez si vous voulez recevoir des notifications par email quand de nouveaux votes sont publiés.';

    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => $this->translator->trans(self::EMAIL_LABEL),
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => $this->translator->trans(self::PASSWORD_LABEL),
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 6,
                        'minMessage' => $this->translator->trans('Votre mot de passe doit être composé d\'au moins {{ limit }} caractères.'),//Your password should be at least {{ limit }} characters
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'mapped' => false,
                'label' => $this->translator->trans(self::CONFIRM_PASSWORD_LABEL),
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 6,
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('rgpd', CheckboxType::class, [
                'label' => $this->translator->trans(self::RGPD_LABEL),
                'constraints' => [
                    new IsTrue(),
                ],
            ])
            ->add('user_terms_of_use', CheckboxType::class, [
                'label' => $this->translator->trans(self::TERMS_OF_USE_LABEL),
                'constraints' => [
                    new IsTrue(),
                ],
            ])
            ->add('newsletter', CheckboxType::class, [
                'label' => $this->translator->trans(self::NEWSLETTER_LABEL),
            ])
            ->add('event', CheckboxType::class, [
                'label' => $this->translator->trans(self::EVENT_LABEL),
            ])
            ->add('survey', CheckboxType::class, [
                'label' => $this->translator->trans(self::SURVEY_LABEL),
            ])
            ->add('vote', CheckboxType::class, [
                'label' => $this->translator->trans(self::VOTE_LABEL),
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
