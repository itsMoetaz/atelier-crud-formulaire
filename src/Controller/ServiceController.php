<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service/{name}', name: 'show_service')]
    public function showService($name): Response
    {
        
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 0),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),);
            return $this->render('service/showService.html.twig',  [ 'name' =>  $name , 'tab' => $authors ] );
    }
    public function goToIndex(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/detail/{authorName}', name: 'detail')]
    public function showdetail($authorName): Response
    {
        return new Response ('authors'.$authorName);
    }

}
