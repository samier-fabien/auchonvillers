<?php

namespace App\Controller;

use App\Entity\Article;
use DateTime;
use App\Entity\Votes;
use App\Entity\Events;
use App\Service\Regex;
use App\Entity\Ballots;
use App\Entity\Category;
use App\Form\VotesType;
use App\Form\EventsType;
use App\Form\BallotsType;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\BallotsRepository;
use App\Repository\CategoryRepository;
use App\Repository\VotesRepository;
use App\Repository\EventsRepository;
use App\Service\Imagine;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    private $categoryRepo;
    private $translator;

    public function __construct(CategoryRepository $categoryRepo, TranslatorInterface $translator) {
        $this->categoryRepo = $categoryRepo;
        $this->translator = $translator;
    }

    // /**
    //  * @Route("/{locale}/votes/{page<\d+>}", name="votes_index", methods={"GET"})
    //  */
    // public function index(string $locale, int $page = 1, Request $request, Regex $regex): Response
    // {
    //     // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
    //     if (!in_array($locale, $this->getParameter('app.locales'), true)) {
    //         $request->getSession()->set('_locale', 'fr'); 
    //         return $this->redirect("/");
    //     }

    //     // Recherche des evenements dans la bdd
    //     $votes = $this->votesRepo->findByPage($page, self::VOTES_PER_PAGE);
    //     $votesDatas = [];
    //     foreach ($votes as $key => $value) {
    //         $votesDatas[$key] = [
    //             'id' => $value->getId(),
    //             'createdAt' => $value->getVotCreatedAt(),
    //             'begining' => $value->getVotBegining(),
    //             'end' => $value->getVotEnd(),
    //             'contentFr' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getVotContentFr(), ENT_QUOTES)),
    //             'contentEn' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getVotContentEn(), ENT_QUOTES)),
    //             'thumb' => $regex->findFirstImage(htmlspecialchars_decode($value->getVotContentFr(), ENT_QUOTES)),
    //         ];
    //     }

    //     // Calcul du nombres de pages en fonction du nombre d'evenements par page
    //     $pages = (int) ceil($this->votesRepo->getnumber() / self::VOTES_PER_PAGE);
        
    //     return $this->render('votes/index.html.twig', [
    //         'votes' => $votesDatas,
    //         'page' => $page,
    //         'pages' => $pages,
    //     ]);
    // }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/theme/agent/creer", name="category_new", methods={"GET", "POST"})
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
            return $this->redirectToRoute('home', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('home', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCatNameFr(htmlspecialchars($category->getCatNameFr(), ENT_QUOTES));
            $category->setCatNameEn(htmlspecialchars($category->getCatNameEn(), ENT_QUOTES));
            $category->setCatDescriptionFr(htmlspecialchars($category->getCatDescriptionFr(), ENT_QUOTES));
            $category->setCatDescriptionEn(htmlspecialchars($category->getCatDescriptionEn(), ENT_QUOTES));

            $doctrine->getManager()->persist($category);
            $doctrine->getManager()->flush();

            $this->addFlash('success', 'Le thème a été créé');

            return $this->redirectToRoute('category_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $category->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{locale}/theme/{id<\d+>}", name="category_show", methods={"GET"})
     */
    public function show(string $locale, int $id, Request $request, ManagerRegistry $doctrine, Regex $regex, Imagine $imagine, ArticleRepository $articleRepo): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche de la catégorie
        $category = $this->categoryRepo->findOneBy(["id" => $id]);

        // Si la catégorie n'est pas trouvée
        if (is_null($category)) {
            $this->addFlash('notice', $this->translator->trans('Le thème que vous essayez de consulter n\'existe pas.'));
            return $this->redirectToRoute('home', [
                'locale' => $locale,
            ]);
        }

        // Le tableau datas contient toutes les données envoyées au template
        $datas = [];

        $getName = 'getCatName' . ucfirst($locale);
        $description = 'getCatDescription' . ucfirst($locale);

        $datas['category'] = [
            'id' => $category->getId(),
            'name' => htmlspecialchars_decode($category->$getName(), ENT_QUOTES),
            'description' => htmlspecialchars_decode($category->$description(), ENT_QUOTES),
            'number' => $category->getCatOrderOfAppearance(),
        ];

        // Recherche des articles par rapport a la catégorie
        $articles = $articleRepo->findBy(["category" => $category], ["art_order_of_appearance" => 'ASC']);

        // Ajout / traitement données articles
        foreach ($articles as $key => $value) {
            $getContent = 'getArtContent' . ucfirst($locale);
            $getTitle = 'getArtTitle' . ucfirst($locale);

            $datas['cards'][$key] = [
                'id' => $value->getId(),
                'createdAt' => $value->getArtCreatedAt(),
                'content' => $regex->removeHtmlTags(htmlspecialchars_decode($value->$getContent(), ENT_QUOTES)),
                'content' => $regex->textTruncate($regex->removeHtmlTags(htmlspecialchars_decode($value->$getContent(), ENT_QUOTES)), 200),
                'title' => $title = $regex->removeHtmlTags(htmlspecialchars_decode($value->$getTitle(), ENT_QUOTES)),
                'thumb' => $regex->findFirstImage(htmlspecialchars_decode($value->getArtContentFr(), ENT_QUOTES)),
                'image' => $imagine->toSquareTwoHundreds($regex->findFirstImage(htmlspecialchars_decode($value->getArtContentFr(), ENT_QUOTES))),
                'url' => 'article',
            ];
        }

        return $this->render('category/show.html.twig', [
            'datas' => $datas,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/theme/{id<\d+>}/agent/editer", name="category_edit", methods={"GET", "POST"})
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
            return $this->redirectToRoute('category_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }


        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('category_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }

        // On va chercher l'evenement a modifier
        $category  = $this->categoryRepo->findOneBy(["id" => $id]);

        // Si la categorie n'a pas été trouvée
        if (is_null($category)) {
            $this->addFlash('notice', 'Le thème que vous essayez de modifier n\'existe pas.');
            return $this->redirectToRoute('votes_index', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }   

        // Décodage des données
        $category->setCatNameFr(htmlspecialchars_decode($category->getCatNameFr(), ENT_QUOTES))
            ->setCatNameEn(htmlspecialchars_decode($category->getCatNameEn(), ENT_QUOTES))
            ->setCatDescriptionFr(htmlspecialchars_decode($category->getCatDescriptionFr(), ENT_QUOTES))
            ->setCatDescriptionEn(htmlspecialchars_decode($category->getCatDescriptionEn(), ENT_QUOTES))
        ;   

        // Création du formulaire
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        // Si le formulaire est bien rempli..
        if ($form->isSubmitted() && $form->isValid()) {
            // ... on encode les contenus fr et en avant de les ajouter en bdd
            $category->setCatNameFr(htmlspecialchars($category->getCatNameFr(), ENT_QUOTES))
                ->setCatNameEn(htmlspecialchars($category->getCatNameEn(), ENT_QUOTES))
                ->setCatDescriptionFr(htmlspecialchars($category->getCatDescriptionFr(), ENT_QUOTES))
                ->setCatDescriptionEn(htmlspecialchars($category->getCatDescriptionEn(), ENT_QUOTES))
            ;

            $doctrine->getManager()->persist($category);
            $doctrine->getManager()->flush();

            // Ajout de message de succes et redirection vers la news qui vient d'être modifié
            $this->addFlash('success', 'Le thème a été édité');

            return $this->redirectToRoute('category_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $category->getId(),
            ]);
        }

        // Affichage du formulaire
        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/theme/{id<\d+>}/agent/supprimer", name="category_delete", methods={"POST"})
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
            return $this->redirectToRoute('home', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('home', [
                'locale'=> $request->getSession()->get('_locale'),
            ]);
        }

        // On va chercher la categorie à supprimer
        $category = $this->categoryRepo->findOneBy(["id" => $id]);


        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            // Suppression
            $doctrine->getManager()->remove($category);
            $doctrine->getManager()->flush();
            $this->addFlash('success', 'Le thème a bien été supprimée.');
        }

        return $this->redirectToRoute('home', [
            'locale' => $request->getSession()->get('_locale'),
        ], Response::HTTP_SEE_OTHER);
    }
}
