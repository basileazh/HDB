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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Doctrine\ORM\EntityRepository;

class HomePageController extends Controller
{


  public function indexAction(Request $request)
  {
    // Récupération de l'User authentifié
    $user = $this->get('security.token_storage')->getToken()->getUser();
    $em = $this->getDoctrine()->getManager();

    $StoryFormBuilder = $this->get('form.factory')->createBuilder(FormType::class);

    // On récupère la liste d'amis de l'user en cours pour le formulaire des histoires
    $friendsRepository = $this->getDoctrine()->getRepository('CoreBundle:Friends');
    $userFriends = $friendsRepository->getFriendsOfBoug($user);
    // On crée un tableau des usernames des Friends de l'User
    $userFriendsUsernames = [];
    foreach($userFriends as $userFriend) {
      $userFriendsUsernames[] = $userFriend->getUsername();
    }

    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $StoryFormBuilder
      ->add('title',      TextType::class, ['label' => 'Titre'])
      ->add('content',    TextareaType::class, ['label' => 'Contenu'])
      ->add('bougReadAccess', CollectionType::class, [
                    'entry_type' => EntityType::class, 
                    'entry_options' => [
                        'data_class' => null,
                        'class' => 'CoreBundle:Boug',
                        'query_builder' => function (EntityRepository $er) use ($user, $userFriendsUsernames) {
                            return $er->createQueryBuilder('boug')
                            ->addSelect('boug')
                            ->andWhere('boug.username IN(:userFriendsUsernames)')
                            ->setParameter('userFriendsUsernames', $userFriendsUsernames)
                            ->orderBy('boug.username', 'ASC');
                            },
                        'choice_label' => 'name',
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
                        'query_builder' => function (EntityRepository $er) use ($user, $userFriendsUsernames) {
                            return $er->createQueryBuilder('boug')
                            ->addSelect('boug')
                            ->where('boug.username = :ownerUsername OR boug.username IN(:userFriendsUsernames)')
                            // ->andWhere('boug.username IN(:userFriendsUsernames)')
                            ->setParameter('ownerUsername', $user->getUsername())
                            ->setParameter('userFriendsUsernames', $userFriendsUsernames)
                            ->orderBy('boug.username', 'ASC');
                            },
                        'choice_label' => 'name',
                    ],
                    'allow_add' => true,
                    'prototype' => true,
                    'allow_delete' => true,
                    'label' => 'Personnage de l\'histoire',
                ])
      ->add('isPublic', ChoiceType::class, [
          'choices'  => [
            'Yes' => true,
            'No' => false,
            ],
            'label' => 'Histoire publique'
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
        
        // Accès en lecture
        $storyReadAccesses = [];
        for($i = 0; $i < count($storyFormData['bougReadAccess']); $i++) {
          $storyReadAccesses[$i] = new BougStoryReadAccess();
          $storyReadAccesses[$i]->setBoug($storyFormData['bougReadAccess'][$i]);
          // $storyReadAccesses[$i]->setStory($story);
          // $em->persist($storyReadAccesses[$i]);
          $story->addBougStoryReadAccess($storyReadAccesses[$i]);
        }
        // On enregistre le user qui crée l'histoire comme ayant accès en lecture à sa propre histoire, afin qu'il puisse la noter
        $ownReadAccess = new BougStoryReadAccess();
        $ownReadAccess->setBoug($user);
        // $ownReadAccess->setStory($story);
        // $em->persist($ownReadAccess);
        $story->addBougStoryReadAccess($ownReadAccess);

        // Personnages de l'histoire
        $storyIsCharacters = [];
        for($i = 0; $i < count($storyFormData['bougIsCharacter']); $i++) {
          $storyIsCharacters[$i] = new BougStoryIsCharacter();
          $storyIsCharacters[$i]->setBoug($storyFormData['bougIsCharacter'][$i]);
          // $storyIsCharacters[$i]->setStory($story);
          $story->addBougStoryIsCharacter($storyIsCharacters[$i]);
          // $em->persist($storyIsCharacters[$i]);
        }
       
        // Owner de l'histoire
        $story->setOwner($user);

        // Histoire publique ou non 
        $story->setIsPublic($storyFormData['isPublic']);
       
        $em->persist($story);
        $em->flush();

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
        $storyservice = $this->container->get('core.storyservice');
        $rating = $storyservice->getStoryRating($story);
        $json.='{"id" : "'.$story->getId().'", 
                 "title" : "'.$story->getTitle().'",
                 "content" : "'.$story->getContent().'",
                 "owner" : "'.$story->getOwner()->getUsername().'",
                 "rating" : {"rating" : "'.$rating[0].'", "ratingsCount" : "'.$rating[1].'"},
                 "userRating" : "'.$repository->getStoryAccessForBoug($user, $story)->getRating().'",
                 "userOpinion" : "'.$storyservice->bougOpinionForStory($user, $story).'",
                 "charactersOpinions" : '.$storyservice->getStoryOpinionsJSON($story).'}';
       if(++$i !== $numStories)
        $json.= ',';
    }
    $json .= ']';
    // dump($json);
    // die;
    return $json;
  }
}

?>
