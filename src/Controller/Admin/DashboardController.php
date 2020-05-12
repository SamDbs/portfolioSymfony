<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\SkillRepository;
use App\Repository\ProjectRepository;
use App\Entity\Project;
use App\Entity\Skill;
use App\Service\FormsManager;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
    public function boardAction(SkillRepository $skillRepository)
    {
        $skills = $skillRepository->findAll();
        return $this->render('/componentsAdmin/baseAdmin.html.twig', ["skills" => $skills]);
    }

    public function projectAction(Request $request, ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findAll();



        return $this->render('/pagesAdmin/projectAdmin.html.twig', ["projects" => $projects]);
    }

    public function addProjectAction(Request $request, ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findAll();

        $project = new Project();
        $formProject = $this->createForm('App\Form\ProjectType', $project);

        $formProject->handleRequest($request);

        if ($formProject->isSubmitted()) {
            //hydrater mon entity (qui pour le moment est vide) avec les infos de mon formulaire
            $project = $formProject->getData();
            $file = $formProject->get('image')->getData();
            if($file){
                $newFileName = FormsManager::handleFileUpload($file, $this->getParameter('uploads'));
                $project->setImage($newFileName);
            }
            $manager = $this->getDoctrine()->getManager();
            //je demande a Doctrine de préparer la sauvegarde de mon entity job
            $manager->persist($project);
            //j'exécute la sauvegarde de mon entity job
            $manager->flush();
            //je redirige vers la route de mon choix (dans ce cas, c'est la route qui a le nom 'project')
            return $this->redirectToRoute('dashboard');
        }


        return $this->render('/pagesAdmin/addProject.html.twig', ["projects" => $projects, "projectForm" => $formProject->createView()]);
    }


    public function modProjectAction(Request $request, ProjectRepository $projectRepository, $id)
    {
        $project = $projectRepository->find($id);

        $form = $this->createForm('App\Form\ProjectType', $project);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $project = $form->getData();
            $file = $form->get('image')->getData();
            if($file){
                $newFileName = FormsManager::handleFileUpload($file, $this->getParameter('uploads'));
                $project->setImage($newFileName);
            }

            $manager = $this->getDoctrine()->getManager();

            $manager->persist($project);
            $manager->flush();
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('pagesAdmin/modifyProject.html.twig', ["projectForm" => $form->createView()]);
    }

    public function suppProjectAction(Request $request, ProjectRepository $projectRepository, $id)
    {
        $project = $projectRepository->find($id);
        $this->getDoctrine()->getManager()->remove($project);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('projectAdmin');
    }

    public function skillAction(Request $request, SkillRepository $skillRepository)
    {
        $skills = $skillRepository->findAll();
        return $this->render('pagesAdmin/skillAdmin.html.twig', ["skills" => $skills]);
    }

    public function addSkillAction(Request $request, SkillRepository $skillRepository)
    {
        $skills = $skillRepository->findAll();

        $skill = new Skill();
        $formSkill = $this->createForm('App\Form\SkillType', $skill);

        $formSkill->handleRequest($request);

        if ($formSkill->isSubmitted()) {
            $skill = $formSkill->getData();
            $file = $formSkill->get('image')->getData();
            if($file){
                $newFileName = FormsManager::handleFileUpload($file, $this->getParameter('uploads'));
                $skill->setImage($newFileName);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($skill);
            $manager->flush();
            return $this->redirectToRoute('skillAdmin');
        }


        return $this->render('/pagesAdmin/addSkill.html.twig', ["skills" => $skills, "formSkill" => $formSkill->createView()]);
    }

    public function modSkillAction(Request $request, SkillRepository $skillRepository, $id)
    {
        $skills = $skillRepository->find($id);

        $form = $this->createForm('App\Form\SkillType', $skills);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $skill = $form->getData();
            $file = $form->get('image')->getData();
            if($file){
                $newFileName = FormsManager::handleFileUpload($file, $this->getParameter('uploads'));
                $skill->setImage($newFileName);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($skill);
            $manager->flush();
            return $this->redirectToRoute('skillAdmin');
        }
        return $this->render('pagesAdmin/modifySkill.html.twig', ["formSkill" => $form->createView()]);
    }

    public function supSkillAction(Request $request, SkillRepository $skillRepository, $id)
    {
        $skill = $skillRepository->find($id);
        $this->getDoctrine()->getManager()->remove($skill);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('skillAdmin');
    }
}