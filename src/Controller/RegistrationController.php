<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\EmailType;
use App\Form\PreferencesType;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use App\Form\RegistrationEmailType;
use App\Repository\VotesRepository;
use App\Security\UserAuthenticator;
use Symfony\Component\Mime\Address;
use App\Form\RegistrationDeleteType;
use App\Repository\EventsRepository;
use App\Repository\ArticleRepository;
use App\Repository\AttendsRepository;
use App\Repository\BallotsRepository;
use App\Repository\MessageRepository;
use App\Repository\SurveysRepository;
use Symfony\Component\Form\FormError;
use App\Form\RegistrationPasswordType;
use App\Repository\OpinionsRepository;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegistrationPreferencesType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    public const CONFIRMATION_MAIL_DELAY = 10;// en minutes

    private EmailVerifier $emailVerifier;
    private $translator;
    private $userRepo;

    public function __construct(EmailVerifier $emailVerifier, TranslatorInterface $translator, UserRepository $userRepo)
    {
        $this->emailVerifier = $emailVerifier;
        $this->translator = $translator;
        $this->userRepo = $userRepo;
    }

    /**
     * @Route("/{locale}/inscription", name="app_register")
     */
    public function register(string $locale, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr');
            return $this->redirect("/");
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Si les deux mots de passe diffèrent
        if ($form->get('plainPassword')->getData() !== $form->get('confirmPassword')->getData()) {
            $form->get('confirmPassword')->addError(new FormError('Les deux mots de passe doivent être identiques.'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new DateTime())
                ->setLastModification(new DateTime())
                ->setRoles(['ROLE_USER'])
                // encode the plain password
                ->setPassword(
                    $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            // $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            //     (new TemplatedEmail())
            //         ->from(new Address('samierfabien@gmail.com', $this->translator->trans('Le site web d\'Auchonvillers')))
            //         ->to($user->getEmail())
            //         ->subject($this->translator->trans('Veuillez confirmer votre email.'))
            //         ->htmlTemplate('registration/confirmation_email.html.twig')
            // );

            // Si erreur lors de l'envoi de l'email
            if (!$this->sendConfirmation($user))
            {
                $this->addFlash('danger', $this->translator->trans('Une erreur est survenue lors de l\'envoi d\'un email de confirmation, veuillez recommencer.'));
                return $this->redirectToRoute('app_register', [
                    'locale' => $locale,
                ]);
            }

            // Si tout ok, ajout message plus authentification
            $this->addFlash('success', $this->translator->trans('Un email de confirmation vous a été envoyé, cliquez dessus pour accéder à toutes les fonctionnalités du site internet.'));

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('danger', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', $this->translator->trans('Votre e-mail a été vérifié.'));

        return $this->redirectToRoute('home', [
            'locale' => 'fr',
        ]);
    }

    /**
     * @Route("/{locale}/membre/compte", name="app_show")
     */
    public function show(string $locale, Request $request): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr');
            return $this->redirect("/");
        }

        $user = $this->userRepo->findOneBy(["id" => $this->getUser()->getId()]);

        $roles = [];
        foreach ($user->getRoles() as $key => $value) {
            switch ($value) {
                case 'ROLE_USER': $roles[$key] = 'membre';
                    break;

                case 'ROLE_AGENT': $roles[$key] = 'agent';
                    break;

                case 'ROLE_MAYOR': $roles[$key] = 'maire';
                break;

                case 'ROLE_ADMIN': $roles[$key] = 'administrateur';
                    break;
            }
        }

        $userDatas = [
            'email' => $user->getEmail(),
            'roles' => $roles,
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'newsletter' => $user->getNewsletter(),
            'vote' => $user->getVote(),
            'event' => $user->getEvent(),
            'survey' => $user->getSurvey(),
        ];

        return $this->render('registration/show.html.twig', [
            'user' => $userDatas,
        ]);
    }

    /**
     * Modifier préférences :
     * >> clique sur modifier les préférences
     * << affiche le formulaire
     * >> renseigne le formulaire
     * << enregistre les données
     */

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{locale}/membre/compte/preferences", name="app_preferences")
     */
    public function editPreferences(string $locale = 'fr', Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr');
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('app_show', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        // On va chercher l'utilisateur a modifier
        $user = $this->userRepo->findOneBy(["id" => $this->getUser()->getId()]);

        $form = $this->createForm(RegistrationPreferencesType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setLastModification(new DateTime());

            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('Vos préférences ont été mises à jour'));

            return $this->redirectToRoute('app_show', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        return $this->render('registration/edit_preferences.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * Modifier mot de passe :
     * >> clique sur modifier mot de passe
     * << affiche formulaire
     * >> renseigne le champs nouveau mot de passe + clique sur modifier
     * << modifie le mot de passe dans la bdd + isverified = false + envoie mail de confirmation
     * >> clique sur le lien de confirmation
     * << isverified = true
     *
     */

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{locale}/membre/compte/mot_de_passe", name="app_password")
     */
    public function editPassword(string $locale = 'fr', Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr');
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('app_show', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        // On va chercher l'utilisateur a modifier
        $user = $this->userRepo->findOneBy(["id" => $this->getUser()->getId()]);

        $form = $this->createForm(RegistrationPasswordType::class, $user);
        $form->handleRequest($request);

        // Si les deux mots de passe diffèrent
        if ($form->get('plainPassword')->getData() !== $form->get('confirmPassword')->getData()) {
            $form->get('confirmPassword')->addError(new FormError($this->translator->trans('Les deux mots de passe doivent être identiques.')));
        }

        if ($form->isSubmitted() && $form->isValid()) {

            // On encode le mot de passe et on annule la vérification du compte pour obliger l'utilisateur à cliquer sur le lien de confirmation
            $user->setIsVerified(false)
                ->setLastModification(new DateTime())
                // encode the plain password
                ->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

            if (!$this->sendConfirmation($user))
            {
                $this->addFlash('danger', $this->translator->trans('Une erreur est survenue lors de l\'envoi d\'un email de confirmation, veuillez recommencer.'));
                return $this->redirectToRoute('app_password', [
                    'locale' => $locale,
                ]);
            }

            $this->addFlash('notice', $this->translator->trans('Un email de confirmation vous a été envoyé, cliquez dessus pour accéder à toutes les fonctionnalités du site internet.'));

            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('Votre mot de passe a été mis à jour.'));

            return $this->redirectToRoute('app_show', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        return $this->render('registration/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * Modifier email :
     * >> clique sur modifier email
     * << affiche le champs nouvel email
     * >> renseigne le champs nouvel email + clique sur modifier
     * << modifie email + isverified = false + envoie mail de confirmation
     * >> clique sur le lien de confirmation
     * << isverified = true
     */

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{locale}/membre/compte/email", name="app_email")
     */
    public function editEmail(string $locale = 'fr', Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr');
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('app_show', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        // On va chercher l'utilisateur a modifier
        $user = $this->userRepo->findOneBy(["id" => $this->getUser()->getId()]);

        $form = $this->createForm(RegistrationEmailType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On annule la vérification du compte pour obliger l'utilisateur à cliquer sur le lien de confirmation
            $user->setIsVerified(false)
                ->setLastModification(new DateTime());

            // Envoi d'email de confirmation
            // $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            //     (new TemplatedEmail())
            //         ->from(new Address('samierfabien@gmail.com', $this->translator->trans('Le site web d\'Auchonvillers')))
            //         ->to($user->getEmail())
            //         ->subject($this->translator->trans('Veuillez confirmer votre email.'))
            //         ->htmlTemplate('registration/confirmation_email.html.twig')
            // );
            if (!$this->sendConfirmation($user))
            {
                $this->addFlash('danger', $this->translator->trans('Une erreur est survenue lors de l\'envoi d\'un email de confirmation, veuillez recommencer.'));
                return $this->redirectToRoute('app_email', [
                    'locale' => $locale,
                ]);
            }

            $this->addFlash('notice', $this->translator->trans('Un email de confirmation vous a été envoyé, cliquez dessus pour accéder à toutes les fonctionnalités du site internet.'));

            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('Votre email a été mis à jour.'));

            return $this->redirectToRoute('app_show', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        return $this->render('registration/edit_email.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * Suppression compte :
     * >> clique sur supprimer compte
     * << affiche fenêtre de confirmation
     * >> clique sur supprimer
     * << si maire : impossible
     *  sinon : recherche dans les tables [article, events, surveys, votes, newsletter] ou "table".user_id = user.id et changement en user.id where user.roles = mayor
     *      + recherche dans les tables [attends, opinions, ballots] ou "table".user_id = user.id et suppression de la ligne
     *      + recherche dans chat ou chat.user_id = user.id : pour chaque ligne, supprimer message ou message.chat_id = chat.chat_id
     *      + recherche dans message ou message.user_id = user.user_id : pour chaque ligne, modifier message.user_id par celui du maire
     *      + supprimer user ou user.id = "current user".id
     *  si user : recherche dans les tables [attends, opinions, ballots] ou "table".user_id = user.id et suppression de la ligne
     *      + recherche dans chat ou chat.user_id = user.id : pour chaque ligne, supprimer message ou message.chat_id = chat.chat_id
     *      + recherche dans message ou message.user_id = user.user_id : pour chaque ligne, modifier message.user_id par celui du maire
     *      + supprimer user ou user.id = "current user".id
     */

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{locale}/membre/compte/supprimer", name="app_delete")
     */
    public function delete(string $locale = 'fr', Request $request, ManagerRegistry $doctrine, AttendsRepository $attendsRepo, BallotsRepository $ballotsRepo, OpinionsRepository $opinionsRepo, ArticleRepository $articleRepo, EventsRepository $eventsRepo, SurveysRepository $surveysRepo, VotesRepository $votesRepo, NewsletterRepository $newsletterRepo, ChatRepository $chatRepo, MessageRepository $messageRepo): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr');
            return $this->redirect("/");
        }

        // Si l'utilisateur est le maire on redirige avec un message suppression impossible
        if (in_array('ROLE_MAYOR', $this->getUser()->getRoles(), true)) {
            $this->addFlash('notice', $this->translator->trans('Le compte du maire ne peut pas être supprimé. Il peut seulement être transmis.'));

            return $this->redirectToRoute('app_show', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        $form = $this->createForm(RegistrationDeleteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On trouve le maire
            $mayorId = $this->userRepo->findOneByRole('ROLE_MAYOR');

            // I SUPPRESSION DES DONNEES UTILISATEUR
            // 1-recherche dans chat, pour chaque chat créé par l'utilisateur on supprime les messages. Ensuite on supprime le chat.
            foreach ($chatRepo->findBy(['user' => $this->getUser()->getId()]) as $key1 => $chat) {
                $messages = $messageRepo->findBy([
                    'user' => $this->getUser()->getId(),
                    'chat' => $chat->getId(),
                ]);
                foreach ($messages as $key2 => $message)
                {
                    $doctrine->getManager()->remove($message);
                }
                $doctrine->getManager()->remove($chat);
            }

            // 2-recherche dans les tables [attends, opinions, ballots], pour chaque ligne créée par l'utilisateur, suppression de la ligne
            foreach ($attendsRepo->findBy(['user' => $this->getUser()->getId()]) as $key3 => $attend) {
                $doctrine->getManager()->remove($attend);
            }
            foreach ($ballotsRepo->findBy(['user' => $this->getUser()->getId()]) as $key4 => $ballot) {
                $doctrine->getManager()->remove($ballot);
            }
            foreach ($opinionsRepo->findBy(['user' => $this->getUser()->getId()]) as $key5 => $opinion) {
                $doctrine->getManager()->remove($opinion);
            }

            // II MODIFICATION DES DONNEES CREEES PAR LES AGENTS
            // 1-recherche dans les tables [article, events, surveys, votes, newsletter], pour chaque ligne créée par l'utilisateur, modifier user = maire
            foreach ($articleRepo->findBy(['user' => $this->getUser()->getId()]) as $key6 => $article) {
                $article->setUser($mayorId);
                $doctrine->getManager()->persist($article);
            }
            foreach ($eventsRepo->findBy(['user' => $this->getUser()->getId()]) as $key7 => $event) {
                $event->setUser($mayorId);
                $doctrine->getManager()->persist($event);
            }
            foreach ($surveysRepo->findBy(['user' => $this->getUser()->getId()]) as $key8 => $survey) {
                $survey->setUser($mayorId);
                $doctrine->getManager()->persist($survey);
            }
            foreach ($votesRepo->findBy(['user' => $this->getUser()->getId()]) as $key9 => $vote) {
                $vote->setUser($mayorId);
                $doctrine->getManager()->persist($vote);
            }
            foreach ($newsletterRepo->findBy(['user' => $this->getUser()->getId()]) as $key10 => $newsletter) {
                $newsletter->setUser($mayorId);
                $doctrine->getManager()->persist($newsletter);
            }

            $doctrine->getManager()->flush();

            // 2-recherche dans message, pour chaque ligne créée par l'utilisateur, modifier user = maire
            foreach ($messageRepo->findBy(['user' => $this->getUser()->getId()]) as $key11 => $message) {
                $message->setUser($mayorId);
                $doctrine->getManager()->persist($message);
            }

            $doctrine->getManager()->flush();

            // III SUPPRESSION UTILISATEUR
            $user = $this->getUser();
            $this->container->get('security.token_storage')->setToken(null);

            $doctrine->getManager()->remove($user);
            $doctrine->getManager()->flush();

            // Ceci ne fonctionne pas avec la création d'une nouvelle session !
            $this->addFlash('success', 'Votre compte utilisateur a bien été supprimé !'); 

            return $this->redirectToRoute('home', [
                'locale'=> $locale,
            ]);
        }

        return $this->render('registration/delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{locale}/membre/compte/renvoyer_confirmation", name="app_renvoyer_confirmation")
     */
    public function renvoyerConfirmation(string $locale = 'fr', Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr');
            return $this->redirect("/");
        }

        // Si l'utilisateur est vérifié, redirection
        if ($this->getUser()->isVerified()) {
            $this->addFlash('notice', $this->translator->trans('Cete procédure est réservée aux personnes dont l\'email doit être vérifié.'));
            return $this->redirectToRoute('home', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        // Calcul du temps depuis envoi dernier mail de confirmation
        $user = $this->userRepo->findOneBy(['id' => $this->getUser()->getId()]);
        $interval = $user->getLastModification()->diff(new DateTime());
        $intervalInMinutes = $interval->format('%R%i');

        // Si < 10 minutes, redirection shoow avec message
        if ($intervalInMinutes < self::CONFIRMATION_MAIL_DELAY) {
            //dd("moins d'une heure : $intervalInMinutes minutes");
            $this->addFlash('warning', $this->translator->trans('Un email de confirmation ne peut être envoyé que toutes les dix minutes. Le dernier date d\'il y a') . ' ' . $intervalInMinutes . ' ' . $this->translator->trans('minutes.'));

            return $this->redirectToRoute('app_show', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        if (!$this->sendConfirmation($this->getUser())) {
            $this->addFlash('danger', $this->translator->trans('Une erreur est survenue lors de l\'envoi d\'un email de confirmation, veuillez recommencer.'));
            
            return $this->redirectToRoute('app_show', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        $user->setLastModification(new DateTime());

        $doctrine->getManager()->persist($user);
        $doctrine->getManager()->flush();

        $this->addFlash('success', $this->translator->trans('Un email de confirmation vous a été envoyé, cliquez dessus pour accéder à toutes les fonctionnalités du site internet.'));

        return $this->redirectToRoute('app_show', [
            'locale'=> $request->getSession()->get('_locale'),
        ]);
    }


    public function sendConfirmation(User $user)
    {
        return $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('samierfabien@gmail.com', $this->translator->trans('Le site web d\'Auchonvillers')))
                ->to($user->getEmail())
                ->subject($this->translator->trans('Veuillez confirmer votre email.'))
                ->htmlTemplate('registration/confirmation_email.html.twig'))
        ;
    }


}
