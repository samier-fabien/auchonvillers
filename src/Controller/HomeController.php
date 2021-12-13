<?php

namespace App\Controller;

use App\Repository\ActionRepository;
use App\Repository\NewsletterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(): Response
    {
        $userEmail = ($this->getUser()) ? $this->getUser()->getEmail() : "";
        $newsletters = $this->newsletterRepo->findLast(self::NUMBER_OF_NEWSLETTERS);
        $actions = $this->actionRepo->findLast(self::NUMBER_OF_ACTIONS);

        return $this->render('home/home.html.twig', [
            'newsletters' => $newsletters,
            'actions' => $actions,
            'userEmail' => $userEmail,
        ]);
    }
}
