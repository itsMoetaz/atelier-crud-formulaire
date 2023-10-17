<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Classroom;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    #[Route('/fetch', name: 'fetch')]
    public function fetch(StudentRepository $repo):Response
    {
        $result=$repo->findALL();
        return $this->render('student/index.html.twig', [
            'response' => $result,
        ]);
    }
    #[Route('/add', name: 'add')]
    public function add(ManagerRegistry $mr):Response
    {
        $s=new Student();
        $s->setName('test');
        $s->setEmail('test@gmail.com');
        $s->setAge('22');
        

        $em=$mr->getManager();
        $em->persist($s);
        $em->flush();

        return new Response("added");
    }


  
    
    
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, ManagerRegistry $mr):Response
    {
        $em=$mr->getManager();
        $repository = $em->getRepository(Student::class); 

        $student = $repository->find($id);

        if (!$student) {
            return new Response("Étudiant introuvable", 404);
        }

        $em->remove($student);
        $em->flush();

        return $this->redirectToRoute('fetch');
    }
    



    #[Route('/addF', name: 'addF')]
    public function addF(Request $req, ManagerRegistry $doctrine): Response
    {
       //objet à insérer
        $s=new Student();
        //instancier la classe du formulaire
        $form=$this->createForm(StudentType::class, $s);
        //$form->add('Save_me', SubmitType::class);
        //form is submitted or not + remplissage de l'objet $a
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $em=$doctrine->getManager();
            //créer la requête d'ajout
            $em->persist($s);
          
            //exécuter la requête
            $em->flush();
            return $this->redirectToRoute('fetch');
        }
       
        return $this->render("home/index.html.twig", ['formulaire'=>$form->createView()]);
    }

    #[Route('/update/{id}',name:'update')]
    public function update(int $id, Request $request, ManagerRegistry $mr): Response
    {
        $em = $mr->getManager();
        $student = $em->getRepository(Student::class)->find($id);

        if (!$student) {
            throw $this->createNotFoundException('Étudiant introuvable');
        }

        // Récupérer les données du formulaire soumis et mettre à jour l'étudiant
        $form = $this->createForm(StudentType::class, $student);
      $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('fetch'); // Rediriger vers la liste des étudiants après la mise à jour
        }

        return $this->render('student/update.html.twig', [
            'form' => $form->createView(),
        ]);

//    return $this->redirectToRoute('fetch');
}
    
}
