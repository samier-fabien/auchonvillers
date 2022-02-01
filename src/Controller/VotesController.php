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

class VotesController extends AbstractController
{
    public const VOTES_PER_PAGE = 4;
    private $votesRepo;
    private $translator;

    public function __construct(VotesRepository $votesRepo, TranslatorInterface $translator) {
        $this->votesRepo = $votesRepo;
        $this->translator = $translator;
    }

    /**
     * @Route("/{locale}/votes/{page<\d+>}", name="votes_index", methods={"GET"})
     */
    public function index(string $locale, int $page = 1, Request $request, Regex $regex): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche des evenements dans la bdd
        $votes = $this->votesRepo->findByPage($page, self::VOTES_PER_PAGE);

        // Calcul du nombres de pages en fonction du nombre d'éléments par page
        $pages = (int) ceil($this->votesRepo->getnumber() / self::VOTES_PER_PAGE);

        // Le tableau datas contient toutes les données des votes
        $votesDatas = [];
        foreach ($votes as $key => $value) {
            $content = 'getVotContent' . ucFirst($locale);

            $votesDatas[$key] = [
                'id' => $value->getId(),
                'createdAt' => $value->getVotCreatedAt(),
                'begining' => $value->getVotBegining(),
                'end' => $value->getVotEnd(),
                'content' => $regex->textTruncate($regex->removeHtmlTags(htmlspecialchars_decode($value->$content(), ENT_QUOTES)), 58),
                'thumb' => $regex->findFirstImage(htmlspecialchars_decode($value->getVotContentFr(), ENT_QUOTES)),
            ];
        }
        
        return $this->render('votes/index.html.twig', [
            'votes' => $votesDatas,
            'page' => $page,
            'pages' => $pages,
            'paginationPath' => 'votes',
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/vote/agent/creer", name="votes_new", methods={"GET", "POST"})
     */
    public function new(string $locale, Request $request, ManagerRegistry $doctrine): Response
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

        $vote = new Votes();
        $vote->setVotCreatedAt(new DateTime());
        $vote->setUser($this->getUser());

        $form = $this->createForm(VotesType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vote->setVotContentFr(htmlspecialchars($vote->getVotContentFr(), ENT_QUOTES));
            $vote->setVotContentEn(htmlspecialchars($vote->getVotContentEn(), ENT_QUOTES));
            $doctrine->getManager()->persist($vote);
            $doctrine->getManager()->flush();
            $this->addFlash('success', 'Votre Vote a été créé');
            return $this->redirectToRoute('votes_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $vote->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('votes/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{locale}/vote/{id<\d+>}", name="votes_show", methods={"GET", "POST"})
     */
    public function show(string $locale, int $id, Request $request, ManagerRegistry $doctrine, BallotsRepository $ballotsRepo): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche du vote avec l'id "x"
        $vote = $this->votesRepo->findOneBy(["id" => $id]);

        // Si le vote n'est pas trouvée
        if (is_null($vote)) {
            $message = $this->translator->trans('Le vote que vous essayez de consulter n\'existe pas.');
            $this->addFlash('notice', $message);
            return $this->redirectToRoute('votes_index', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }

        // On en décode les contenus fr et en
        $vote->setVotContentFr(htmlspecialchars_decode($vote->getVotContentFr()), ENT_QUOTES);
        $vote->setVotContentEn(htmlspecialchars_decode($vote->getVotContentEn()), ENT_QUOTES);

        $content = 'getVotContent' . ucFirst($locale);

        $votesDatas = [
            'id' => $vote->getId(),
            'createdAt' => $vote->getVotCreatedAt(),
            'begining' => $vote->getVotBegining(),
            'end' => $vote->getVotEnd(),
            'content' => htmlspecialchars_decode($vote->$content(), ENT_QUOTES),
        ];


        // Création du vote
        $ballot = new Ballots();
        $ballot->setUser($this->getUser());
        $ballot->setVote($vote);

        // Résultats du vote
        $ballotsResults = $ballotsRepo->findResults($id);
        $firstChoice = 0;
        $secondChoice = 0;
        foreach ($ballotsResults as $key => $value) {
            if ($value->getBalVote()) {
                $secondChoice++;
            } else {
                $firstChoice++;
            }
        }

        // Création du formulaire de vote
        //$form = $this->createForm(BallotsType::class, $ballot);
        $questionLabel = 'getVotQuestion' . $locale;
        $firstChoiceLabel = 'getVotFirstChoice' . $locale;
        $secondChoiceLabel = 'getVotSecondChoice' . $locale;
        $form = $this->createFormBuilder($ballot)
            ->add('bal_vote', ChoiceType::class, [
                'label' => $vote->$questionLabel(),
                'choices' => [
                    $vote->$firstChoiceLabel().' ('.$firstChoice.')' => 0,
                    $vote->$secondChoiceLabel().' ('.$secondChoice.')' => 1,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        // Si le formulaire est bien rempli...
        if ($form->isSubmitted() && $form->isValid()) {

            // Si le csrf est invalide
            if (!$this->isCsrfTokenValid('referendum-item'.$vote->getId(), $request->request->get('token'))) {
                $this->addFlash('danger', $this->translator->trans('Formulaire non autorisé.'));
                
                return $this->redirectToRoute('votes_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $vote->getId(),
                ]);
            }

            // Si l'utilisateur n'est pas connecté
            if (is_null($this->getUser())) {
                $this->addFlash('notice', 'Vous devez être connecté pour donner votre avis');

                return $this->redirectToRoute('votes_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $vote->getId(),
                ]);
            }

            // Si l'utilisateur n'est pas vérifié
            if (!$this->getUser()->isVerified()) {
                $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
                return $this->redirectToRoute('votes_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $id,
                ]);
            }

            // Si l'utilisateur a déja voté
            if (count($ballotsRepo->findPerVoteAndUser($id, $this->getUser()->getId())) >= 1) {
                $this->addFlash('notice', 'Vous avez déja donné votre avis');

                return $this->redirectToRoute('votes_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $vote->getId(),
                ]);
            }

            // ... et que tout est ok : on enregistre le vote
            $doctrine->getManager()->persist($ballot);
            $doctrine->getManager()->flush();

            // Ajout de message de succes et redirection vers la news qui vient d'être modifié
            $this->addFlash('success', 'Votre vote a été enregistré');

            return $this->redirectToRoute('votes_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $vote->getId(),
            ]);
        }

        return $this->render('votes/show.html.twig', [
            'vote' => $votesDatas,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/vote/{id<\d+>}/agent/editer", name="votes_edit", methods={"GET", "POST"})
     */
    public function edit(string $locale = 'fr', int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('votes_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('votes_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }

        // On va chercher l'evenement a modifier
        $vote = $this->votesRepo->findOneBy(["id" => $id]);

        // Si l'evenement est trouvée
        if (!is_null($vote)) {
            // On en décode les contenus fr et en
            $vote->setVotContentFr(htmlspecialchars_decode($vote->getVotContentFr()), ENT_QUOTES);
            $vote->setVotContentEn(htmlspecialchars_decode($vote->getVotContentEn()), ENT_QUOTES);    

            // Création du formulaire
            $form = $this->createForm(VotesType::class, $vote);
            $form->handleRequest($request);

            // Si le formulaire est bien rempli..
            if ($form->isSubmitted() && $form->isValid()) {
                // ... on encode les contenus fr et en avant de les ajouter en bdd
                $vote->setVotContentFr(htmlspecialchars($vote->getVotContentFr(), ENT_QUOTES));
                $vote->setVotContentEn(htmlspecialchars($vote->getVotContentEn(), ENT_QUOTES));
                $doctrine->getManager()->persist($vote);
                $doctrine->getManager()->flush();

                // Ajout de message de succes et redirection vers la news qui vient d'être modifié
                $this->addFlash('success', 'Votre vote a été édité');

                return $this->redirectToRoute('votes_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $vote->getId(),
                ]);
            }

            return $this->render('votes/edit.html.twig', [
                'vote' => $vote,
                'form' => $form->createView(),
            ]);

        } else {
            $this->addFlash('notice', 'Le vote que vous essayez de modifier n\'existe pas.');
            return $this->redirectToRoute('votes_index', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }   
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/vote/{id<\d+>}/agent/supprimer", name="votes_delete", methods={"POST"})
     */
    public function delete(string $locale, int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('votes_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('votes_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }

        // On va chercher la newsletter a supprimer
        $vote = $this->votesRepo->findOneBy(["id" => $id]);


        if ($this->isCsrfTokenValid('delete'.$vote->getId(), $request->request->get('_token'))) {
            // Suppression
            $doctrine->getManager()->remove($vote);
            $doctrine->getManager()->flush();
            $this->addFlash('notice', 'Le vote a bien été supprimée.');
        }

        return $this->redirectToRoute('votes_index', [
            'locale' => $request->getSession()->get('_locale'),
            'page' => 1,
        ], Response::HTTP_SEE_OTHER);
    }
}
