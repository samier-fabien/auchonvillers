<?php

namespace App\Controller;

use DateTime;
use App\Entity\Events;
use App\Service\Regex;
use App\Entity\Attends;
use App\Form\EventsType;
use App\Repository\AttendsRepository;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Translation\Translator;

class EventsController extends AbstractController
{
    public const EVENTS_PER_PAGE = 4;
    private $eventsRepo;
    private $translator;

    public function __construct(EventsRepository $eventsRepo, TranslatorInterface $translator) {
        $this->eventsRepo = $eventsRepo;
        $this->translator = $translator;
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
            $content = 'getEveContent' . ucFirst($locale);

            $eventsDatas[$key] = [
                'id' => $value->getId(),
                'createdAt' => $value->getEveCreatedAt(),
                'begining' => $value->getEveBegining(),
                'end' => $value->getEveEnd(),
                'content' => $regex->removeHtmlTags(htmlspecialchars_decode($value->$content(), ENT_QUOTES)),
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

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('events_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('events_index', [
                'locale'=> $request->getSession()->get('_locale'),
                'page' => 1,
            ]);
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
     * @Route("/{locale}/evenement/{id<\d+>}", name="events_show", methods={"GET", "POST"})
     */
    public function show(string $locale, int $id, Request $request, ManagerRegistry $doctrine, AttendsRepository $attendsRepo): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Recherche de l'évènement avec l'id "x"
        $event = $this->eventsRepo->findOneBy(["id" => $id]);

        // Si l'évènement n'existe pas, redirection vers la liste des évènements avec un message
        if (is_null($event)) {
            $message = $this->translator->trans('L\'évènement que vous essayez de consulter n\'existe pas.');
            $this->addFlash('notice', $message);
            return $this->redirectToRoute('events_index', [
                'locale' => $locale,
                'page' => 1,
            ]);
        }  

        $participations = count($attendsRepo->findBy(['event' => $event->getId()]));

        $content = 'getEveContent' . ucFirst($locale);

        $eventsDatas = [
            'id' => $event->getId(),
            'createdAt' => $event->getEveCreatedAt(),
            'begining' => $event->getEveBegining(),
            'end' => $event->getEveEnd(),
            'content' => htmlspecialchars_decode($event->$content(), ENT_QUOTES),
        ];

        $attend = new Attends();
        $attend->setEvent($event);

        $form = $this->createFormBuilder($attend)
            ->add('save', SubmitType::class, [
                'label' => $this->translator->trans('Participer à l\'évènement'),
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        // Si le formulaire est bien rempli...
        if ($form->isSubmitted() && $form->isValid()) {

            // Si le csrf est invalide
            if (!$this->isCsrfTokenValid('participate-item'.$event->getId(), $request->request->get('token'))) {
                $this->addFlash('danger', $this->translator->trans('Formulaire non autorisé.'));
                
                return $this->redirectToRoute('events_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $event->getId(),
                ]);
            }

            // Si l'utilisateur n'est pas connecté
            if (is_null($this->getUser())) {
                $this->addFlash('notice', 'Vous devez être connecté pour indiquer votre participation à l\'évènement.');

                return $this->redirectToRoute('events_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $event->getId(),
                ]);
            }

            // Si l'utilisateur n'est pas vérifié
            if (!$this->getUser()->isVerified()) {
                $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
                return $this->redirectToRoute('events_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $id,
                ]);
            }

            // Ajout de l'utilisateur
            $attend->setUser($this->getUser());

            // Si l'utilisateur participe déja
            if (count($attendsRepo->findPerEventAndUser($id, $this->getUser()->getId())) >= 1) {
                $this->addFlash('notice', 'Vous participez déja à l\'évènement.');

                return $this->redirectToRoute('events_show', [
                    'locale'=> $request->getSession()->get('_locale'),
                    'id' => $event->getId(),
                ]);
            }

            // Si tout est ok : on enregistre le vote
            $doctrine->getManager()->persist($attend);
            $doctrine->getManager()->flush();

            // Ajout de message de succes et redirection vers la news qui vient d'être modifié
            $this->addFlash('success', 'Vous avez indiqué participer à l\'évènement.');

            return $this->redirectToRoute('events_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $event->getId(),
            ]);
        }

        return $this->render('events/show.html.twig', [
            'event' => $eventsDatas,
            'form' => $form->createView(),
            'participations' => $participations,
        ]);
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

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('events_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('events_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
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

        // Si l'utilisateur n'est pas vérifié
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir confirmé votre email pour accéder à cette fonctionnalité.'));
            return $this->redirectToRoute('events_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
        }

        // Si l'utilisateur n'a pas accepté les conditions d'utilisation pour les employés
        if (!$this->getUser()->getEmployeeTermsOfUse()) {
            $this->addFlash('warning', $this->translator->trans('Vous devez avoir accepté les conditions d\'utilisation pour les employés.'));
            return $this->redirectToRoute('events_show', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $id,
            ]);
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
