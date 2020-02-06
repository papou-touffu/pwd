<?php

namespace App\Controller;

use App\Entity\Critique;
use App\Entity\Comment;
use App\Entity\Lexique;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\CritiqueType;
use App\Repository\CritiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;





class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="home")
     */
    public function index(CritiqueRepository $repo)
    {

        $critiques = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'critiques' => $critiques
        ]);
    }

    /**
     * @Route("/", name="blog")
     */
    public function home()
    {
       
       
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route("/critique", name="critique")
     */
    public function critique()
    {
        return $this->render('blog/critique.html.twig');
    }
    /**
     * @Route("/lexique", name="lexique")
     */
    public function lexique()
    {
        $repo =$this->getDoctrine()->getRepository(Lexique::class);
        $lexiques = $repo->findAll();
        return $this->render('blog/lexique.html.twig', ['controller_name' =>'BlogController',
            'lexiques' => $lexiques]);

    }

    /**
     * @Route("/profil/{id}", name="profil")
     */
    public function profil(User $user, Request $request)
    {
        return $this->render('blog/profil.html.twig', [
            'user' => $user
        ]);
    }
    /**
     * @Route("/connexions", name="connexion")
     */
    public function connexion()
    {
        return $this->render('blog/connexion.html.twig');
    }


    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("blog/{id}/edit" , name="blog_edit")
     */
    public function form(Critique $critique = null, Request $request, EntityManagerInterface $manager) {
        if(!$critique){
                        $critique =new Critique();
         }

        //Formulaire créer avec la console
        $form = $this->createForm(CritiqueType::class, $critique);
        $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){


             if(!$critique->getId()){
                 $critique->setCreatedAt(new \DateTime());
             }
             $file = $critique->getImage();
             $filename = md5(\uniqid()).'.'.$file->guessExtension();
             try{
                 $file->move(
                     $this->getParameter('uploads_directory'),
                     $filename
                 );
             } catch (FileException $e) {
                 //appel le manager pour (faire persister la recette et l'inclure dans la db)
             }
             $manager = $this->getDoctrine()->getManager();
             $critique->setImage($filename);
             $manager->persist($critique);
             $manager->flush();

             return $this->redirectToRoute('blog_show' , ['id' =>$critique->getId()]);
         }


        return $this->render('blog/create.html.twig', [
            'formCritique' => $form->createView(),
            'editMode' => $critique->getId() !== null
        ]);
    }

    /**
     * @Route("/blog/{id}", name ="blog_show")
     */
    public function show(critique $critique, Request $request, EntityManagerInterface $manager){
        $comment= new Comment();
        $form =$this->createForm(CommentType::class, $comment);
$form->handleRequest($request);
if($form->isSubmitted() && $form->isValid()){
    $comment->setCreatedAt(new \DateTime())
        ->setCritique($critique);
    $manager->persist($comment);
    $manager->flush();
    return $this->redirectToRoute('blog_show', ['id' => $critique->getId()]);
}
        return $this->render('blog/show.html.twig',[
            'critique'=>$critique,
            'commentForm' => $form->createView()
        ]);
    }

}
