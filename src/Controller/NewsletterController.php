<?php

namespace App\Controller;

use DateTime;
use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Service\LocaleCheck;
use App\Repository\NewsletterRepository;
use App\Service\Regex;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class NewsletterController extends AbstractController
{
    public const NEWSLETTERS_PER_PAGE = 4;
    private $newsletterRepo;

    public function __construct(NewsletterRepository $newsletterRepo)
    {
        $this->newsletterRepo = $newsletterRepo;
    }

    /**
     * @Route("/{locale}/actualites/{page<\d+>}", name="newsletters")
     */
    public function displayAll(string $locale, int $page = 1, Request $request, Regex $regex): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche des newsletters dans la bdd
        $newsletters = $this->newsletterRepo->findByPage($page, self::NEWSLETTERS_PER_PAGE);

        // Calcul du nombres de pages en fonction du nombre de news par page
        $pages = (int) ceil($this->newsletterRepo->getnumber() / self::NEWSLETTERS_PER_PAGE);

        // Le tableau datas contient toutes les données des newsletters
        // Utilité :
        //  -retourne des données décodées vias htmlSpecialChars
        //  -ajoute un thumbnail basé sur la première image trouvé dans la news fr (seule la version fr est obligatoire dans le formulaire)
        //  -le texte est une version de "x" caractères (twig) sans les tags html créés avec ckeditor : pas besoin de 'titre' ni de 'chemin vers une image' en bdd
        $newsDatas = [];
        foreach ($newsletters as $key => $value) {
            $newsDatas[$key] = [
                'id' => $value->getId(),
                'newCreatedAt' => $value->getNewCreatedAt(),
                'newContentFr' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getNewContentFr(), ENT_QUOTES)),
                'newContentEn' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getNewContentEn(), ENT_QUOTES)),
                'thumb' => $regex->findFirstImage(htmlspecialchars_decode($value->getNewContentFr(), ENT_QUOTES)),
            ];
        }

        return $this->render('newsletter/displayAll.html.twig', [
            'newsletters' => $newsDatas,
            'page' => $page,
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/{locale}/actualite/{id<\d+>}", name="newsletter")
     */
    public function display(string $locale, int $id, Request $request, TranslatorInterface $translator): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }
        
        // Recherche de la news avec l'id "x"
        $newsletter = $this->newsletterRepo->findOneBy(["id" => $id]);

        // Si la news est trouvée
        if (!is_null($newsletter)) {
            // On en décode les contenus fr et en
            $newsletter->setNewContentFr(htmlspecialchars_decode($newsletter->getNewContentFr()), ENT_QUOTES);
            $newsletter->setNewContentEn(htmlspecialchars_decode($newsletter->getNewContentEn()), ENT_QUOTES);

            return $this->render('newsletter/display.html.twig', [
                'newsletter' => $newsletter,
            ]);
        // Sinon, redirection vers la liste des actualités avec un message
        } else {
            $message = $translator->trans('La nouvelle que vous essayez de consulter n\'existe pas.');
            $this->addFlash('notice', $message);
            return $this->redirectToRoute('newsletters', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }  
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/actualite/agent/creer", name="newsletterCreate")
     */
    public function create(string $locale, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }
        
        // Création d'un nouvel objet Newsletter qu'on pré-rempli avec la date actuelle et l'utilisateur actuel
        $newsletter = new Newsletter();
        $newsletter->setNewCreatedAt(new DateTime());
        $newsletter->setUser($this->getUser());

        // Créaion du formulaire
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        // Si formulaire soumis et correctement rempli...
        if ($form->isSubmitted() && $form->isValid()) {
            // ... alors on encode les contenus fr et en avant de les ajouter en bdd
            $newsletter->setNewContentFr(htmlspecialchars($newsletter->getNewContentFr(), ENT_QUOTES));
            $newsletter->setNewContentEn(htmlspecialchars($newsletter->getNewContentEn(), ENT_QUOTES));
            $doctrine->getManager()->persist($newsletter);
            $doctrine->getManager()->flush();
            // Ajout de message de succes et redirection vers la news qui vient d'être publiée
            $this->addFlash('success', 'Votre nouvelle a été créée et ajoutée dans le fil d\'actualités');
            return $this->redirectToRoute('newsletter', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $newsletter->getId(),
            ], Response::HTTP_SEE_OTHER);
        }


        return $this->render('newsletter/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/actualite/{id<\d+>}/agent/editer", name="newsletterUpdate")
     */
    public function update(string $locale = 'fr', int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }
        
        // On va chercher la newsletter a modifier
        $newsletter = $this->newsletterRepo->findOneBy(["id" => $id]);

        // Si la news est trouvée
        if (!is_null($newsletter)) {
            // On en décode les contenus fr et en
            $newsletter->setNewContentFr(htmlspecialchars_decode($newsletter->getNewContentFr()), ENT_QUOTES);
            $newsletter->setNewContentEn(htmlspecialchars_decode($newsletter->getNewContentEn()), ENT_QUOTES);    

            // Création du formulaire
            $form = $this->createForm(NewsletterType::class, $newsletter);
            $form->handleRequest($request);

            // Si le formulaire est bien rempli..
            if ($form->isSubmitted() && $form->isValid()) {
                // ... on encode les contenus fr et en avant de les ajhouter en bdd
                $newsletter->setNewContentFr(htmlspecialchars($newsletter->getNewContentFr(), ENT_QUOTES));
                $newsletter->setNewContentEn(htmlspecialchars($newsletter->getNewContentEn(), ENT_QUOTES));
                $doctrine->getManager()->persist($newsletter);
                $doctrine->getManager()->flush();
                // Ajout de message de succes et redirection vers la news qui vient d'être modifié
                $this->addFlash('success', 'Votre nouvelle a été créée et ajoutée dans le fil d\'actualités');
                return $this->redirectToRoute('newsletter', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $newsletter->getId(),
                ]);
            }

            return $this->render('newsletter/update.html.twig', [
                'form' => $form->createView(),
            ]);


        // Sinon, redirection vers la liste des actualités avec un message
        } else {
            $this->addFlash('notice', 'La nouvelle que vous essayez de modifier n\'existe pas.');
            return $this->redirectToRoute('newsletters', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }            
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/actualite/{id<\d+>}/agent/supprimer", name="newsletterDelete")
     */
    public function delete(string $locale, int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // On va chercher la newsletter a supprimer
        $newsletter = $this->newsletterRepo->findOneBy(["id" => $id]);

        // Si la news est trouvée       
        if (!is_null($newsletter)) {
            // On en décode les contenus fr et en
            $newsletter->setNewContentFr(htmlspecialchars_decode($newsletter->getNewContentFr()), ENT_QUOTES);
            $newsletter->setNewContentEn(htmlspecialchars_decode($newsletter->getNewContentEn()), ENT_QUOTES);    

            // Affichage de la question êtes-vous sure... ?
            return $this->render('newsletter/delete.html.twig', [
                'locale' => $locale,
                'newsletter' => $newsletter,
            ]);


        // Sinon, redirection vers la liste des actualités avec un message
        } else {
            $this->addFlash('notice', 'La nouvelle que vous essayez de supprimer n\'existe pas.');
            return $this->redirectToRoute('newsletters', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }            
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/actualite/{id<\d+>}/agent/suppression", name="newsletterDeletion")
     */
    public function deletion(string $locale, int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // On va chercher la newsletter a supprimer
        $newsletter = $this->newsletterRepo->findOneBy(["id" => $id]);

        // Si la news est trouvée
        if (!is_null($newsletter)) {
            // Suppression
            $doctrine->getManager()->remove($newsletter);
            $doctrine->getManager()->flush();
            $this->addFlash('notice', 'La nouvelle a bien été supprimée.');

            return $this->redirectToRoute('newsletters', [
                'locale' => $locale,
                'page' => 1,
            ]);


        // Sinon, redirection vers la liste des actualités avec un message
        } else {
            $this->addFlash('notice', 'La nouvelle que vous essayez de supprimer n\'existe pas.');
            return $this->redirectToRoute('newsletters', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }            
    }
}
