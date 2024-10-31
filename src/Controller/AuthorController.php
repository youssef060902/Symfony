<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends AbstractController
{   
  
    

    #[Route('/listauthors', name: 'listauthors')]
    public function listAuthors(ManagerRegistry $doctrine): Response
    {   $authorRepo=$doctrine->getRepository(Author::class);
            $authors=$authorRepo->findAll();
            return $this->render('author/listauthors.html.twig', [
            'authors' =>$authors,
        ]);
    }

   
    #[Route('/addauthor', name: 'add_author')]
    public function addauthor(ManagerRegistry $doctrine, Request $request): Response
    {
        // Récupérer l'entity manager
        $entityManager = $doctrine->getManager();

        // Créer une nouvelle instance d'Author avec des données statiques
        $author = new Author();
        $form =$this->createForm(AuthorType::class, $author);
        $form->add('gender', ChoiceType::class, [
            'choices' => [
                'Male' => 'Mr',
                'Female' => 'Mrs',
            ],
            'mapped' => false,
            'required' => false,
            'label' => 'Gender',
        ]);
        $form ->add('ajouter',SubmitType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            $username=$form->get('username')->getData();
            $prefix=$form->get('gender')->getData();
            $username=$prefix.' '.$username;
            $author->setUsername($username);

            $entityManager->persist($author);  
            $entityManager->flush(); 
            return $this->redirectToRoute('listauthors');
        }
        

        return $this->render('author/addauthor.html.twig', [
            'formauth' => $form->createView(),  
        ]);
    }
    
    #[Route('/author/{id}', name: 'show_author')]
    public function showAuthor($id , ManagerRegistry $doctrine): Response
    
    {
        $authorRepo = $doctrine->getRepository(Author::class);
        $author = $authorRepo->find($id);
    
        if (!$author) {
            throw $this->createNotFoundException('Author not found');
        }
    
        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }
    
    #[Route('/deleteauthor/{id}', name: 'delete_author')]
    public function deleteAuthor($id , ManagerRegistry $doctrine): Response
    
    {
        // Récupérer l'entity manager
        
        $authorRepo = $doctrine->getRepository(Author::class);
        $author = $authorRepo->find($id);
        $entityManager = $doctrine->getManager();
        

        // Enregistrer l'auteur dans la base de données
        $entityManager->remove($author);  // Prépare l'entité pour l'insertion
        $entityManager->flush();  
        
        return $this->redirectToRoute('listauthors');

    }

    #[Route('/editauthor/{id}', name: 'edit_author')]
    public function editauthor(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        // Récupérer l'entity manager
        $entityManager = $doctrine->getManager();

        // Récupérer l'auteur à partir de l'ID
        $author = $entityManager->getRepository(Author::class)->find($id);

        // Vérifier si l'auteur existe
        if (!$author) {
            throw $this->createNotFoundException('Author not found');
        }

        // Créer le formulaire lié à l'auteur existant
        $form = $this->createForm(AuthorType::class, $author);
        $form->add('modifier', SubmitType::class, [
            'label' => 'Update Author'
        ]);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->flush();

            
            return $this->redirectToRoute('listauthors');
        }

        
        return $this->render('author/addauthor.html.twig', [
            'formauth' => $form->createView(),
        ]);
    }

    #[Route('/authors/emails', name: 'author_emails')]
public function authorEmails(AuthorRepository $authorRepository): Response
{
    
    $emails = $authorRepository->findSortedEmails();

    
    return $this->render('author/emails.html.twig', [
        'emails' => $emails,
    ]);
}

#[Route('/author/{id}/books', name: 'books_by_author')]
public function listBooksByAuthor(BookRepository $bookRepository, int $id): Response
{
    
    $books = $bookRepository->findBooksByAuthorId($id);

    
    return $this->render('book/list_books_by_author.html.twig', [
        'books' => $books,
    ]);
}

#[Route('/search_author', name: 'search_author')]
    public function searchAuthor(Request $request, AuthorRepository $authorRepository): Response
    {
        $searchTerm = $request->query->get('search', '');  

        
        if ($searchTerm) {
            $authors = $authorRepository->searchByAuthorName($searchTerm);
        } else {
            $authors = [];
        }

        
        return $this->render('author/listauthors.html.twig', [
            'authors' => $authors,
        ]);
    }
    

    
}
