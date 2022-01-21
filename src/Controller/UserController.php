<?php

namespace App\Controller;

use DateTime;
use App\Entity\Votes;
use App\Entity\Events;
use App\Service\Regex;
use App\Entity\Ballots;
use App\Form\VotesType;
use App\Form\EventsType;
use App\Form\BallotsType;
use App\Repository\BallotsRepository;
use App\Repository\VotesRepository;
use App\Repository\EventsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserController extends AbstractController
{
    public const ACCOUNTS_PER_PAGE = 4;
    public const TITLE_USERS = 'Liste des utilisateurs';
    public const TITLE_AGENTS = 'Liste des agents';
    public const TITLE_MAYOR = 'Maire';
    public const TYPE_USERS = 'utilisateurs';
    public const TYPE_AGENTS = 'agents';
    public const TYPE_MAYOR = 'maire';

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
    public function index(string $locale, string $type = 'utilisateur', int $page = 1, Request $request, Regex $regex): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Création de la liste déroulante
        $form = $this->createFormBuilder()
            ->add('type', ChoiceType::class, [
                'mapped' => false,
                'label' => $this->translator->trans('Type de membre à afficher'),
                'choices' => [
                    $this->translator->trans('Utilisateur') => self::TYPE_USERS,
                    $this->translator->trans('Agents') => self::TYPE_AGENTS,
                    $this->translator->trans('Maire') => self::TYPE_MAYOR,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                // 'expanded' => true,
                // 'multiple' => false,
            ])
            ->getForm()
        ;

        $form->handleRequest($request);
        

        // Si le formulaire est bien rempli...
        if ($form->isSubmitted() && $form->isValid()) {
            // Si le csrf est invalide
            if (!$this->isCsrfTokenValid('user-item' . $this->getUser()->getId(), $request->request->get('token'))) {
                $this->addFlash('danger', $this->translator->trans('Formulaire non autorisé.'));
                
                return $this->redirectToRoute('user_index', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'page' => 1,
                    'type' => self::TYPE_USERS,
                ]);
            }

            if ($form->get('type')->getData() === 'maire') {
                return $this->redirectToRoute('user_index', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'page' => 1,
                    'type' => self::TYPE_MAYOR,
                ]);
            } elseif ($form->get('type')->getData() === 'agents') {
                return $this->redirectToRoute('user_index', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'page' => 1,
                    'type' => self::TYPE_AGENTS,
                ]);
            } else {
                return $this->redirectToRoute('user_index', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'page' => 1,
                    'type' => self::TYPE_USERS,
                ]);
            }
        }

        // 
        $requestParameters = [
            self::TYPE_USERS => [
                'role' => 'ROLE_USER',
                'title' => self::TITLE_USERS,
            ],
            self::TYPE_AGENTS => [
                'role' => 'ROLE_AGENT',
                'title' => self::TITLE_AGENTS,
            ],
            self::TYPE_MAYOR => [
                'role' => 'ROLE_MAYOR',
                'title' => self::TITLE_MAYOR,
            ],
        ];
        
        // Recherche des evenements dans la bdd
        $users = $this->userRepo->findByRoleAndPage($requestParameters[$type]['role'], $page, self::ACCOUNTS_PER_PAGE);

        $userDatas = [];
        foreach ($users as $key => $user) {
            $userDatas[$key] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstName' => $fisrtName = ($user->getFirstName() == "") ? '?' : $user->getFirstName(),
                'lastName' => $lastName = ($user->getLastName() == "") ? '?' : $user->getLastName(),
                'isVerified' => $isverified = ($user->isVerified()) ? 'oui' : 'non',
                'user_terms_of_use' => $user_terms_of_use = ($user->getUserTermsOfUse()) ? 'oui' : 'non',
                'employee_terms_of_use' => $employee_terms_of_use = ($user->getEmployeeTermsOfUse()) ? 'oui' : 'non',
                'created_at' => $user->getCreatedAt(),
                'last_modification' => $user->getLastModification(),
            ];
 
            foreach ($user->getRoles() as $roleKey => $role) {
                switch ($role) {
                    case 'ROLE_USER': $userDatas[$key]['roles'][$roleKey] = 'Utilisateur';
                        break;
    
                    case 'ROLE_AGENT': $userDatas[$key]['roles'][$roleKey] = 'Agent';
                        break;
    
                    case 'ROLE_MAYOR': $userDatas[$key]['roles'][$roleKey] = 'Maire';
                    break;
    
                    case 'ROLE_ADMIN': $userDatas[$key]['roles'][$roleKey] = 'Administrateur';
                        break;
                }
            }
        }

        // Calcul du nombres de pages en fonction du nombre d'evenements par page
        $pages = (int) ceil($this->userRepo->getnumber($requestParameters[$type]['role']) / self::ACCOUNTS_PER_PAGE);
        
        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
            'users' => $userDatas,
            'page' => $page,
            'pages' => $pages,
            'title' => $requestParameters[$type]['title'],
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}agent/inscrit/{id<\d+>}/promouvoir", name="user_promote", methods={"GET", "POST"})
     */
    public function promote(string $locale, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('votes_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('votes_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si user est juste un ROLE_USER : Ajouter ROLE_AGENT
        // Envoi d'email
        // flash : a bien ete promu

        // ! pas possible si maire !

        return $this->render('votes/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}agent/inscrit/{id<\d+>}/destituer", name="user_dismiss", methods={"GET", "POST"})
     */
    public function dismiss(string $locale, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('votes_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('votes_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si user est ROLE_AGENT : retirer ROLE_AGENT
        // Envoi d'email
        // message flash : a bien ete destitué

        // ! pas possible si maire !

        return $this->render('votes/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}agent/inscrit/{id<\d+>}/supprimer", name="user_remove", methods={"GET", "POST"})
     */
    public function remove(string $locale, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('votes_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('votes_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Envoi d'email
        // message flash : a bien ete supprime

        // ! pas possible si maire !

        return $this->render('votes/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}agent/inscrit/{id<\d+>}/elire", name="user_elect", methods={"GET", "POST"})
     */
    public function elect(string $locale, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('votes_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('votes_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // n'importe quel compte
        // ne peut être fait que par le maire
        // Envoi d'email
        // message flash : a bien ete supprime

        // ! pas possible si maire !

        return $this->render('votes/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
