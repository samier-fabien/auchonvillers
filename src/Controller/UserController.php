<?php

namespace App\Controller;

use App\Service\RolesChecker;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use App\Repository\VotesRepository;
use App\Repository\EventsRepository;
use App\Repository\ArticleRepository;
use App\Repository\AttendsRepository;
use App\Repository\BallotsRepository;
use App\Repository\MessageRepository;
use App\Repository\SurveysRepository;
use App\Repository\OpinionsRepository;
use App\Repository\NewsletterRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserController extends AbstractController
{
    public const ACCOUNTS_PER_PAGE = 4;
    public const TITLE_USERS = 'Liste des utilisateurs';
    public const TITLE_AGENTS = 'Liste des agents';
    public const TITLE_MAYOR = 'Maire';
    public const URL_TYPE_USERS = 'utilisateurs';
    public const URL_TYPE_AGENTS = 'agents';
    public const URL_TYPE_MAYOR = 'maire';
    public const DB_TYPE_USER = 'ROLE_USER';
    public const DB_TYPE_AGENT = 'ROLE_AGENT';
    public const DB_TYPE_MAYOR = 'ROLE_MAYOR';

    private $userRepo;
    private $translator;

    public function __construct(UserRepository $userRepo, TranslatorInterface $translator) {
        $this->userRepo = $userRepo;
        $this->translator = $translator;
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/agent/inscrits/{type}/{page<\d+>}", requirements={"type": "utilisateurs|agents|maire"}, name="user_index", methods={"GET", "POST"})
     */
    public function index(string $locale, string $type, int $page = 1, Request $request): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('home', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('user_index', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        // Le tableau datas contient toutes les données des surveys
        $datas = [];

        // Page demandée
        $datas['pagination']['page'] = $page;

        $datas['pagination']['url'] = 'agent/inscrits/'.$type;

        $user = $this->userRepo->findOneBy(['id' => $this->getUser()->getId()]);
        $askedRole = null;


        // Création de la liste déroulante du formulaire
        $form = $this->createFormBuilder()
            ->add('type', ChoiceType::class, [
                'mapped' => false,
                'label' => $this->translator->trans('Type de membre à afficher'),
                'choices' => [
                    $this->translator->trans(RolesChecker::URL_USERS) => RolesChecker::URL_USERS,
                    $this->translator->trans(RolesChecker::URL_AGENTS) => RolesChecker::URL_AGENTS,
                    $this->translator->trans(RolesChecker::URL_MAYOR) => RolesChecker::URL_MAYOR,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        //dd($form->get('type')->getData());

        // Si le formulaire est bien rempli...
        if ($form->isSubmitted() && $form->isValid()) {
            // Si le csrf est invalide
            if (!$this->isCsrfTokenValid('user-item' . $this->getUser()->getId(), $request->request->get('token'))) {
                $this->addFlash('danger', $this->translator->trans('Formulaire non autorisé.'));
                
                return $this->redirectToRoute('user_index', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'page' => 1,
                    'type' => RolesChecker::URL_USERS,
                ]);
            }

            if ($form->get('type')->getData() === RolesChecker::URL_MAYOR) {
                return $this->redirectToRoute('user_index', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'page' => 1,
                    'type' => RolesChecker::URL_MAYOR,
                ]);
            } elseif ($form->get('type')->getData() === RolesChecker::URL_AGENTS) {
                return $this->redirectToRoute('user_index', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'page' => 1,
                    'type' => RolesChecker::URL_AGENTS,
                ]);
            } else {
                return $this->redirectToRoute('user_index', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'page' => 1,
                    'type' => RolesChecker::URL_USERS,
                ]);
            }
        }

        // On détermine quel est le role recherché pour les afficher
        switch ($type) {
            case RolesChecker::URL_MAYOR:
                $askedRole = RolesChecker::DB_MAYOR;
                break;

            case RolesChecker::URL_AGENTS:
                $askedRole = RolesChecker::DB_AGENT;
                break;

            case RolesChecker::URL_USERS:
                $askedRole = RolesChecker::DB_USER;
                break;

            default:
                $askedRole = RolesChecker::DB_USER;
                break;
        }

        // Recherche des users par role et page
        $users = $this->userRepo->findByRoleAndPage($askedRole, $page, self::ACCOUNTS_PER_PAGE);

        $userDatas = [];
        foreach ($users as $key => $userFromList) {
            $datas['users'][$key] = [
                'id' => $userFromList->getId(),
                'email' => $userFromList->getEmail(),
                'firstName' => $firstName = ($userFromList->getFirstName() == "") ? '?' : $userFromList->getFirstName(),
                'lastName' => $lastName = ($userFromList->getLastName() == "") ? '?' : $userFromList->getLastName(),
                'isVerified' => $isverified = ($userFromList->isVerified()) ? 'oui' : 'non',
                'user_terms_of_use' => $user_terms_of_use = ($userFromList->getUserTermsOfUse()) ? 'oui' : 'non',
                'employee_terms_of_use' => $employee_terms_of_use = ($userFromList->getEmployeeTermsOfUse()) ? 'oui' : 'non',
                'created_at' => $userFromList->getCreatedAt(),
                'last_modification' => $userFromList->getLastModification(),
            ];

            $listRoleChecker = new RolesChecker($userFromList->getRoles());
            $datas['users'][$key]['roles'] = $listRoleChecker->getRoles();
        }

        // Calcul du nombres de pages en fonction du nombre d'evenements par page
        $datas['pagination']['pages'] = (int) ceil($this->userRepo->countByRole($askedRole) / self::ACCOUNTS_PER_PAGE);



        // Vue du formulaire
        $datas['form'] = $form->createView();

        // Titre de la page en fonction du role recherché
        $datas['title'] = $this->translator->trans(RolesChecker::urlToTitle($type));

        return $this->render('user/index.html.twig', [
            'datas' => $datas,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/agent/inscrit/{id<\d+>}/promouvoir", name="user_promote", methods={"GET", "POST"})
     */
    public function promote(string $locale, int $id, Request $request, ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('user_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('user_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Recherche utilisateur
        $userToPromote = $this->userRepo->findOneBy(['id' => $id]);

        // Recherche role
        $roleChecker = new RolesChecker($userToPromote->getRoles());

        // Si role différent de user (donc si agent, maire ou admin)
        if (!($roleChecker->getRole() === RolesChecker::DB_USER)) {
            $this->addFlash('danger', $this->translator->trans('Les agents et maires ne peuvent pas être promus en tant qu\'agent.'));

            $url = $request->headers->get('referer');
            // Si la page précédente existe on y retourne. 
            if (!is_null($url)) {
                return $this->redirect($url);
            }

            // Sinon on redirige vers la page de liste d'inscrits
            return $this->redirectToRoute('user_index', [
                'locale' => $locale,
                'type' => 'utilisateurs',
                'page' => 1,
            ]);
        }

        // Ajout ROLE_AGENT
        $userToPromote->setRoles(["ROLE_USER", "ROLE_AGENT"]);

        // Enregistrement
        $doctrine->getManager()->persist($userToPromote);
        $doctrine ->getManager()->flush();

        // Message succès
        $this->addFlash('success', $this->translator->trans('L\'utilisateur a désormais le role d\'agent.'));

        // Envoi du lien par e-mail
        $email = (new TemplatedEmail())
            ->from('samierfabien@gmail.com')
            ->to($userToPromote->getEmail())
            ->subject('Promotion')
            ->htmlTemplate('email/promotion_email.html.twig')
            ->context([
                'userEmail' => $userToPromote->getEmail(),
            ]);

        try {
            $mailer->send($email);
            $this->addFlash('notice', $this->translator->trans('Un email contenant les détails a été envoyé à ') . $userToPromote->getEmail() . '.');
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('warning', $this->translator->trans('L\'email n\'a pas été envoyé à ') . $userToPromote->getEmail() . '.');
        }

        return $this->redirectToRoute('user_index', [
            'locale' => $locale,
            'type' => 'utilisateurs',
            'page' => 1,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/agent/inscrit/{id<\d+>}/destituer", name="user_dismiss", methods={"GET", "POST"})
     */
    public function dismiss(string $locale, int $id, Request $request, ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('user_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('user_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Recherche utilisateur
        $userToDismiss = $this->userRepo->findOneBy(['id' => $id]);

        // Recherche role
        $roleChecker = new RolesChecker($userToDismiss->getRoles());


        // Si rôle différent d'agent (si utilisateur ou maire)
        if (!($roleChecker->getRole() == RolesChecker::DB_AGENT)) {
            $this->addFlash('danger', $this->translator->trans('Les utilisateurs et maire ne peuvent pas être destitués.'));

            $url = $request->headers->get('referer');
            // Si la page précédente existe on y retourne. 
            if (!is_null($url)) {
                return $this->redirect($url);
            }

            // Sinon on redirige vers la page de liste d'inscrits
            return $this->redirectToRoute('user_index', [
                'locale' => $locale,
                'type' => 'utilisateurs',
                'page' => 1,
            ]);
        }

        // Retrait ROLE_AGENT
        $userToDismiss->setRoles(["ROLE_USER"]);
        $userToDismiss->setEmployeeTermsOfUse(false);

        // Enregistrement
        $doctrine->getManager()->persist($userToDismiss);
        $doctrine ->getManager()->flush();

        // Message succès
        $this->addFlash('success', $this->translator->trans('L\'utilisateur a désormais le role d\'utilisateur.'));

        // Envoi du lien par e-mail
        $email = (new TemplatedEmail())
            ->from('samierfabien@gmail.com')
            ->to($userToDismiss->getEmail())
            ->subject('Rétrogradation')
            ->htmlTemplate('email/dismissal_email.html.twig')
            ->context([
                'userEmail' => $userToDismiss->getEmail(),
            ]);

        try {
            $mailer->send($email);
            $this->addFlash('notice', $this->translator->trans('Un email contenant les détails a été envoyé à ') . $userToDismiss->getEmail() . '.');
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('warning', $this->translator->trans('L\'email n\'a pas été envoyé à ') . $userToDismiss->getEmail() . '.');
        }

        return $this->redirectToRoute('user_index', [
            'locale' => $locale,
            'type' => 'utilisateurs',
            'page' => 1,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/agent/inscrit/{id<\d+>}/supprimer", name="user_remove", methods={"GET", "POST"})
     */
    public function remove(string $locale, int $id, Request $request, ManagerRegistry $doctrine, MailerInterface $mailer, RegistrationController $registrationController, AttendsRepository $attendsRepo, BallotsRepository $ballotsRepo, OpinionsRepository $opinionsRepo, ArticleRepository $articleRepo, EventsRepository $eventsRepo, SurveysRepository $surveysRepo, VotesRepository $votesRepo, NewsletterRepository $newsletterRepo, ChatRepository $chatRepo, MessageRepository $messageRepo): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('user_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('user_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Recherche utilisateur
        $userToDelete = $this->userRepo->findOneBy(['id' => $id]);

        // Recherche role
        $roleChecker = new RolesChecker($userToDelete->getRoles());

        // Si rôle différent de maire (si utilisateur ou agent)
        if ($roleChecker->getRole() === RolesChecker::DB_MAYOR) {
            $this->addFlash('danger', $this->translator->trans('Le compte du maire ne peut pas être supprimé.'));

            $url = $request->headers->get('referer');
            // Si la page précédente existe on y retourne. 
            if (!is_null($url)) {
                return $this->redirect($url);
            }

            // Sinon on redirige vers la page de liste d'inscrits
            return $this->redirectToRoute('user_index', [
                'locale' => $locale,
                'type' => 'utilisateurs',
                'page' => 1,
            ]);
        }

        // Suppression du compte
        $registrationController->deleteDatasFromUser($userToDelete, $doctrine, $attendsRepo, $ballotsRepo, $opinionsRepo, $articleRepo, $eventsRepo, $surveysRepo, $votesRepo, $newsletterRepo, $chatRepo, $messageRepo);

        // Message succès
        $this->addFlash('success', 'Le compte a bien été supprimé !');

        // Envoi du lien par e-mail
        $email = (new TemplatedEmail())
            ->from('samierfabien@gmail.com')
            ->to($userToDelete->getEmail())
            ->subject('Suppression')
            ->htmlTemplate('email/dismissal_email.html.twig')
            ->context([
                'userEmail' => $userToDelete->getEmail(),
            ]);

        try {
            $mailer->send($email);
            $this->addFlash('notice', $this->translator->trans('Un email contenant les détails a été envoyé à ') . $userToDelete->getEmail() . '.');
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('warning', $this->translator->trans('L\'email n\'a pas été envoyé à ') . $userToDelete->getEmail() . '.');
        }

        return $this->redirectToRoute('user_index', [
            'locale' => $locale,
            'type' => 'utilisateurs',
            'page' => 1,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/agent/inscrit/{id<\d+>}/elire", name="user_elect", methods={"GET", "POST"})
     */
    public function elect(string $locale, int $id, Request $request, ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('user_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('user_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // n'importe quel compte
        // ne peut être fait que par le maire
        // Envoi d'email
        // message flash : a bien ete supprime

        // ! pas possible si maire !

        // Recherche utilisateur
        $user = $this->userRepo->findOneBy(['id' => $this->getUser()->getId()]);
        $userToElect = $this->userRepo->findOneBy(['id' => $id]);

        // Recherche role
        $roleChecker = new RolesChecker($user->getRoles());

        // Si rôle différent de maire (si utilisateur ou maire)
        if (!($roleChecker->getRole() === RolesChecker::DB_MAYOR)) {
            $this->addFlash('danger', $this->translator->trans('Seul le maire peut en désigner un autre.'));

            $url = $request->headers->get('referer');
            // Si la page précédente existe on y retourne. 
            if (!is_null($url)) {
                return $this->redirect($url);
            }

            // Sinon on redirige vers la page de liste d'inscrits
            return $this->redirectToRoute('user_index', [
                'locale' => $locale,
                'type' => 'utilisateurs',
                'page' => 1,
            ]);
        }

        // Ajout ROLE_MAYOR
        $userToElect->setRoles(["ROLE_USER", "ROLE_AGENT", "ROLE_MAYOR"]);
        // Retrait ROLE_MAYOR
        $user->setRoles((["ROLE_USER", "ROLE_AGENT"]));

        // Enregistrement
        $doctrine->getManager()->persist($userToElect);
        $doctrine->getManager()->persist($user);
        $doctrine ->getManager()->flush();

        // Message succès
        $this->addFlash('success', $this->translator->trans('La passation a bien été effectuée.'));

        // Envoi du lien par e-mail
        $email = (new TemplatedEmail())
            ->from('samierfabien@gmail.com')
            ->to($userToElect->getEmail())
            ->subject('Election')
            ->htmlTemplate('email/election_email.html.twig')
            ->context([
                'userEmail' => $userToElect->getEmail(),
            ]);

        try {
            $mailer->send($email);
            $this->addFlash('notice', $this->translator->trans('Un email contenant les détails a été envoyé à ') . $userToElect->getEmail() . '.');
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('warning', $this->translator->trans('L\'email n\'a pas été envoyé à ') . $userToElect->getEmail() . '.');
        }

        return $this->redirectToRoute('user_index', [
            'locale' => $locale,
            'type' => 'utilisateurs',
            'page' => 1,
        ]);
    }
}
