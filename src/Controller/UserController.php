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

    private $userRepo;
    private $translator;

    public function __construct(UserRepository $userRepo, TranslatorInterface $translator) {
        $this->userRepo = $userRepo;
        $this->translator = $translator;
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/agent/inscrits/utilisateurs/{page<\d+>}", name="user_index", methods={"GET", "POST"})
     */
    public function index(string $locale, int $page = 1, Request $request, Regex $regex): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }
        
        // Recherche des evenements dans la bdd
        $users = $this->userRepo->findByRoleAndPage('ROLE_USER', $page, self::ACCOUNTS_PER_PAGE);
        //dd($users);

        $userDatas = [];
        foreach ($users as $key => $user) {
            $userDatas[$key] = [
                'email' => $user->getEmail(),
                // 'roles' => $roles,
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
        $pages = (int) ceil($this->userRepo->getnumber() / self::ACCOUNTS_PER_PAGE);
        
        return $this->render('user/index.html.twig', [
            'users' => $userDatas,
            'page' => $page,
            'pages' => $pages,
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

        

        return $this->render('votes/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
