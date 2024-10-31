<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController {
    #[Route('/home', name:'home')]
    public function index(){
        return new Response('<html><body><h1>Hello world </h1></body></html');
    }
}