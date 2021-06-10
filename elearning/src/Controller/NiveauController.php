<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Etudiant;
use App\Entity\Niveau;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


use App\Form\NiveauType;


class NiveauController extends AbstractController

{

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/allniveaux", name="allniveauxlink")
     */
    public function allniveaux()
    {
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository(Niveau::class);
        $lesniveaux=$repo->findAll();
        return $this->render('niveau/allniveaux.html.twig',
            ['lesniveaux'=> $lesniveaux]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/addniveau", name="addniveaulink")
     */
    public function Ajouterniveau(Request $request)
    {
        $niveau = new Niveau();
        $form = $this->createForm("App\Form\NiveauType", $niveau);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($niveau);
            $em->flush();
            return $this->redirectToRoute('allniveauxlink');
        }
        $em=$this->getDoctrine()->getManager();
        $lesetudiants=$em->getRepository(Etudiant::class)
            ->findBy(['Niveau'=>$niveau]);
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository(Etudiant::class);
        return $this->render('niveau/ajouter.html.twig',
            [
                'form' => $form->createView(),
                'lesetudiants'=> $lesetudiants,
                ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/editerniveau/{id}", name="editerniveaulink")
     */
    public function editerniveau($id, request $request)
    {
        $niveau = $this->getDoctrine()
            ->getRepository(Niveau::class)
            ->find($id);


        $form = $this->createForm("App\Form\NiveauType", $niveau);
        $form->handleRequest($request);

        $em=$this->getDoctrine()->getManager();
        $lesetudiants=$em->getRepository(Etudiant::class)
            ->findBy(['Niveau'=>$niveau]);
        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository(Etudiant::class);
        $n=$repository->getcountetudiantniveau($id);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($niveau);
            $em->flush();
            return $this->redirectToRoute('allniveauxlink');
        }

        return $this->render('niveau/ajouter.html.twig', [
            'niveau' => $niveau, 'form' => $form->createView(),
            'lesetudiants'=> $lesetudiants,
            'nbr'=>$n

        ]);

    }

}
