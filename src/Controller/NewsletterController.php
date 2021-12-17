<?php

namespace App\Controller;

use DateTime;
use App\Entity\Newsletter;
use App\Form\NewsletterType;
use PhpParser\Node\Expr\New_;
use App\Repository\NewsletterRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function displayAll(int $page = 1): Response
    {
        $newsletters = $this->newsletterRepo->findByPage($page, self::NEWSLETTERS_PER_PAGE);
        $pages = (int) ceil($this->newsletterRepo->getnumber() / self::NEWSLETTERS_PER_PAGE);

        return $this->render('newsletter/displayAll.html.twig', [
            'newsletters' => $newsletters,
            'page' => $page,
            'pages' => $pages,
            'total' => $pages,
            'current' => $page,
        ]);
    }

    /**
     * @Route("/{locale}/actualite/{id<\d+>}", name="newsletter")
     */
    public function display(Request $request, $id): Response
    {
        $newsletter = $this->newsletterRepo->findOneBy(["id" => $id]);

        return $this->render('newsletter/display.html.twig', [
            'newsletter' => $newsletter,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/actualite/agent/creer", name="newsletterCreate")
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $newsletter = new Newsletter();
        $newsletter->setNewCreatedAt(new DateTime());
        $newsletter->setUser($this->getUser());

        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->persist($newsletter);
            $doctrine->getManager()->flush();
            $this->addFlash('success', 'Votre nouvelle a été créée et ajoutée dans le fil d\'actualités');
            return $this->redirectToRoute('newsletter', [
                'locale'=> $request->getSession()->get('_locale'),
                'id' => $newsletter->getId(),
            ]);
        }


        return $this->render('newsletter/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/actualites/{id<\d+>}/agent/editer", name="newsletterUpdate")
     */
    public function update(): Response
    {
        $userEmail = ($this->getUser()) ? $this->getUser()->getEmail() : null;

        

        return $this->render('newsletter/update.html.twig', [
            'userEmail' => $userEmail,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/{locale}/actualites/agent/supprimer", name="newsletterDelete")
     */
    public function delete(): Response
    {
        $userEmail = ($this->getUser()) ? $this->getUser()->getEmail() : null;

        return $this->render('newsletter/creation.html.twig', [
            'userEmail' => $userEmail,
        ]);
    }
}
