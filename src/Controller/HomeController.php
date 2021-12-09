<?php

namespace App\Controller;

use App\Repository\ActionRepository;
use App\Repository\NewsletterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
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
    public function index(): Response
    {
        $newsletters = $this->newsletterRepo->findLast(4);
        $actions = $this->actionRepo->findLast(4);
        $userEmail = ($this->getUser()) ? $this->getUser()->getEmail() : "";

        return $this->render('home/index.html.twig', [
            'newsletters' => $newsletters,
            'actions' => $actions,
            'userEmail' => $userEmail,
        ]);
    }
}
