<?php

namespace App\Controller;

use App\Service\Regex;
use App\Repository\EventsRepository;
use App\Repository\NewsletterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public const NUMBER_OF_NEWSLETTERS = 4;
    public const NUMBER_OF_ACTIONS = 4;
    private $newsletterRepo;
    private $eventsRepo;

    public function __construct(NewsletterRepository $newsletterRepo, EventsRepository $eventsRepo)
    {
        $this->newsletterRepo = $newsletterRepo;
        $this->eventsRepo = $eventsRepo;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        // Cherche si La session _locale existe. Sinon, on la crée et configure en fr
        if (!$request->getSession()->get('_locale')) {
            $request->getSession()->set('_locale', 'fr'); 
        }

        // Si la _locale n'est pas dans la liste de app.locales alors on la reconfigure en fr
        if (!in_array($request->getSession()->get('_locale'), $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
        }
        return $this->redirect("/" . $request->getSession()->get('_locale'));
    }

    /**
     * @Route("/{locale}", name="home")
     */
    public function home(string $locale, Request $request, Regex $regex): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche des "x" dernieres newsletters dans la bdd
        $newsletters = $this->newsletterRepo->findLast(self::NUMBER_OF_NEWSLETTERS);

        // Recherche des "x" dernieres actions dans la bdd
        $actions = $this->eventsRepo->findLast(self::NUMBER_OF_ACTIONS);

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

        // Le tableau datas contient toutes les données des actions
        // Utilité :
        //  -retourne des données décodées vias htmlSpecialChars
        //  -ajoute un thumbnail basé sur la première image trouvé dans l'action fr (seule la version fr est obligatoire dans le formulaire)
        //  -le texte est une version de "x" caractères (twig) sans les tags html créés avec ckeditor : pas besoin de 'titre' ni de 'chemin vers une image' en bdd
        $actionsDatas = [];
        foreach ($actions as $key => $value) {
            $actionsDatas[$key] = [
                'id' => $value->getId(),
                'actCreatedAt' => $value->getEveCreatedAt(),
                'actBegining' => $value->getEveBegining(),
                'actEnd' => $value->getEveEnd(),
                'actContentFr' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getEveContentFr(), ENT_QUOTES)),
                'actContentEn' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getEveContentEn(), ENT_QUOTES)),
                'thumb' => $regex->findFirstImage(htmlspecialchars_decode($value->getEveContentFr(), ENT_QUOTES)),
            ];
        }

        return $this->render('home/home.html.twig', [
            'newsletters' => $newsDatas,
            'actions' => $actionsDatas,
        ]);
    }

    /**
     * @Route("/langue/{locale}", name="locale")
     */
    public function lang($locale, Request $request): Response
    {
        // On stocke la valeur de la locale avant changement
        $lastLocale = (in_array($request->getSession()->get('_locale'), $this->getParameter('app.locales'), true)) ? $request->getSession()->get('_locale') : "fr";

        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }
        
        // Stockage de la langue dans la session
        $request->getSession()->set('_locale', $locale); 

        // Si la page précédente n'existe pas alors on retourne l'url de l'accueil, 
        $url = (!is_null($request->headers->get('referer'))) ? $request->headers->get('referer') : "/";

        // si elle existe alors on retourne à la page précédente en changeant /{locale}/.../... par la nouvelle locale et...
        $tab = explode("/", $url);
        for ($i=0; $i < count($tab); $i++) { 
            if ($tab[$i] == $lastLocale) {
                $tab[$i] = $request->getSession()->get('_locale');
                break;
            }
        }
        $url = implode("/", $tab);

        // ... on redirige vers l'url
        return $this->redirect($url);
    }
}
