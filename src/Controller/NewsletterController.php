<?php

namespace App\Controller;

use App\Repository\NewsletterRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsletterController extends AbstractController
{
    private $newsletterRepo;

    public function __construct(NewsletterRepository $newsletterRepo)
    {
        $this->newsletterRepo = $newsletterRepo;
    }

    /**
     * @Route("/actualites", name="newsletters")
     */
    public function displayAll(): Response
    {
        $userEmail = ($this->getUser()) ? $this->getUser()->getEmail() : "";
        $newsletters = $this->newsletterRepo->findAll();

        return $this->render('newsletter/displayAll.html.twig', [
            'userEmail' => $userEmail,
            'newsletters' => $newsletters,
        ]);
    }

    /**
     * @Route("/actualite/{id}", name="newsletter")
     */
    public function display($id): Response
    {
        $userEmail = ($this->getUser()) ? $this->getUser()->getEmail() : "";
        $newsletter = $this->newsletterRepo->findOneBy(["id" => $id]);
        //dd($newsletter);

        return $this->render('newsletter/display.html.twig', [
            'userEmail' => $userEmail,
            'newsletter' => $newsletter,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/actualites/agent/creer", name="newsletterCreate")
     */
    public function create(): Response
    {
        $userEmail = ($this->getUser()) ? $this->getUser()->getEmail() : "";

        return $this->render('newsletter/create.html.twig', [
            'userEmail' => $userEmail,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/actualites/agent/editer", name="newsletterUpdate")
     */
    public function update(): Response
    {
        $userEmail = ($this->getUser()) ? $this->getUser()->getEmail() : "";

        return $this->render('newsletter/update.html.twig', [
            'userEmail' => $userEmail,
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/actualites/agent/supprimer", name="newsletterDelete")
     */
    public function delete(): Response
    {
        $userEmail = ($this->getUser()) ? $this->getUser()->getEmail() : "";

        return $this->render('newsletter/creation.html.twig', [
            'userEmail' => $userEmail,
        ]);
    }
}
