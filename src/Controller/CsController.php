<?php

namespace App\Controller;

use App\Entity\Stop;
use App\Entity\College;
use App\Repository\CollegeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class CsController extends AbstractController
{

    /**
     * @Route("/cs", name="cs", methods={"GET" , "POST"})
     */
    public function index(Request $request, ObjectManager $manager)
    {
        $college = new College();
        
        $form = $this->createFormBuilder($college)
                    ->add('nom', EntityType::class, [
                        'class' => 'App\Entity\College',
                        'choice_label' => "nom"
                    ])
                    ->getForm();
                    
        $form->handleRequest($request);
        $name_college = "";
        $x = "";
        $y = "";
        if($form->isSubmitted() && $form->isValid()){
            if($request->getMethod() == "POST"){
                $data_college = $form["nom"]->getData();
                $name_college = $data_college->getnom();
                $x = $data_college->getx();
                $y = $data_college->gety();
                $repos = $this->getDoctrine()->getRepository(Stop::class)->findAll();
                
                foreach($repos as $repo){
                    $stop_lat = $repo->getStopLat();
                    $stop_lon = $repo->getStopLon();
                
                    if($stop_lat && $stop_lon){
                        $rawSql = "SELECT DISTINCT (6378 * acos(cos(radians($y)) * cos(radians($stop_lat)) * cos(radians($stop_lon) - radians($x)) + sin(radians($y)) * sin(radians($stop_lat)))) AS distance FROM stop HAVING distance < 0.3 ORDER BY distance";

                        $em = $this->getDoctrine()->getManager();
                        $name = $em->getConnection()->prepare($rawSql);
                        $name->execute();
                        $results = $name->fetchAll();
                        if($results){
                            foreach($results as $result){
                                $name_stop = $repo->getStopId();
                                $name = $repo->getStopName();
                                
                                dump($name);
                            }
                        }
                    }
                    
                    
                }
                
            }
        }

        return $this->render('cs/index.html.twig', [
            'controller_name' => 'CsController',
            'name_college' => $name_college,
            'x' => $x,
            'y' => $y,
            'formCollege' => $form->createView()
        ]);
    }
}
