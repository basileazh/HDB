<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use CoreBundle\Entity\Boug;
use CoreBundle\Entity\Story;
use CoreBundle\Entity\BougStoryReadAccess;
use CoreBundle\Entity\BougStoryIsCharacter;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Doctrine\ORM\EntityRepository;

class HomePageController extends Controller
{


  public function indexAction(Request $request)
  {
    $user = $this->get('security.token_storage')->getToken()->getUser();
    
    $StoryFormBuilder = $this->get('form.factory')->createBuilder(FormType::class);

    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $StoryFormBuilder
      ->add('title',      TextType::class, ['label' => 'Titre'])
      ->add('content',    TextareaType::class, ['label' => 'Contenu'])
      ->add('bougReadAccess', CollectionType::class, [
                    'entry_type' => EntityType::class, 
                    'entry_options' => [
                        'data_class' => null,
                        'class' => 'CoreBundle:Boug',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('boug')
                            ->orderBy('boug.username', 'ASC');
                            },
                        'choice_label' => 'username',
                    ],
                    'allow_add' => true,
                    'prototype' => true,
                    'allow_delete' => true,
                    'label' => 'Accès en lecture',
                ])
      ->add('bougIsCharacter', CollectionType::class, [
                    'entry_type' => EntityType::class, 
                    'entry_options' => [
                        'data_class' => null,
                        'class' => 'CoreBundle:Boug',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('boug')
                            ->orderBy('boug.username', 'ASC');
                            },
                        'choice_label' => 'username',
                    ],
                    'allow_add' => true,
                    'prototype' => true,
                    'allow_delete' => true,
                    'label' => 'Personnage de l\'histoire',
                ])
      ->add('save',      SubmitType::class);


     $storyForm = $StoryFormBuilder->getForm();

    $storyForm->handleRequest($request);

     if ($storyForm->isSubmitted() && $storyForm->isValid()) {
        $storyFormData = $storyForm->getData();

        $em = $this->getDoctrine()->getManager();

        $story = new Story();
        $story->setTitle($storyFormData['title']);
        $story->setContent($storyFormData['content']);
        
        $storyReadAccesses = [];
        for($i = 0; $i < count($storyFormData['bougReadAccess']); $i++) {
          $storyReadAccesses[$i] = new BougStoryReadAccess();
          $storyReadAccesses[$i]->setBoug($storyFormData['bougReadAccess'][$i]);
          $storyReadAccesses[$i]->setStory($story);
          $story->addBougStoryReadAccess($storyReadAccesses[$i]);
          $em->persist($storyReadAccesses[$i]);
        }

        $storyIsCharacters = [];
        for($i = 0; $i < count($storyFormData['bougIsCharacter']); $i++) {
          $storyIsCharacters[$i] = new BougStoryIsCharacter();
          $storyIsCharacters[$i]->setBoug($storyFormData['bougIsCharacter'][$i]);
          $storyIsCharacters[$i]->setStory($story);
          $story->addBougStoryIsCharacter($storyIsCharacters[$i]);
          $em->persist($storyIsCharacters[$i]);
        }
       
        $story->setOwner($user);
       
        $em->persist($story);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Story ajoutée.');
      echo ('BSR') ;
        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('core_homepage');
    }

     // Récupération des Stories de l'User en cours
    $storyRepository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('CoreBundle:Story');

    $stories = $storyRepository->findBy(
        [ 'owner' => $user ]
      );
    return $this->render('CoreBundle:HomePage:homepage.html.twig', [
      'user'  => $user,
      'storyForm' => $storyForm->createView(),
      'stories' => $this->prepareStoriesJSON($stories, $user),
    ]);
  }

  private function prepareStoriesJSON($stories, $user)
  {
    $json = '[';
    $numStories = count($stories);
    $i = 0;
    $repository = $this->getDoctrine()->getManager()->getRepository('CoreBundle:BougStoryReadAccess');
    foreach ($stories as $story)
    {
        $rating = $this->getStoryRating($story);
        $json.='{"id" : "'.$story->getId().'", 
                 "title" : "'.$story->getTitle().'",
                 "content" : "'.$story->getContent().'",
                 "owner" : "'.$story->getOwner()->getUsername().'",
                 "rating" : {"rating" : "'.$rating[0].'", "nbrRatings" : "'.$rating[1].'"},
                 "userRating" : "'.$repository->getStoryAccessForBoug($user, $story).'" }';
                //TODO: rempalcer par cette ligne
                 // "userRating" : "'.$repository->getStoryAccessForBoug($user, $story)->getNote().'" }';
       if(++$i !== $numStories)
        $json.= ',';
    }
    $json .= ']';
    return $json;
  }

  private function getStoryRating($story)
  {
    $readAccesses = $story->getBougStoryReadAccess();
    if(count($readAccesses) == 0)
        return [0,0];
    $mean = 0;
    foreach ($readAccesses as $readAccess)
    {
        $mean+=$readAccess->getNote();
    }
    return [$mean/count($readAccesses), count($readAccesses)];
  }

}

?>
