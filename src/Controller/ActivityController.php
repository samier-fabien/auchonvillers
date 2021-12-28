<?php

namespace App\Controller;

use DateTime;
use App\Entity\Event;
use App\Entity\Action;
use App\Service\Regex;
use App\Form\ActionType;
use App\Repository\ActionRepository;
use App\Repository\EventsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActivityController extends AbstractController
{
    public const ACTION_PER_PAGE = 4;
    private $eventsRepo;

    public function __construct(EventsRepository $eventsRepo) { 
        $this->eventsRepo = $eventsRepo;
    }

    /**
     * @Route("/{locale}/evenements/{page<\d+>}", name="actions")
     */
    public function index(string $locale, int $page = 1, Request $request, Regex $regex): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche des newsletters dans la bdd
        $actions = $this->actionRepo->findByPage($page, self::ACTION_PER_PAGE);

        // Calcul du nombres de pages en fonction du nombre de news par page
        $pages = (int) ceil($this->actionRepo->getnumber() / self::ACTION_PER_PAGE);

        // Le tableau datas contient toutes les données des actions
        // Utilité :
        //  -retourne des données décodées vias htmlSpecialChars
        //  -ajoute un thumbnail basé sur la première image trouvé dans l'action fr (seule la version fr est obligatoire dans le formulaire)
        //  -le texte est une version de "x" caractères (twig) sans les tags html créés avec ckeditor : pas besoin de 'titre' ni de 'chemin vers une image' en bdd
        $actionsDatas = [];
        foreach ($actions as $key => $value) {
            $actionsDatas[$key] = [
                'id' => $value->getId(),
                'actCreatedAt' => $value->getActCreatedAt(),
                'actBegining' => $value->getActBegining(),
                'actEnd' => $value->getActEnd(),
                'actContentFr' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getActContentFr(), ENT_QUOTES)),
                'actContentEn' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getActContentEn(), ENT_QUOTES)),
                'thumb' => $regex->findFirstImage(htmlspecialchars_decode($value->getActContentFr(), ENT_QUOTES)),
            ];
        }

        return $this->render('action/displayAll.html.twig', [
            'actions' => $actionsDatas,
            'page' => $page,
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/{locale}/evenement/{id<\d+>}", name="action")
     */
    public function display(string $locale, int $id, Request $request, TranslatorInterface $translator): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }
        
        // Recherche de la news avec l'id "x"
        $action = $this->actionRepo->findOneBy(["id" => $id]);

        // Si la news est trouvée
        if (!is_null($action)) {
            // On en décode les contenus fr et en
            $action->setActContentFr(htmlspecialchars_decode($action->getActContentFr()), ENT_QUOTES);
            $action->setActContentEn(htmlspecialchars_decode($action->getActContentEn()), ENT_QUOTES);

            return $this->render('action/display.html.twig', [
                'action' => $action,
            ]);
        // Sinon, redirection vers la liste des actualités avec un message
        } else {
            $message = $translator->trans('L\'évènement que vous essayez de consulter n\'existe pas.');
            $this->addFlash('notice', $message);
            return $this->redirectToRoute('actions', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/evenement/agent/creer", name="actionCreate")
     */
    public function create(string $locale, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }
        
        // Création d'un nouvel objet action qu'on pré-rempli avec la date actuelle et l'utilisateur actuel
        $action = new Action();
        $action->setactCreatedAt(new DateTime());
        $action->setUser($this->getUser());
        $event = new Event();
        $event->setAction($action);
        //$action->setEvent($event);
        //$action->getTags()->add($tag1);

        

        // Créaion du formulaire
        $form = $this->createForm(ActionType::class, $action);
        $form->handleRequest($request);

        // Si formulaire soumis et correctement rempli...
        if ($form->isSubmitted() && $form->isValid()) {
            // ... alors on encode les contenus fr et en avant de les ajouter en bdd
            $action->setActContentFr(htmlspecialchars($action->getActContentFr(), ENT_QUOTES));
            $action->setActContentEn(htmlspecialchars($action->getActContentEn(), ENT_QUOTES));
            //$doctrine->getManager()->persist($action);
            //$doctrine->getManager()->flush();
            // Ajout de message de succes et redirection vers la news qui vient d'être publiée
            $this->addFlash('success', 'Votre évènement a été créée et ajoutée dans la liste');
            return $this->redirectToRoute('action', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $action->getId(),
            ]);
        }


        return $this->render('action/actionCreate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
