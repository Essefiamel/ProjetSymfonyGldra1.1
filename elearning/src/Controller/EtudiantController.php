<?php

namespace App\Controller;

use App\Entity\Etudiant;

use App\Entity\Matiere;
use App\Entity\Niveau;
use App\Entity\Section;
use App\Entity\Tuteur;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Query\ResultSetMapping;

class EtudiantController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/addetudiant", name="addetudiantlink")
     */
    public function addetudiant(Request $request)
    {
        $etudiant = new Etudiant();
        $form = $this->createForm("App\Form\EtudiantType", $etudiant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($etudiant);
            $em->flush();
            return $this->redirectToRoute('alletudiantslink');
        }
        $em = $this->getDoctrine()->getManager();
        return $this->render('etudiant/ajouter.html.twig',
            ['form' => $form->createView()]);
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/mescoursetudiant/{id}", name="mescoursetudiantlink")
     */
    public function mescoursetudiant($id, request $request)
    {

        $doctrine=$this->getDoctrine();
        $repository=$doctrine->getRepository(Etudiant::class);
        $ide=$repository->getEtudiant($id);
        $etudiant = $this->getDoctrine()
            ->getRepository(Etudiant::class)
            ->find($ide);
        $niveau=$etudiant->getNiveau();
        $lesmatieres=$niveau->getMatiere();

        $lestuteurs=$niveau->getTuteurs();

       // $tuteurmatiere = $this->getDoctrine()
            //->getRepository(Tuteur::class)
            //->findBy(['Matiere'=>$lesmatieres]);



        return $this->render('etudiant/etudiantcours.html.twig', ['lestuteurs'=>$lestuteurs,'etudiant'=>$etudiant,'lesmatieres'=> $lesmatieres
        ]);



    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/alletudiants", name="alletudiantslink")
     */
    public function alletudiants()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Etudiant::class);
        $lesetudiants = $repo->findAll();
        return $this->render('etudiant/alletudiants.html.twig',
            ['lesetudiants' => $lesetudiants]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/editeretudiant/{id}", name="editeretudiantlink")
     */
    public function editeretudiant($id, request $request)
    {
        $etudiant = $this->getDoctrine()
            ->getRepository(Etudiant::class)
            ->find($id);


        $form = $this->createForm("App\Form\EtudiantType", $etudiant);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($etudiant);
            $em->flush();
            return $this->redirectToRoute('alletudiantslink');
        }

        return $this->render('etudiant/ajouter.html.twig', ['etudiant' => $etudiant, 'form' => $form->createView()]);

    }

    /**
     * @Route("/supprimeretudiant/{id}", name="supprimeretudiantlink")
     */
    public function Supprimeretudiant($id)
    {
        $etudiant = $this->getDoctrine()
            ->getRepository(Etudiant::class)
            ->find($id);
        if (!$etudiant) {
            throw $this->createNotFoundException(
                'No student found for id ' . $id
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($etudiant);
        $em->remove($etudiant);
        $em->flush();
        return $this->redirectToRoute('alletudiantslink');
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/sectionsetudiant/{id}", name="sectionsetudiantlink")
     */
    public function sectionsetudiant($id, request $request)
    {
        $matiere = $this->getDoctrine()
            ->getRepository(Matiere::class)
            ->find($id);

        $em=$this->getDoctrine()->getManager();
        $lessections=$em->getRepository(Section::class)
            ->findBy(['Matiere'=>$matiere]);
        return $this->render('section/courssections.html.twig', ['matiere'=>$matiere,'lessections'=> $lessections
        ]);
    }
}
