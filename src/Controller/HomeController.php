<?php

namespace App\Controller;

use App\Repository\ActionRepository;
use App\Repository\NewsletterRepository;
use App\Service\Regex;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public const NUMBER_OF_NEWSLETTERS = 4;
    public const NUMBER_OF_ACTIONS = 4;
    private $newsletterRepo;
    private $actionRepo;

    public function __construct(NewsletterRepository $newsletterRepo, ActionRepository $actionRepo)
    {
        $this->newsletterRepo = $newsletterRepo;
        $this->actionRepo = $actionRepo;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        // Cherche si La session _locale existe. Si non, on la crée et configure en fr
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

        // Envoi des "x" dernieres newsletters dans le template
        $newsletters = $this->newsletterRepo->findLast(self::NUMBER_OF_NEWSLETTERS);
        // Envoi des "x" dernieres actions dans le template
        $actions = $this->actionRepo->findLast(self::NUMBER_OF_ACTIONS);

        $datas = [];
        foreach ($newsletters as $key => $value) {
            $datas[$key] = [
                'id' => $value->getId(),
                'newCreatedAt' => $value->getNewCreatedAt(),
                'newContentFr' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getNewContentFr(), ENT_QUOTES)),
                'newContentEn' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getNewContentEn(), ENT_QUOTES)),
                'thumb' => $regex->findFirstImage(htmlspecialchars_decode($value->getNewContentFr(), ENT_QUOTES)),
            ];
        }

        return $this->render('home/home.html.twig', [
            'newsletters' => $datas,
            'actions' => $actions,
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
            $request->getSession()->set('locale', 'fr');
            return $this->redirect("/");
        }
        
        // Stockage de la langue dans la session
        $request->getSession()->set('_locale', $locale); 

        // Si la page précédente n'existe pas alors on retourne l'url de l'accueil, 
        $url = (!is_null($request->headers->get('referer'))) ? $request->headers->get('referer') : "/";

        // si elle existe alors on retourne à la page précédente en changeant /{locale}/.../... par la nouvelle locale
        $tab = explode("/", $url);
        for ($i=0; $i < count($tab); $i++) { 
            if ($tab[$i] == $lastLocale) {
                $tab[$i] = $request->getSession()->get('_locale');
                break;
            }
        }
        $url = implode("/", $tab);

        // Redirection vers l'url
        return $this->redirect($url);
    }
}
