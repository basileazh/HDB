<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form as Form;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use CoreBundle\Entity\Boug;
use CoreBundle\Entity\Story;
//use CoreBundle\Entity\BougStoryReadAccess;
//use CoreBundle\Entity\BougStoryIsCharacter;


class HomePageController extends Controller
{
  public function indexAction(Request $request)
  {
    // Simulation de Boug user, debug
    $bougRepository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('CoreBundle:Boug');

    $bougs = $bougRepository->findAll();

    $boug = $bougs[0];

    // Création du formulaire d'ajout de Story
    $story = new Story();
    //$story->setBougStoryRead()

    $storyForm = $this->createFormBuilder($story)
                ->add('title', TextType::class)
                ->add('content', TextareaType::class)
                ->add('bougstoryreadaccess', EntityType::class, array(
                    'class'        => 'CoreBundle:Boug',
                    'choice_label' => 'login',
                    'multiple'     => true,
                  ))
                ->add('bougStoryIsCharacter', EntityType::class, array(
                    'class'        => 'CoreBundle:Boug',
                    'choice_label' => 'login',
                    'multiple'     => true,
                  ))                
                ->add('save', SubmitType::class, array('label' => '   Create Story'))
                ->getForm();

    $storyForm->handleRequest($request);

    // Récupération des Stories de l'User en cours
    $storyRepository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('CoreBundle:Story');

    $stories = $storyRepository->findBy(
        [ 'owner' => $boug ]
      );

     if ($storyForm->isSubmitted() && $storyForm->isValid()) {
        
        $story = $storyForm->getData();

        $em = $this->getDoctrine()->getManager();
        $story->setOwner($boug);
        $em->persist($story);
        $em->flush();

    }

    
  	
    return $this->render('CoreBundle:HomePage:homepage.html.twig', [
      'bougs' => $bougs,
      'user'  => $boug,
      'form' => $storyForm->createView(),
      'stories' => $stories,
    ]);
  }

}

?>
