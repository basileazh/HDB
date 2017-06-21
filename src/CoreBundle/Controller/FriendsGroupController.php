<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use CoreBundle\Entity\Story;
use CoreBundle\Entity\Boug;
use CoreBundle\Entity\FriendsGroup;

class FriendsGroupController extends Controller
{
  public function indexAction(Request $request, $idFriendsGroup)
  {
    // Récupération de l'User authentifié
    $user = $this->get('security.token_storage')->getToken()->getUser();
    
    // Entity Manager et Repositories
    $em = $this->getDoctrine()->getManager();
    $friendsGroupRepository = $em->getRepository('CoreBundle:FriendsGroup');
    $storyRepository = $em->getRepository('CoreBundle:Story');



    // On récupère le groupe depuis la BDD
    $friendsGroup = $friendsGroupRepository->find($idFriendsGroup);
dump($friendsGroup->getMembers());
die;
    // AJOUT DE STORY
    $StoryFormBuilder = $this->get('form.factory')->createBuilder(FormType::class);

      $StoryFormBuilder
    ->add('title',      TextType::class, ['label' => 'Titre'])
    ->add('content',    TextareaType::class, ['label' => 'Contenu'])
    ->add('bougIsCharacter', CollectionType::class, [
                    'entry_type' => EntityType::class, 
                    'entry_options' => [
                        'data_class' => null,
                        'class' => 'CoreBundle:Boug',
                        'choices' => [$user ],
                        'choice_label' => 'name',
                    ],
                    'allow_add' => true,
                    'prototype' => true,
                    'allow_delete' => true,
                    'label' => 'Personnage de l\'histoire',
                ])
    ->add('save',      SubmitType::class);

    $storyForm = $StoryFormBuilder->getForm();

    $storyForm->handleRequest($request);
    
    // Traitement du formulaire d'ajout d'une histoire si celui-ci a été envoyé
    if ($storyForm->isSubmitted() && $storyForm->isValid()) {
        $storyFormData = $storyForm->getData();

        $story = new Story();

        // Titre
        $story->setTitle($storyFormData['title']);
        
        // Contenu
        $story->setContent($storyFormData['content']);

        // ReadAccess : on ajoute tous les memebres du groupe
        foreach ($friendsGroup->getMembers() as $member) {
          $storyReadAccess = new BougStoryReadAccess();
          $storyReadAccess->setBoug($member);
          $storyReadAccess->setStory($story);
          $story->addBougStoryReadAccess($storyReadAccess);
          $em->persist($storyReadAccess);
        }
        // On enregistre le user qui crée l'histoire comme ayant accès en lecture à sa propre histoire, afin qu'il puisse la noter
        $ownReadAccess = new BougStoryReadAccess();
        $ownReadAccess->setBoug($user);
        $ownReadAccess->setStory($story);
        $story->addBougStoryReadAccess($ownReadAccess);
        $em->persist($ownReadAccess);

        // Personnages de l'histoire
        $storyIsCharacters = [];
        for($i = 0; $i < count($storyFormData['bougIsCharacter']); $i++) {
          $storyIsCharacters[$i] = new BougStoryIsCharacter();
          $storyIsCharacters[$i]->setBoug($storyFormData['bougIsCharacter'][$i]);
          $storyIsCharacters[$i]->setStory($story);
          $story->addBougStoryIsCharacter($storyIsCharacters[$i]);
          $em->persist($storyIsCharacters[$i]);
        }
       
        // Owner de l'histoire
        $story->setOwner($user);

        // La récursion est aussi faite, la story est ajoutée au groupe par la fonction addGroup
        $story->addGroup($friendsGroup);

        $em->persist($story);
        $em->flush();

        return $this->redirectToRoute('core_friendsgroup', ['idFriendsGroup' => $friendsGroup->getId()]);
    }

    $stories = $friendsGroup->getStories();

    return $this->render('CoreBundle:FriendsGroup:friendsGroup.html.twig', [
      'user'  => $user,
      'storyForm' => $storyForm->createView(),
      'stories' => $this->prepareStoriesJSON($stories, $user),
    ]);
  }

  public function creationAction(Request $request)
  {
    // Récupération de l'User authentifié
    $user = $this->get('security.token_storage')->getToken()->getUser();

    // Entity Manager et Repositories
    $em = $this->getDoctrine()->getManager();
    $bougRepository = $em->getRepository('CoreBundle:Boug');

    if( $request->getMethod() == 'POST' ) {
      $newFriendsGroup = new FriendsGroup();

      // Nom du groupe
      $newFriendsGroup->setName($request->request->get('creationGroupName'));

      // Membres du groupe
      $members = $bougRepository->findById($request->request->get('creationGroupMembers'));

      foreach ($members as $member) {
        $newFriendsGroup->addMember($member);
      }
      
      $newFriendsGroup->setManager($user);

      $em->persist($newFriendsGroup);
      $em->flush();

      return $this->redirectToRoute('core_friendsgroup', ['idFriendsGroup' => $newFriendsGroup->getId()]);
    }

    return $this->redirectToRoute('core_homepage');
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
                 "userRating" : "'.$repository->getStoryAccessForBoug($user, $story)->getRating().'" }';
       if(++$i !== $numStories)
        $json.= ',';
    }
    $json .= ']';
    return $json;
  }
}
