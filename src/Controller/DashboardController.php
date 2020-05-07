<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\SkillRepository;
use App\Repository\ProjectRepository;
use App\Entity\Project;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
    public function boardAction(SkillRepository $skillRepository)
    {
        $skills = $skillRepository->findAll() ;
        return $this->render('/componentsAdmin/baseAdmin.html.twig', ["skills" => $skills]);
        
        
    }
    public function addProjectAction(Request $request, ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findAll() ;

        $project = new Project();
        $formProject = $this->createForm('App\Form\ProjectType', $project);

        $formProject->handleRequest($request);

        if($formProject->isSubmitted()){
            //hydrater mon entity (qui pour le moment est vide) avec les infos de mon formulaire
            $project = $formProject->getData();
            //je récupère le manager pour pouvoir sauvegarder mon entity dans la base de données
            $manager = $this->getDoctrine()->getManager();
            //je demande a Doctrine de préparer la sauvegarde de mon entity job
            $manager->persist($project);
            //j'exécute la sauvegarde de mon entity job
            $manager->flush();
            //je redirige vers la route de mon choix (dans ce cas, c'est la route qui a le nom 'project')
            return $this->redirectToRoute('dashboard');
        }


        return $this->render('/pagesAdmin/addProject.html.twig', ["projects" => $projects,"projectForm"=>$formProject->createView()]);
        
    }
    public function projectAction(Request $request, ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findAll() ;

        

        return $this->render('/pagesAdmin/projectAdmin.html.twig', ["projects" => $projects]);
        
    }


    public function modProjectAction(Request $request, ProjectRepository $projectRepository, $id)
    {
        $project = $projectRepository->find($id);

        $form = $this->createForm('App\Form\ProjectType',$project);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $project = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($project);
            $manager->flush();
            return $this->redirectToRoute('dashboard');
        }
    return $this->render('pagesAdmin/modifyProject.html.twig', ["projectForm"=>$form->createView()]);

    }
    public function suppProjectAction(Request $request, ProjectRepository $projectRepository, $id)
    {
        $project = $projectRepository->find($id);
        $this->getDoctrine()->getManager()->remove($project);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('projectAdmin');
    }
    public function skillAction(SkillRepository $skillRepository)
    {
        $skills = $skillRepository->findAll() ;
        return $this->render('pagesAdmin/skillAdmin.html.twig', ["skills" => $skills]);
        
    }
}