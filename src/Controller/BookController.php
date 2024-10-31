<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{


    #[Route('/addbook', name: 'add_book')]
    public function addbook(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $book = new Book();
        $book->setEnabled(true);
        $form =$this->createForm(BookType::class, $book);
        $form ->add('ajouter',SubmitType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){

            $entityManager->persist($book);  
            $entityManager->flush(); 
            return $this->redirectToRoute('listbooks');
        }
        

        return $this->render('book/addbook.html.twig', [
            'formauthh' => $form->createView(),  
        ]);
    }

    #[Route('/listbooks', name: 'listbooks')]
    public function listBooks(ManagerRegistry $doctrine): Response
    {   $bookRepo=$doctrine->getRepository(Book::class);
            $books=$bookRepo->findAll();
            return $this->render('book/listbooks.html.twig', [
            'books' =>$books,
        ]);
    }

    #[Route('/book/{id}', name: 'show_book')]
    public function showBook($id , ManagerRegistry $doctrine): Response
    
    {
        $BookRepo = $doctrine->getRepository(Book::class);
        $Book = $BookRepo->find($id);
    
        if (!$Book) {
            throw $this->createNotFoundException('Book not found');
        }
    
        return $this->render('book/showbook.html.twig', [
            'book' => $Book,
        ]);
    }

    #[Route('/deletebook/{id}', name: 'delete_book')]
    public function deleteBook($id , ManagerRegistry $doctrine): Response
    
    {
        
        
        $bookRepo = $doctrine->getRepository(Book::class);
        $book = $bookRepo->find($id);
        $entityManager = $doctrine->getManager();
        

        
        $entityManager->remove($book);  
        $entityManager->flush();  
        
        return $this->redirectToRoute('listbooks');

    }


    #[Route('/editbook/{id}', name: 'edit_book')]
    public function editbook(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $entityManager = $doctrine->getManager();
    
        
        $book = $entityManager->getRepository(Book::class)->find($id);
    
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }
    
        
        $form = $this->createForm(BookType::class, $book, ['include_enabled' => true]);
        $form->add('modifier', SubmitType::class, [
            'label' => 'Update Book'
        ]);
    
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->flush();
    
            return $this->redirectToRoute('listbooks');
        }
    
        return $this->render('book/updatebook.html.twig', [
            'formauthh' => $form->createView(),
        ]);
    }

    #[Route('/author/{id}/books', name: 'books_by_author')]
public function booksByAuthor(ManagerRegistry $doctrine, $id): Response
{
   
    $author = $doctrine->getRepository(Author::class)->find($id);
    
    if (!$author) {
        throw $this->createNotFoundException('Author not found');
    }

   
    $books = $doctrine->getRepository(Book::class)->findBy(['author' => $author]);

    
    return $this->render('book/books_by_author.html.twig', [
        'author' => $author,
        'books' => $books,
    ]);
}

    
}
