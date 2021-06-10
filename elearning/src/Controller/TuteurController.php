<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Matiere;
use App\Entity\Etudiant;
use App\Entity\Tuteur;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TuteurController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/addtuteur", name="addtuteurlink")
     */
    public function addtuteur(Request $request)
    {
        $tuteur = new Tuteur();
        $form = $this->createForm("App\Form\TuteurType", $tuteur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tuteur);
            $em->flush();
            return $this->redirectToRoute('alltuteurslink');
        }
        $em = $this->getDoctrine()->getManager();
        return $this->render('tuteur/ajouter.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/alltuteurs", name="alltuteurslink")
     */
    public function alltuteurs()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Tuteur::class);
        $lestuteurs = $repo->findAll();
        return $this->render('tuteur/alltuteurs.html.twig',
            ['lestuteurs' => $lestuteurs]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/editertuteur/{id}", name="editertuteurlink")
     */
    public function editertuteur($id, request $request)
    {

        $tuteur = $this->getDoctrine()
            ->getRepository(Tuteur::class)
            ->find($id);


        $form = $this->createForm("App\Form\TuteurType", $tuteur);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tuteur);
            $em->flush();
            return $this->redirectToRoute('alltuteurslink');
        }

        return $this->render('tuteur/ajouter.html.twig', ['etudiant' => $tuteur, 'form' => $form->createView()]);

    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/tuteurcours/{id}", name="tuteurcourslink")
     */
    public function tuteurcours($id, request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $tuteur = $this->getDoctrine()
            ->getRepository(Tuteur::class)
            ->find($id);

        $em=$this->getDoctrine()->getManager();
        $lesmatieres=$tuteur->getMatieres();
        return $this->render('tuteur/tuteurcours.html.twig', ['Tuteur'=>$tuteur,'lesmatieres'=> $lesmatieres
        ]);
    }
    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/mescours/{id}", name="mescourslink")
     */
    public function mescours($id, request $request)
    {

        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository(Tuteur::class);
        $idt=$repository->getTuteur($id);
        $tuteur = $this->getDoctrine()
            ->getRepository(Tuteur::class)
            ->find($idt);
        $lesmatieres=$tuteur->getMatiere();
        return $this->render('tuteur/tuteurcours.html.twig', ['lesmatieres'=> $lesmatieres
        ]);
    }
    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/mesgroupes/{id}", name="mesgroupeslink")
     */
    public function mesgroupes($id, request $request)
    {

        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository(Tuteur::class);
        $idt=$repository->getTuteur($id);
        $tuteur = $this->getDoctrine()
            ->getRepository(Tuteur::class)
            ->find($idt);
        $lesgroupes=$tuteur->getNiveau();
        return $this->render('tuteur/tuteurgroupes.html.twig', ['lesgroupes'=> $lesgroupes
        ]);
    }
}
