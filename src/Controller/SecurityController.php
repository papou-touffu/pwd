<?php

namespace App\Controller;
use App\Entity\Comment;
use App\Entity\Critique;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\CommentRepository;
use App\Repository\CritiqueRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription",name ="security_registration")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion",name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
        // $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/security_admin", name="security_admin")
     */
    public function admin()
    {
        return $this->render('security/admin.html.twig');
    }

    /**
     * @Route("/security_admin/list-critique", name="list-critique")
     * @param CritiqueRepository $critiqueRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function critiqueListAdmin(CritiqueRepository $critiqueRepository)
    {
        // j'appelle tous les articles via le repository des Articles
        $critiques = $critiqueRepository->findAll();

        // je les affiche tous
        return $this->render('security/postListAdmin.html.twig', [
            'critiques' => $critiques
        ]);
    }
    // function qui permet de supprimer les articles

    /**
     * @Route("/security_admin/critique/{id}", name="removeCritique", methods={"GET"})
     * @param Critique $critique
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function removeCritique(Critique $critique, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($critique);
        $em->flush();

        return $this->redirectToRoute("list-critique");
    }

    /**
     * @Route("/security_admin/list-comment", name="list-comment")
     * @param CommentRepository $commentRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentListAdmin(CommentRepository $commentRepository)
    {

        $comments = $commentRepository->findAll();


        return $this->render('security/postListComment.html.twig', [
            'comments' => $comments
        ]);
    }
    /**
     * @Route("/security_admin/comment/{id}", name="removeComment", methods={"GET"})
     */
    public function removeComment(Comment $comment, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute("list-comment");
    }

    /**
     * @Route("/security_admin/list-user", name="list-user")
     */
    public function userListAdmin(UserRepository $userRepository)
    {

        $users = $userRepository->findAll();


        return $this->render('security/postListMember.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/security_admin/user/{id}", name="removeUser", methods={"GET"})
     * @param User $user
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function removeUser(User $user, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute("list-user");
    }
}
