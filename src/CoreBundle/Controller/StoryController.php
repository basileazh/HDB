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
  public function addAction(Story $strory, Boug $boug)
  {
    $story->setOwner($boug);
    $bougStoryReadAccess = new BougStoryReadAccess();
    $bougStoryReadAccess->setBoug($boug);
    $bougStoryReadAccess->setStory($story);

    $bougStoryIsCharacter = new BougStoryIsCharacter();
    $bougStoryIsCharacter->setBoug($boug);
    $bougStoryIsCharacter->setStory($story);


    $story->addBougStoryReadAccess($bougStoryReadAccess);
    $story->addBougStoryIsCharacter($bougStoryIsCharacter);

    $em = $this->getDoctrine()->getManager();
    //$em->persist($story);
    $em->persist($story);
    //$em->persist($bougStoryReadAccess);
    //$em->persist($bougStoryIsCharacter);
    $em->flush();

    return new Response('<html><body>BSR</body></html>');
  }

    public function rateAction(Request $request)
    {
        if($request->isXMLHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $storyId = $request->get('storyId');
            $story = $em->getRepository('CoreBundle:Story')->find($storyId);
            $rate = $request->get('rate');

            $access = $em->getRepository('CoreBundle:BougStoryReadAccess')->getStoryAccessForBoug($user, $story);
            $access->setRating($rate == 0 ? null : $rate);

            $em->persist($access);
            $em->flush();
                          
            return new JsonResponse(['rate' => 'success']);
        }
        return new Response("Error : this is not an Ajax request", 400);
    }
}

?>
