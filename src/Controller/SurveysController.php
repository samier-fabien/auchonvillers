<?php

namespace App\Controller;

use DateTime;
use App\Entity\Votes;
use App\Entity\Events;
use App\Service\Regex;
use App\Entity\Ballots;
use App\Entity\Surveys;
use App\Form\VotesType;
use App\Entity\Opinions;
use App\Form\EventsType;
use App\Form\BallotsType;
use App\Form\SurveysType;
use App\Repository\VotesRepository;
use App\Repository\EventsRepository;
use App\Repository\BallotsRepository;
use App\Repository\SurveysRepository;
use App\Repository\OpinionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SurveysController extends AbstractController
{
    public const SURVEYS_PER_PAGE = 4;
    private $surveysRepo;

    public function __construct(SurveysRepository $surveysRepo) {
        $this->surveysRepo = $surveysRepo;
    }

    /**
     * @Route("/{locale}/enquetes/{page<\d+>}", name="surveys_index", methods={"GET"})
     */
    public function index(string $locale, int $page = 1, Request $request, Regex $regex): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche des evenements dans la bdd
        $surveys = $this->surveysRepo->findByPage($page, self::SURVEYS_PER_PAGE);
        $surveysDatas = [];
        foreach ($surveys as $key => $value) {
            $surveysDatas[$key] = [
                'id' => $value->getId(),
                'createdAt' => $value->getSurCreatedAt(),
                'begining' => $value->getSurBegining(),
                'end' => $value->getSurEnd(),
                'contentFr' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getSurContentFr(), ENT_QUOTES)),
                'contentEn' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getSurContentEn(), ENT_QUOTES)),
                'thumb' => $regex->findFirstImage(htmlspecialchars_decode($value->getSurContentFr(), ENT_QUOTES)),
            ];
        }

        // Calcul du nombres de pages en fonction du nombre d'evenements par page
        $pages = (int) ceil($this->surveysRepo->getnumber() / self::SURVEYS_PER_PAGE);
        
        return $this->render('surveys/index.html.twig', [
            'surveys' => $surveysDatas,
            'page' => $page,
            'pages' => $pages,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/enquete/agent/creer", name="surveys_new", methods={"GET", "POST"})
     */
    public function new(string $locale, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        $survey = new Surveys();
        $survey->setSurCreatedAt(new DateTime());
        $survey->setUser($this->getUser());

        $form = $this->createForm(SurveysType::class, $survey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $survey->setSurContentFr(htmlspecialchars($survey->getSurContentFr(), ENT_QUOTES));
            $survey->setSurContentEn(htmlspecialchars($survey->getSurContentEn(), ENT_QUOTES));
            $doctrine->getManager()->persist($survey);
            $doctrine->getManager()->flush();
            $this->addFlash('success', 'Votre enquête a été créé');
            return $this->redirectToRoute('surveys_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $survey->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('surveys/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{locale}/enquete/{id<\d+>}", name="surveys_show", methods={"GET", "POST"})
     */
    public function show(string $locale, int $id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine, OpinionsRepository $opinionsRepo): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche de l'évènement avec l'id "x"
        $survey = $this->surveysRepo->findOneBy(["id" => $id]);

        // Si l'évènement n'est pas trouvée
        if (is_null($survey)) {
            $message = $translator->trans('L\'enquête que vous essayez de consulter n\'existe pas.');
            $this->addFlash('notice', $message);
            return $this->redirectToRoute('surveys_index', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }

        // On en décode les contenus fr et en
        $survey->setSurContentFr(htmlspecialchars_decode($survey->getSurContentFr()), ENT_QUOTES);
        $survey->setSurContentEn(htmlspecialchars_decode($survey->getSurContentEn()), ENT_QUOTES);

        // Création de l'enquête
        $opinion = new Opinions();
        $opinion->setUser($this->getUser());
        $opinion->setSurvey($survey);

        // // Résultats de l'enquête
        // $ballotsResults = $ballotsRepo->findResults($id);
        // $firstChoice = 0;
        // $secondChoice = 0;
        // foreach ($ballotsResults as $key => $value) {
        //     if ($value->getBalVote()) {
        //         $secondChoice++;
        //     } else {
        //         $firstChoice++;
        //     }
        // }

        // Création du formulaire d'enquête
        $questionLabel = ($locale == "en") ? $survey->getSurQuestionEn() : $survey->getSurQuestionFr();
        $form = $this->createFormBuilder($opinion)
            ->add('opi_opinion', TextType::class, [
                'label' => $questionLabel,
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        // Si le formulaire est bien rempli...
        if ($form->isSubmitted() && $form->isValid()) {

            // Si l'utilisateur n'est pas connecté
            if (is_null($this->getUser())) {
                $this->addFlash('notice', 'Vous devez être connecté pour donner votre opinion');

                return $this->redirectToRoute('surveys_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $survey->getId(),
                ]);
            }

            // Si l'utilisateur a déja voté
            if (count($opinionsRepo->findPerSurveyAndUser($id, $this->getUser()->getId())) >= 1) {
                $this->addFlash('notice', 'Vous avez déja donné votre opinion');

                return $this->redirectToRoute('surveys_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $survey->getId(),
                ]);
            }

            // ... et que tout est ok : on enregistre l'opinion
            $doctrine->getManager()->persist($opinion);
            $doctrine->getManager()->flush();

            // Ajout de message de succes et redirection vers la news qui vient d'être modifié
            $this->addFlash('success', 'Votre opinion a été enregistré');

            return $this->redirectToRoute('surveys_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $survey->getId(),
            ]);
        }

        return $this->render('surveys/show.html.twig', [
            'survey' => $survey,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/enquete/{id<\d+>}/agent/editer", name="surveys_edit", methods={"GET", "POST"})
     */
    public function edit(string $locale = 'fr', int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // On va chercher l'evenement a modifier
        $survey = $this->surveysRepo->findOneBy(["id" => $id]);

        // Si l'evenement est trouvée
        if (!is_null($survey)) {
            // On en décode les contenus fr et en
            $survey->setSurContentFr(htmlspecialchars_decode($survey->getSurContentFr()), ENT_QUOTES);
            $survey->setSurContentEn(htmlspecialchars_decode($survey->getSurContentEn()), ENT_QUOTES);    

            // Création du formulaire
            $form = $this->createForm(SurveysType::class, $survey);
            $form->handleRequest($request);

            // Si le formulaire est bien rempli..
            if ($form->isSubmitted() && $form->isValid()) {
                // ... on encode les contenus fr et en avant de les ajouter en bdd
                $survey->setSurContentFr(htmlspecialchars($survey->getSurContentFr(), ENT_QUOTES));
                $survey->setSurContentEn(htmlspecialchars($survey->getSurContentEn(), ENT_QUOTES));
                $doctrine->getManager()->persist($survey);
                $doctrine->getManager()->flush();

                // Ajout de message de succes et redirection vers la news qui vient d'être modifié
                $this->addFlash('success', 'Votre enquête a été éditée');

                return $this->redirectToRoute('surveys_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $survey->getId(),
                ]);
            }

            return $this->render('surveys/edit.html.twig', [
                'survey' => $survey,
                'form' => $form->createView(),
            ]);

        } else {
            $this->addFlash('notice', 'L\'enquête que vous essayez de modifier n\'existe pas.');
            return $this->redirectToRoute('surveys_index', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }   
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/enquete/{id<\d+>}/agent/supprimer", name="surveys_delete", methods={"POST"})
     */
    public function delete(string $locale, int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // On va chercher la newsletter a supprimer
        $survey = $this->surveysRepo->findOneBy(["id" => $id]);


        if ($this->isCsrfTokenValid('delete'.$survey->getId(), $request->request->get('_token'))) {
            // Suppression
            $doctrine->getManager()->remove($survey);
            $doctrine->getManager()->flush();
            $this->addFlash('notice', 'L\'enquête a bien été supprimée.');
        }

        return $this->redirectToRoute('surveys_index', [
            'locale' => $request->getSession()->get('_locale'),
            'page' => 1,
        ], Response::HTTP_SEE_OTHER);
    }
}
