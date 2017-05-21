<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use CoreBundle\Entity\FriendsGroup;

class FriendsGroupController extends Controller
{
  // public function indexAction(Boug $user)
  // {
  //     return $this->render('CoreBundle:HomePage:homepage.html.twig', [
  //        ]);
  // }

  public function creationAction()
  {

    $friendsGroup = new FriendsGroup();

    // On crée le FormBuilder grâce au service form factory
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $advert);

    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $formBuilder
      ->add('date',      DateType::class)
      ->add('title',     TextType::class)
      ->add('content',   TextareaType::class)
      ->add('author',    TextType::class)
      ->add('published', CheckboxType::class)
      ->add('save',      SubmitType::class)
    ;

      return $this->render('CoreBundle:FriendsGroup:creation.html.twig', [
         ]);
  }

}

?>