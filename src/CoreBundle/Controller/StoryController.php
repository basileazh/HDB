<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use CoreBundle\Entity\Story;
use CoreBundle\Entity\Boug;
use CoreBundle\Entity\BougStoryReadAccess;
use CoreBundle\Entity\BougStoryIsCharacter;

class StoryController extends Controller
{
    //TODO : virer?
  // public function addAction(Story $strory, Boug $boug)
  // {
  //   $story->setOwner($boug);
  //   $bougStoryReadAccess = new BougStoryReadAccess();
  //   $bougStoryReadAccess->setBoug($boug);
  //   $bougStoryReadAccess->setStory($story);

  //   $bougStoryIsCharacter = new BougStoryIsCharacter();
  //   $bougStoryIsCharacter->setBoug($boug);
  //   $bougStoryIsCharacter->setStory($story);

  //   $story->addBougStoryReadAccess($bougStoryReadAccess);
  //   $story->addBougStoryIsCharacter($bougStoryIsCharacter);

  //   $em = $this->getDoctrine()->getManager();
  //   $em->persist($story);
  //   $em->flush();

  //   return new Response('<html><body>BSR</body></html>');
  // }

    //Appelée lros des appels Ajax pour donner une note à une histoire
    public function rateAction(Request $request)
    {
        if($request->isXMLHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $storyId = $request->get('storyId');
            $story = $em->getRepository('CoreBundle:Story')->find($storyId);
            $rate = $request->get('rate');

            //On récupère l'entité bougstoryreadaccess correspondant à l'accès en lecture du boug pour l'histoire
            $access = $em->getRepository('CoreBundle:BougStoryReadAccess')->getStoryAccessForBoug($user, $story);
            //Si rate vaut 0, cela signifie aucun avis et on met rating à null, sinon on met la note
            $access->setRating($rate == 0 ? null : $rate);

            $em->persist($access);
            $em->flush();

            //On recupère les nouvelles notes (momyenne, nombre etc) pour les retourner 
            $ratings = $this->container->get('core.storyservice')->getStoryRating($story);    
            return new JsonResponse(['rate' => 'success', 'newRating' => $ratings[0], 'newRatingsCount' => $ratings[1]]);
        }
        return new Response("Error : this is not an Ajax request", 400);
    }

    //Appelée lors des appels Ajax pour donner son avis sur une histoire (fake, vraie...)
    public function giveOpinionAction(Request $request)
    {
        if($request->isXMLHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $storyId = $request->get('storyId');
            $story = $em->getRepository('CoreBundle:Story')->find($storyId);
            $opinion = $request->get('opinion');

            //On récupère l'entité correspondant à l'appartenance du boug au personnages de l'histoire
            $access = $em->getRepository('CoreBundle:BougStoryIsCharacter')->getIsCharacterForBoug($user, $story);
            //Si le user retire son opinion on met null, sinon on met l'opinion en question
            $access->setOpinion($opinion == 'noOpinion' ? null : $opinion);

            $em->persist($access);
            $em->flush();

            return new JsonResponse(['opinion' => 'success', 'newCharactersOpinions' => $storyservice = $this->container->get('core.storyservice')->getStoryOpinionsJSON($story)]);
        }
        return new Response("Error : this is not an Ajax request", 400);
    }
}

?>
