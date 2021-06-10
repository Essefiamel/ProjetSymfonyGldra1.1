<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Matiere;
use App\Entity\Section;
use App\Entity\Tuteur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class SectionController extends AbstractController
{
    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/addsection", name="addsectionlink")
     */
    public function addsection(Request $request)
    {

        $section = new Section();
        $form = $this->createForm("App\Form\SectionType", $section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $documents = $form->get('documents')->getData();
            foreach ($documents as $document){
                $ext =$document-> guessExtension();

                $filename =$document->getClientOriginalName();
                $document ->move($this->getParameter('documents_directory'),$filename);
                $doc= new Document();
                $doc->setName($filename);
                $doc->setExtension($ext);
                $section->addDocument($doc);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($section);
            $em->flush();
            //return $this->redirectToRoute('tuteurcourslink');
        }
        $em = $this->getDoctrine()->getManager();
        return $this->render('section/ajouter.html.twig',
            ['form' => $form->createView()]);
    }
    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/editersection/{id}", name="editersectionlink")
     */
    public function editersection($id, request $request)
    {

        $section = $this->getDoctrine()
            ->getRepository(Section::class)
            ->find($id);


        $form = $this->createForm("App\Form\SectionType", $section);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $documents = $form->get('documents')->getData();
            foreach ($documents as $document){
                //$filename =md5(uniqid()) . '.' . $document-> guessExtension();
                $ext =$document-> guessExtension();

                $filename =$document->getClientOriginalName();
                $document ->move($this->getParameter('documents_directory'),$filename);
                $doc= new Document();
                $doc->setName($filename);
                $doc->setExtension($ext);
                $section->addDocument($doc);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($section);
            $em->flush();
            // $this->redirectToRoute('alltuteurslink');
        }

        return $this->render('section/ajouter.html.twig', ['section' => $section, 'form' => $form->createView()]);

    }
    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/courssections/{id}", name="courssectionslink")
     */
    public function courssections($id, request $request)
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
    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/supprimerdoc/{id}", name="supprimerdoclink")
     */
    public function supprimerdoc(Document $document, request $request)
    {
        $data=json_decode($request->getContent(), true);

            $nom=$document->getName();
            unlink($this->getParameter('documents_directory').'/'.$nom);
            $em=$this->getDoctrine()->getManager();
            $em->remove($document);
            $em->flush();
            return new JsonResponse(['success' => 1]);

    }

    /**
     * @Route("/telechargerdoc/{id}/{idm}", name="telechargerdoclink")
     */
    public function telechargerdoc($id,$idm, request $request)
    {

        $document = $this->getDoctrine()
            ->getRepository(Document::class)
            ->find($id);
$fileName=$document->getName();
        $doc= new File($this->getParameter('documents_directory').'/'.$fileName,true);
$filesystem=new filesystem();

        //$doc->move($this->getParameter('local_directory'),$fileName);
        $filesystem->copy($this->getParameter('documents_directory').'/'.$fileName, $this->getParameter('local_directory').'/'.$fileName);
        $matiere = $this->getDoctrine()
            ->getRepository(Matiere::class)
            ->find($idm);

        $em=$this->getDoctrine()->getManager();
        $lessections=$em->getRepository(Section::class)
            ->findBy(['Matiere'=>$matiere]);

        return $this->render('section/courssections.html.twig', ['matiere'=>$matiere,'lessections'=> $lessections
        ]);
       // return $this->render('section/courssections.html.twig', ['matiere'=>$matiere,'lessections'=> $lessections
        //]);
    }
}
