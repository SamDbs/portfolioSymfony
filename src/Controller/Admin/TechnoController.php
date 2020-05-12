<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\SkillRepository;
use App\Repository\ProjectRepository;
use App\Repository\TechnoRepository;
use App\Entity\Techno;
use App\Entity\Project;
use App\Entity\Skill;
use App\Service\FormsManager;
use Symfony\Component\HttpFoundation\Request;

class TechnoController extends AbstractController
{
    public function technoAction(Request $request, TechnoRepository $technoRepository)
    {
        $technos = $technoRepository->findAll();



        return $this->render('/pagesAdmin/technoAdmin.html.twig', ["technos" => $technos]);
    }

    public function addTechnoAction(Request $request, TechnoRepository $technoRepository)
    {
        $technos = $technoRepository->findAll();

        $techno = new Techno();
        $formTechno = $this->createForm('App\Form\TechnoType', $techno);

        $formTechno->handleRequest($request);

        if ($formTechno->isSubmitted()) {
            //hydrater mon entity (qui pour le moment est vide) avec les infos de mon formulaire
            $techno = $formTechno->getData();
            $file = $formTechno->get('image')->getData();
            if($file){
                $newFileName = FormsManager::handleFileUpload($file, $this->getParameter('uploads'));
                $techno->setImage($newFileName);
            }
            $manager = $this->getDoctrine()->getManager();
            //je demande a Doctrine de préparer la sauvegarde de mon entity job
            $manager->persist($techno);
            //j'exécute la sauvegarde de mon entity job
            $manager->flush();
            //je redirige vers la route de mon choix (dans ce cas, c'est la route qui a le nom 'project')
            return $this->redirectToRoute('dashboard');
        }


        return $this->render('/pagesAdmin/addTechno.html.twig', ["technos" => $technos, "formTechno" => $formTechno->createView()]);
    }
    public function modTechoAction(Request $request, TechnoRepository $technoRepository, $id){
        $techno = $technoRepository->find($id);

        $form = $this->createForm('App\Form\ProjectType', $techno);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $techno = $form->getData();
            $file = $form->get('image')->getData();
            if($file){
                $newFileName = FormsManager::handleFileUpload($file, $this->getParameter('uploads'));
                $techno->setImage($newFileName);
            }

            $manager = $this->getDoctrine()->getManager();

            $manager->persist($techno);
            $manager->flush();
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('pagesAdmin/modifyTechno.html.twig', ["formTechno" => $form->createView()]);
    }
}   
