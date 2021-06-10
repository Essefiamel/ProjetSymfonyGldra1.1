<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phpDocumentor\Reflection\Types\Null_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UseradminType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use function Symfony\Bridge\Twig\Extension\twig_is_root_form;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="Security_registration")
     */
    public function adduser(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm("App\Form\UserType", $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $hash = $encoder->EncodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('loginLink');
        }
        $em = $this->getDoctrine()->getManager();
        return $this->render('Security/user.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/allusers", name="alluserslink")
     */
    public function allusers()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(User::class);
        $lesUsers = $repo->findAll();
        return $this->render('Security/allusers.html.twig',
            ['lesUsers' => $lesUsers]);
    }

    /**
     * @Route("/editeruser/{id}", name="editeruserlink")
     */
    public function EditerUser($id, request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);


        $form = $this->createForm("App\Form\UserType", $user);
        $form->handleRequest($request);
        //$emai=$form->get('email')->getData();
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('alluserslink');
        }

        return $this->render('Security/user.html.twig', ['user' => $user, 'form' => $form->createView()]);

    }
    /**
     * @Route("/editeruseradmin/{id}", name="editeruseradminlink")
     */
    public function EditerUserAdmin($id, request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $form = $this->createForm("App\Form\UseradminType", $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('alluserslink');
        }

        return $this->render('Security/useradmin.html.twig', ['user' => $user, 'form' => $form->createView()]);

    }

    /**
     * @Route("/supprimeruser/{id}", name="supprimeruserlink")
     */
    public function SupprimerUser($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id ' . $id
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('alluserslink');
    }

    /**
     * @route("/login", name="loginLink")
     */
    public function login()
    {

        return $this->render('Security\login.html.twig');
    }
    /**
     * @route("/accessdenied", name="accessdeniedLink")
     */
    public function accessdenieduser()
    {
        return $this->render('Security\accessdenied.html.twig');
    }
    /**
     * @route("/menu", name="menulink")
     */
    public function menuuser()
    {
        return $this->render('Security\menu.html.twig');
    }
    /**
     * @route("/logout", name="logoutLink")
     */
    public function logout()
    {
    }

    /**
     * @route("/", name="homeLink")
     */
    public function home(Request $request)
    {

        if ($this->getUser()) {

            return $this->render('inc/menu.html.twig');
        }
        else{
            return $this->redirectToRoute('loginLink');
        }


    }

    /**
     * @Route("/sendemail/{emailto}", name="sendemaillink")
     */
    public function sendEmail(Request $request,MailerInterface $mailer,$emailto):Response
    {

        $email = (new Email())
            ->from('elearningTeam@elearning.com')
            ->to($emailto)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Validation du compte!')
            ->text('Nous informons que votre compte a ete validé');

        $mailer->send($email);

        // ...
        return $this->redirectToRoute('alluserslink');
    }
}