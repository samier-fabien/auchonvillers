<?php

namespace App\Controller;

use DateTime;
use App\Service\Regex;
use App\Entity\Surveys;
use App\Entity\Opinions;
use App\Service\Imagine;
use App\Form\SurveysType;
use App\Repository\SurveysRepository;
use App\Repository\OpinionsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SurveysController extends AbstractController
{
    public const SURVEYS_PER_PAGE = 4;
    private $surveysRepo;
    private $translator;

    public function __construct(SurveysRepository $surveysRepo, TranslatorInterface $translator) {
        $this->surveysRepo = $surveysRepo;
        $this->translator = $translator;
    }

    /**
     * @Route("/{locale}/enquetes/{page<\d+>}", name="surveys_index", methods={"GET"})
     */
    public function index(string $locale, int $page = 1, Request $request, Regex $regex, Imagine $imagine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Liste des surveys
        $surveys = $this->surveysRepo->findByPage($page, self::SURVEYS_PER_PAGE);

        // Si liste vide retour avec flash message
        if (empty($surveys)) {
            $this->addFlash('notice', $this->translator->trans('La page que vous essayez de consulter n\'existe pas.'));
            return $this->redirectToRoute('surveys_index', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }

        // Le tableau datas contient toutes les données des surveys
        $datas = [];

        // Calcul du nombres de pages en fonction du nombre d'éléments par page
        $datas['pagination']['pages'] = (int) ceil($this->surveysRepo->getnumber() / self::SURVEYS_PER_PAGE);

        // Pagination : ajout numéro page courante pour la pagination
        $datas['pagination']['page'] = $page;

        // Pagination : ajout d'url
        $datas['pagination']['url'] = 'enquetes';

        // Ajout / traitement données évènements
        foreach ($surveys as $key => $value) {
            $getContent = 'getSurContent' . ucFirst($locale);

            $datas['cards'][$key] = [
                'id' => $value->getId(),
                'createdAt' => $value->getSurCreatedAt(),
                'begining' => $value->getSurBegining(),
                'end' => $value->getSurEnd(),
                'content' => $regex->textTruncate($regex->removeHtmlTags(htmlspecialchars_decode($value->$getContent(), ENT_QUOTES)), 58),
                'thumb' => $regex->findFirstImage(htmlspecialchars_decode($value->getSurContentFr(), ENT_QUOTES)),
                'image' => $imagine->toSquareFourHundreds($regex->findFirstImage(htmlspecialchars_decode($value->getSurContentFr(), ENT_QUOTES))),
                'url' => 'enquete',
            ];
        }
        
        return $this->render('surveys/index.html.twig', [
            'datas' => $datas,
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

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('surveys_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('surveys_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
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
    public function show(string $locale, int $id, Request $request, ManagerRegistry $doctrine, OpinionsRepository $opinionsRepo): Response
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
            $message = $this->translator->trans('L\'enquête que vous essayez de consulter n\'existe pas.');
            $this->addFlash('notice', $message);
            return $this->redirectToRoute('surveys_index', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }

        // Le tableau datas contient toutes les données envoyées au template
        $datas = [];

        // Détails de l'enquête
        $content = 'getSurContent' . $locale;
        $datas['survey'] = [
            'id' => $survey->getId(),
            'createdAt' => $survey->getSurCreatedAt(),
            'begining' => $survey->getSurBegining(),
            'end' => $survey->getSurEnd(),
            'content' => htmlspecialchars_decode($survey->$content(), ENT_QUOTES),
        ];

        // Création de l'enquête (resultat formulaire)
        $opinion = new Opinions();
        $opinion->setUser($this->getUser());
        $opinion->setSurvey($survey);

        // Création du formulaire d'enquête
        $questionLabel = 'getSurQuestion' . ucFirst($locale);
        $form = $this->createFormBuilder($opinion)
            ->add('opi_opinion', TextareaType::class, [
                'label' => $survey->$questionLabel(),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        // Si le formulaire est bien rempli...
        if ($form->isSubmitted() && $form->isValid()) {

            // Si le csrf est invalide
            if (!$this->isCsrfTokenValid('opinion-item'.$survey->getId(), $request->request->get('token'))) {
                $this->addFlash('danger', $this->translator->trans('Formulaire non autorisé.'));
                
                return $this->redirectToRoute('surveys_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $survey->getId(),
                ]);
            }

            // Si l'utilisateur n'est pas connecté
            if (is_null($this->getUser())) {
                $this->addFlash('notice', 'Vous devez être connecté pour donner votre opinion');

                return $this->redirectToRoute('surveys_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $survey->getId(),
                ]);
            }

            // Si l'utilisateur n'est pas vérifié
            if (!$this->getUser()->isVerified()) {
                $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
                return $this->redirectToRoute('surveys_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $id,
                ]);
            }

            // Si l'utilisateur a déja voté
            if (!is_null($opinionsRepo->findPerSurveyAndUser($id, $this->getUser()->getId()))) {
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

        $datas['form'] = $form->createView();

        return $this->render('surveys/show.html.twig', [
            'datas' => $datas,
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

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('surveys_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('surveys_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
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

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('surveys_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('surveys_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
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

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{locale}/enquete/{id<\d+>}/membre/supprimer", name="opinion_delete", methods={"POST"})
     */
    public function opinion_delete(string $locale, int $id, Request $request, ManagerRegistry $doctrine, OpinionsRepository $opinionsRepo): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('surveys_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }

        // On va chercher la newsletter a supprimer
        $opinion = $opinionsRepo->findPerSurveyAndUser($id, $this->getUser()->getId());

        // Si l'utilisateur a déja donné son opinion
        if (empty($opinion)) {
            $this->addFlash('notice', 'Vous n\'avez pas donné votre opinion');

            return $this->redirectToRoute('surveys_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }


        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            // Suppression
            $doctrine->getManager()->remove($opinion);
            $doctrine->getManager()->flush();
            $this->addFlash('success', 'Votre opinion a bien été supprimée.');
        }

        return $this->redirectToRoute('surveys_show', [
            'locale'=> $request->getSession()->get('_locale'),
            'id' => $id,
        ], Response::HTTP_SEE_OTHER);
    }

}
