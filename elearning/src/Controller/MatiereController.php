<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Etudiant;
use App\Entity\Tuteur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MatiereController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/AddMatiere", name="AddMatiereLink")
     */
    public function addmatiere(Request $request)
    {
        $matiere = new Matiere;
        $form = $this->createForm("App\Form\MatiereType", $matiere);
        $form->handleRequest($request);

        $em=$this->getDoctrine()->getManager();
        $lestuteurs=$matiere->getTuteurs();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($matiere);
            $em->flush();
            return $this->redirectToRoute('allmatiereslink');
        }
        $em = $this->getDoctrine()->getManager();
        return $this->render('matiere/matiere.html.twig',
            ['form' => $form->createView(),
                'lestuteurs'=> $lestuteurs
            ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/allmatieres", name="allmatiereslink")
     */
    public function allmatieres()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Matiere::class);
        $lesMatieres = $repo->findAll();
        return $this->render('matiere/allmatieres.html.twig',
            ['lesMatieres' => $lesMatieres]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/editermatiere/{id}", name="EditerMatiereLink")
     */
    public function EditerMatiere($id, request $request)
    {
        $matiere = $this->getDoctrine()
            ->getRepository(Matiere::class)
            ->find($id);


        $form = $this->createForm("App\Form\MatiereType", $matiere);
        $form->handleRequest($request);

        $em=$this->getDoctrine()->getManager();
        $lestuteurs=$matiere->getTuteurs();


        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($matiere);
            $em->flush();
            return $this->redirectToRoute('allmatiereslink');
        }

        return $this->render('matiere/matiere.html.twig', ['matiere' => $matiere, 'form' => $form->createView(),
            'lestuteurs'=> $lestuteurs
        ]);

    }


}
