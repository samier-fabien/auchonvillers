<?php

namespace App\Controller;

use App\Entity\Article;
use DateTime;
use App\Entity\Votes;
use App\Entity\Events;
use App\Service\Regex;
use App\Entity\Ballots;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\VotesType;
use App\Form\EventsType;
use App\Form\BallotsType;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\BallotsRepository;
use App\Repository\CategoryRepository;
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

class ArticleController extends AbstractController
{
    public const ARTICLES_PER_PAGE = 4;
    private $articleRepo;

    public function __construct(ArticleRepository $articleRepo) {
        $this->articleRepo = $articleRepo;
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/article/agent/creer", name="article_new", methods={"GET", "POST"})
     */
    public function new(string $locale, Request $request, ManagerRegistry $doctrine, CategoryRepository $categoryRepo): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        $article = new Article();
        $article->setUser($this->getUser())
            //->setCategory($categoryRepo->findOneBy(['id' => $themeId]))
            ->setArtCreatedAt(new DateTime())
        ;

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setArtTitleFr(htmlspecialchars($article->getArtTitleFr(), ENT_QUOTES));
            $article->setArtTitleEn(htmlspecialchars($article->getArtTitleEn(), ENT_QUOTES));
            $article->setArtContentFr(htmlspecialchars($article->getArtContentFr(), ENT_QUOTES));
            $article->setArtContentEn(htmlspecialchars($article->getArtContentEn(), ENT_QUOTES));

            $doctrine->getManager()->persist($article);
            $doctrine->getManager()->flush();

            $this->addFlash('success', 'L\'article a été créé');

            return $this->redirectToRoute('article_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $article->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{locale}/article/{id<\d+>}", name="article_show", methods={"GET", "POST"})
     */
    public function show(string $locale, int $id, Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine, Regex $regex, ArticleRepository $articleRepo): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche de l'article
        $article = $this->articleRepo->findOneBy(["id" => $id]);

        // Si l'article n'est pas trouvée
        if (is_null($article)) {
            $message = $translator->trans('L\'article que vous essayez de consulter n\'existe pas.');
            $this->addFlash('warning', $message);
            return $this->redirectToRoute('home', [
                'locale' => $locale,
            ]);
        }

        // On organise les données
            $articleDatas = [
                'id' => $article->getId(),
                'createdAt' => $article->getArtCreatedAt(),
                'contentFr' => htmlspecialchars_decode($article->getArtContentFr(), ENT_QUOTES),
                'contentEn' => htmlspecialchars_decode($article->getArtContentEn(), ENT_QUOTES),
                'titleFr' => htmlspecialchars_decode($article->getArtTitleFr(), ENT_QUOTES),
                'titleEn' => htmlspecialchars_decode($article->getArtTitleEn(), ENT_QUOTES),
                'thumb' => $regex->findFirstImage(htmlspecialchars_decode($article->getArtContentFr(), ENT_QUOTES)),
            ];

        return $this->render('article/show.html.twig', [
            'article' => $articleDatas,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/article/{id<\d+>}/agent/editer", name="article_edit", methods={"GET", "POST"})
     */
    public function edit(string $locale = 'fr', int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // On va chercher l'evenement a modifier
        $article  = $this->articleRepo->findOneBy(["id" => $id]);

        // Si la categorie n'a pas été trouvée
        if (is_null($article)) {
            $this->addFlash('notice', 'L\'article que vous essayez de modifier n\'existe pas.');
            return $this->redirectToRoute('home', [
                'locale' => $locale,
            ]);
        }   

        // Décodage des données
        $article->setArtTitleFr(htmlspecialchars_decode($article->getArtTitleFr(), ENT_QUOTES))
            ->setArtTitleEn(htmlspecialchars_decode($article->getArtTitleEn(), ENT_QUOTES))
            ->setArtContentFr(htmlspecialchars_decode($article->getArtContentFr(), ENT_QUOTES))
            ->setArtContentEn(htmlspecialchars_decode($article->getArtContentEn(), ENT_QUOTES))
        ;   

        // Création du formulaire
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        // Si le formulaire est bien rempli..
        if ($form->isSubmitted() && $form->isValid()) {
            // ... on encode les contenus fr et en avant de les ajouter en bdd
            $article->setArtTitleFr(htmlspecialchars($article->getArtTitleFr(), ENT_QUOTES))
                ->setArtTitleEn(htmlspecialchars($article->getArtTitleEn(), ENT_QUOTES))
                ->setArtContentFr(htmlspecialchars($article->getArtContentFr(), ENT_QUOTES))
                ->setArtContentEn(htmlspecialchars($article->getArtContentEn(), ENT_QUOTES))
            ;

            $doctrine->getManager()->persist($article);
            $doctrine->getManager()->flush();

            // Ajout de message de succes et redirection vers la news qui vient d'être modifié
            $this->addFlash('success', 'L\'article a été édité');

            return $this->redirectToRoute('article_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $article->getId(),
            ]);
        }

        // Affichage du formulaire
        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/article/{id<\d+>}/agent/supprimer", name="article_delete", methods={"POST"})
     */
    public function delete(string $locale, int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // On va chercher la categorie à supprimer
        $article = $this->articleRepo->findOneBy(["id" => $id]);


        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            // Suppression
            $doctrine->getManager()->remove($article);
            $doctrine->getManager()->flush();
            $this->addFlash('success', 'L\'article a bien été supprimée.');
        }

        return $this->redirectToRoute('home', [
            'locale' => $request->getSession()->get('_locale'),
        ], Response::HTTP_SEE_OTHER);
    }
}
