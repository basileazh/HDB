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
use Doctrine\ORM\EntityRepository;

use CoreBundle\Entity\Boug;
use CoreBundle\Entity\Story;
use CoreBundle\Entity\BougStoryReadAccess;
use CoreBundle\Entity\BougStoryIsCharacter;


class HomePageController extends Controller
{
  public function indexAction(Request $request)
  {
    $user = $this->get('security.token_storage')->getToken()->getUser();
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
                ->add('Title', TextType::class)
                ->add('Content', TextareaType::class)
                ->add('bougstoryreadaccess', EntityType::class, array(
                    'class'        => 'CoreBundle:Boug',
                    'choice_label' => 'username',
                    'multiple'     => true,
                    'expanded'     => true,
                  ))
                ->add('bougStoryIsCharacter', EntityType::class, array(
                    'class'        => 'CoreBundle:Boug',
                    'choice_label' => 'username',
                    'multiple'     => true,
                    'expanded'     => true,

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

        $bougstoryreadaccess = new BougStoryReadAccess();
        $bougstoryreadaccess->setBoug($story->getBougStoryReadAccess());
        $bougstoryreadaccess->setStory($story);
        $story->removeBougStoryReadAccess($boug);
        $story->addBougStoryReadAccess($bougstoryreadaccess);

        $bougStoryIsCharacter = new BougStoryIsCharacter();
        $bougStoryIsCharacter->setBoug($story->getBougStoryIsCharacter());
        $bougStoryIsCharacter->setStory($story);
        $story->removeBougStoryIsCharacter($boug);
        $story->addBougStoryIsCharacter($bougStoryIsCharacter);

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
