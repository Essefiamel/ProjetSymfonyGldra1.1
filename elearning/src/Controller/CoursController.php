<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Etudiant;
use App\Entity\Tuteur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/AddCours", name="AddCoursLink")
     */
    public function addcours(Request $request)
    {
        $cours = new Cours();
        $form = $this->createForm("App\Form\CoursType", $cours);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cours);
            $em->flush();
            return $this->redirectToRoute('AllCoursLink');
        }
        $em = $this->getDoctrine()->getManager();
        return $this->render('cours/Cours.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/allcours", name="allcourslink")
     */
    public function allcours()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Cours::class);
        $lesCours = $repo->findAll();
        return $this->render('cours/allcours.html.twig',
            ['lesCours' => $lesCours]);
    }

    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/editercours/{id}", name="EditerCoursLink")
     */
    public function EditerCours($id, request $request)
    {
        $cours = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->find($id);


        $form = $this->createForm("App\Form\CoursType", $cours);
        $form->handleRequest($request);

        $em=$this->getDoctrine()->getManager();
        $lestuteurs=$cours->getTuteurs();


        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cours);
            $em->flush();
            return $this->redirectToRoute('AllCoursLink');
        }

        return $this->render('cours/cours.html.twig', ['cours' => $cours, 'form' => $form->createView(),
            'lestuteurs'=> $lestuteurs
        ]);

    }


}
