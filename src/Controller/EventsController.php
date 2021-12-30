<?php

namespace App\Controller;

use DateTime;
use App\Entity\Events;
use App\Service\Regex;
use App\Form\EventsType;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventsController extends AbstractController
{
    public const EVENTS_PER_PAGE = 4;
    private $eventsRepo;

    public function __construct(EventsRepository $eventsRepo) {
        $this->eventsRepo = $eventsRepo;
    }

    /**
     * @Route("/{locale}/evenements/{page<\d+>}", name="events_index", methods={"GET"})
     */
    public function index(string $locale, int $page = 1, Request $request, Regex $regex): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche des evenements dans la bdd
        $events = $this->eventsRepo->findByPage($page, self::EVENTS_PER_PAGE);
        $eventsDatas = [];
        foreach ($events as $key => $value) {
            $eventsDatas[$key] = [
                'id' => $value->getId(),
                'createdAt' => $value->getEveCreatedAt(),
                'begining' => $value->getEveBegining(),
                'end' => $value->getEveEnd(),
                'contentFr' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getEveContentFr(), ENT_QUOTES)),
                'contentEn' => $regex->removeHtmlTags(htmlspecialchars_decode($value->getEveContentEn(), ENT_QUOTES)),
                'thumb' => $regex->findFirstImage(htmlspecialchars_decode($value->getEveContentFr(), ENT_QUOTES)),
            ];
        }

        // Calcul du nombres de pages en fonction du nombre d'evenements par page
        $pages = (int) ceil($this->eventsRepo->getnumber() / self::EVENTS_PER_PAGE);
        
        return $this->render('events/index.html.twig', [
            'events' => $eventsDatas,
            'page' => $page,
            'pages' => $pages,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/evenement/agent/creer", name="events_new", methods={"GET", "POST"})
     */
    public function new(string $locale, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        $event = new Events();
        $event->setEveCreatedAt(new DateTime());
        $event->setUser($this->getUser());

        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setEveContentFr(htmlspecialchars($event->getEveContentFr(), ENT_QUOTES));
            $event->setEveContentEn(htmlspecialchars($event->getEveContentEn(), ENT_QUOTES));
            $doctrine->getManager()->persist($event);
            $doctrine->getManager()->flush();
            $this->addFlash('success', 'Votre évènement a été créé et ajouté à la liste');
            return $this->redirectToRoute('events_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $event->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('events/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{locale}/evenement/{id<\d+>}", name="events_show", methods={"GET"})
     */
    public function show(string $locale, int $id, Request $request, TranslatorInterface $translator): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche de l'évènement avec l'id "x"
        $event = $this->eventsRepo->findOneBy(["id" => $id]);

        // Si l'évènement est trouvée
        if (!is_null($event)) {
            // On en décode les contenus fr et en
            $event->setEveContentFr(htmlspecialchars_decode($event->getEveContentFr()), ENT_QUOTES);
            $event->setEveContentEn(htmlspecialchars_decode($event->getEveContentEn()), ENT_QUOTES);

            return $this->render('events/show.html.twig', [
                'event' => $event,
            ]);
        // Sinon, redirection vers la liste des évènements avec un message
        } else {
            $message = $translator->trans('L\'évènement que vous essayez de consulter n\'existe pas.');
            $this->addFlash('notice', $message);
            return $this->redirectToRoute('events_index', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }  
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/evenement/{id<\d+>}/agent/editer", name="events_edit", methods={"GET", "POST"})
     */
    public function edit(string $locale = 'fr', int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // On va chercher l'evenement a modifier
        $event = $this->eventsRepo->findOneBy(["id" => $id]);

        // Si l'evenement est trouvée
        if (!is_null($event)) {
            // On en décode les contenus fr et en
            $event->setEveContentFr(htmlspecialchars_decode($event->getEveContentFr()), ENT_QUOTES);
            $event->setEveContentEn(htmlspecialchars_decode($event->getEveContentEn()), ENT_QUOTES);    

            // Création du formulaire
            $form = $this->createForm(EventsType::class, $event);
            $form->handleRequest($request);

            // Si le formulaire est bien rempli..
            if ($form->isSubmitted() && $form->isValid()) {
                // ... on encode les contenus fr et en avant de les ajouter en bdd
                $event->setEveContentFr(htmlspecialchars($event->getEveContentFr(), ENT_QUOTES));
                $event->setEveContentEn(htmlspecialchars($event->getEveContentEn(), ENT_QUOTES));
                $doctrine->getManager()->persist($event);
                $doctrine->getManager()->flush();

                // Ajout de message de succes et redirection vers la news qui vient d'être modifié
                $this->addFlash('success', 'Votre évènement a été créé');

                return $this->redirectToRoute('events_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $event->getId(),
                ]);
            }

            return $this->render('events/edit.html.twig', [
                'event' => $event,
                'form' => $form->createView(),
            ]);

        } else {
            $this->addFlash('notice', 'L\'évènement que vous essayez de modifier n\'existe pas.');
            return $this->redirectToRoute('events_index', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }   
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/evenement/{id<\d+>}/agent/supprimer", name="events_delete", methods={"POST"})
     */
    public function delete(string $locale, int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // On va chercher la newsletter a supprimer
        $event = $this->eventsRepo->findOneBy(["id" => $id]);


        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            // Suppression
            $doctrine->getManager()->remove($event);
            $doctrine->getManager()->flush();
            $this->addFlash('notice', 'L\'évènement a bien été supprimée.');
        }

        return $this->redirectToRoute('events_index', [
            'locale' => $request->getSession()->get('_locale'),
            'page' => 1,
        ], Response::HTTP_SEE_OTHER);
    }
}
