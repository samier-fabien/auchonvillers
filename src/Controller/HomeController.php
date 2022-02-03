<?php

namespace App\Controller;

use DateTime;
use App\Service\Regex;
use App\Service\ArraySort;
use App\Repository\UserRepository;
use App\Repository\VotesRepository;
use App\Repository\EventsRepository;
use App\Repository\SurveysRepository;
use App\Repository\NewsletterRepository;
use App\Service\Imagine;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public const NUMBER_OF_NEWSLETTERS = 4;
    public const NUMBER_OF_ACTIVITIES = 4;
    private $newsletterRepo;
    private $eventsRepo;
    private $surveysRepo;
    private $votesRepo;
    private $translator;

    public function __construct(NewsletterRepository $newsletterRepo, EventsRepository $eventsRepo, SurveysRepository $surveysRepo, VotesRepository $votesRepo, TranslatorInterface $translator)
    {
        $this->newsletterRepo = $newsletterRepo;
        $this->eventsRepo = $eventsRepo;
        $this->surveysRepo = $surveysRepo;
        $this->votesRepo = $votesRepo;
        $this->translator = $translator;
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
    public function home(string $locale, Request $request, Regex $regex, ArraySort $arraySort, UserRepository $userRepo, Imagine $imagine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }
        
        

        // Le tableau datas contient toutes les données des newsletters
        // Utilité :
        //  -tri selon la langue avec appel de no de fonction dynamique
        //  -retourne les données décodées vias htmlSpecialChars
        //  -ajoute un thumbnail basé sur la première image trouvé dans la news fr (seule la version fr est obligatoire dans le formulaire)
        //  -le texte est retourné sans les tags html créés avec ckeditor : pas besoin de 'titre' ni de 'chemin vers une image' en bdd
        $newslettersDatas = [
            'newslettersTitle' => $this->translator->trans('Dernières actualités'),
            'newslettersButton' => $this->translator->trans('Toutes les actualités'),
            'newslettersButtonAlt' => $this->translator->trans('Lien vers la liste des actualités'),
            'newslettersButtonPath' => "/" . $locale . "/actualites/1",
        ];

        // Recherche des "x" dernieres newsletters dans la bdd
        foreach ($this->newsletterRepo->findLast(self::NUMBER_OF_NEWSLETTERS) as $key => $value) {
            $getContent = 'getNewContent' . ucFirst($locale);
            
            $newslettersDatas['newslettersList'][$key] = [
                'id' => $value->getId(),
                'createdAt' => $this->translator->trans('Le ') . $value->getNewCreatedAt()->format('d-m-Y'),
                'content' => $regex->textTruncate($regex->removeHtmlTags(htmlspecialchars_decode($value->$getContent(), ENT_QUOTES)), 58),
                'thumb' => $imagine->toSquareFourHundreds($regex->findFirstImage(htmlspecialchars_decode($value->getNewContentFr(), ENT_QUOTES))),
                'alt' => $this->translator->trans('Image introductive'),
                'button' => $this->translator->trans('Voir'),
                'link' => "/" . $locale . "/actualite/" . $value->getId(),
            ];
        }


        // Recherche des "x" derniers evenements dans la bdd
        $eventsDatas = [];
        foreach ($this->eventsRepo->findLast(self::NUMBER_OF_ACTIVITIES) as $key => $value) {
            $getContent = 'getEveContent' . ucFirst($locale);

            $eventsDatas[$key] = [
                'id' => $value->getId(),
                'createdAt' => $value->getEveCreatedAt(),
                'begining' => $value->getEveBegining(),
                'end' => $value->getEveEnd(),
                'content' => $regex->removeHtmlTags(htmlspecialchars_decode($value->$getContent(), ENT_QUOTES)),
                'image' => $imagine->toSquareFourHundreds($regex->findFirstImage(htmlspecialchars_decode($value->getEveContentFr(), ENT_QUOTES))),
                'url' => 'evenement'
            ];
        }
        
        // Recherche des "x" dernieres enquetes dans la bdd
        $surveysDatas = [];
        foreach ($this->surveysRepo->findLast(self::NUMBER_OF_ACTIVITIES) as $key => $value) {
            $getContent = 'getSurContent' . ucFirst($locale);

            $surveysDatas[$key] = [
                'id' => $value->getId(),
                'createdAt' => $value->getSurCreatedAt(),
                'begining' => $value->getSurBegining(),
                'end' => $value->getSurEnd(),
                'content' => $regex->removeHtmlTags(htmlspecialchars_decode($value->$getContent(), ENT_QUOTES)),
                'image' => $imagine->toSquareFourHundreds($regex->findFirstImage(htmlspecialchars_decode($value->getSurContentFr(), ENT_QUOTES))),
                'url' => 'enquete'
            ];
        }

        // Recherche des "x" dernieres votes dans la bdd
        $votesDatas = [];
        foreach ($this->votesRepo->findLast(self::NUMBER_OF_ACTIVITIES) as $key => $value) {
            $getContent = 'getVotContent' . ucFirst($locale);

            $votesDatas[$key] = [
                'id' => $value->getId(),
                'createdAt' => $value->getVotCreatedAt(),
                'begining' => $value->getVotBegining(),
                'end' => $value->getVotEnd(),
                'content' => $regex->removeHtmlTags(htmlspecialchars_decode($value->$getContent(), ENT_QUOTES)),
                'image' => $imagine->toSquareFourHundreds($regex->findFirstImage(htmlspecialchars_decode($value->getVotContentFr(), ENT_QUOTES))),
                'url' => 'vote'
            ];
        }

        $datas['activitiesCards'] = $arraySort->sortLastsByDatetime(array_merge($eventsDatas, $surveysDatas, $votesDatas), self::NUMBER_OF_ACTIVITIES);

        return $this->render('home/home.html.twig', [
            //'newsletters' => $newslettersDatas,
            'datas' => $datas,
            'jsonnewsletters' => json_encode($newslettersDatas),
            'newslettersTitle' => $this->translator->trans('Dernières actualités'),
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
