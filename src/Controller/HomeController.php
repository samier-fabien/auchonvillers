<?php

namespace App\Controller;

use App\Repository\ActionRepository;
use App\Repository\NewsletterRepository;
use Locale;
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
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        // Envoi de l'email de l'utilisateur dans template ou null s'il n'existe pas
        $userEmail = ($this->getUser()) ? $this->getUser()->getEmail() : null;
        // Envoi des "x" dernieres newsletters dans le template
        $newsletters = $this->newsletterRepo->findLast(self::NUMBER_OF_NEWSLETTERS);
        // Envoi des "x" dernieres actions dans le template
        $actions = $this->actionRepo->findLast(self::NUMBER_OF_ACTIONS);

        return $this->render('home/home.html.twig', [
            'newsletters' => $newsletters,
            'actions' => $actions,
            'userEmail' => $userEmail,
        ]);
    }

    /**
     * @Route("/langue/{locale}", name="locale")
     */
    public function lang($locale, Request $request): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        $this->getParameter('app.locales');
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            $request->getSession()->set('locale', 'fr');
            return $this->redirect("/");
        }
        
        // Stockage de la langue dans la session
        $request->getSession()->set('_locale', $locale); 
        $request->getSession()->set('locale', $locale);

        // On revient à la page précédente si elle est dans le referer sinon vers l'accueil
        $url = (!is_null($request->headers->get('referer'))) ? $request->headers->get('referer') : "/";
        
        return $this->redirect($url);
    }
}
