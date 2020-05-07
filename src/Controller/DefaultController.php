<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\SkillRepository;

class DefaultController extends AbstractController
{
    public function indexAction(SkillRepository $skillRepository)
    {
        $skills = $skillRepository->findAll() ;
        return $this->render('home.html.twig', ["skills" => $skills]);
        
    }

}