<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProjectRepository;
use App\Entity\Project;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends AbstractController
{
    public function projectAction(Request $request, ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findAll() ;

        

        return $this->render('/pagesCli/project.html.twig', ["projects" => $projects]);
        
    }


   
}