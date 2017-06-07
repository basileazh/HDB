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

use CoreBundle\Form\StoryType;


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

    $storyForm = $this->createForm(StoryType::class, $story);

    $storyForm->handleRequest($request);

     if ($storyForm->isSubmitted() && $storyForm->isValid()) {

        $em = $this->getDoctrine()->getManager();
        $story->setOwner($boug);
        $em->persist($story);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Story ajoutée.');

        // On redirige vers la page de visualisation de l'annonce nouvellement créée
      return $this->redirectToRoute('core_homepage');
    }

     // Récupération des Stories de l'User en cours
    $storyRepository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('CoreBundle:Story');

    $stories = $storyRepository->findBy(
        [ 'owner' => $boug ]
      );
  	
    return $this->render('CoreBundle:HomePage:homepage.html.twig', [
      'bougs' => $bougs,
      'user'  => $boug,
      'form' => $storyForm->createView(),
      'stories' => $stories,
    ]);
  }

}

?>
