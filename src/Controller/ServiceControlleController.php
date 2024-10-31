<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceControlleController extends AbstractController
{
    #[Route('/service/controlle', name: 'app_service_controlle')]
    public function serviceIndex(): Response  // Renommée ici pour éviter le conflit
    {
        return $this->render('service_controlle/index.html.twig', [
            'controller_name' => 'ServiceControlleController',
        ]);
    }

    #[Route('/service/{name}', name: 'servicename')]
    public function showService($name): Response
    {
        return $this->render('service_controlle/showservice.html.twig', [
            'name' => $name
        ]);
    }

    #[Route('/gotoindex', name: 'gotoindex')]
    public function redirectToHome(): Response  // Renommée ici pour être plus descriptive
    {
        return $this->redirectToRoute('home');
    }
}
