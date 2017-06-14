<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityRepository;

use CoreBundle\Entity\Boug;
use CoreBundle\Entity\Friends;


class FriendsController extends Controller
{
    public function getBougListAction(Request $request)
    {
        if($request->isXMLHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $bougRepository = $em->getRepository('CoreBundle:Boug');
            $friendsRepository = $em->getRepository('CoreBundle:Friends');

            $user = $this->get('security.token_storage')->getToken()->getUser();

            $nameSearched = $request->get('nameSearched');
            $nonFriendBougs = $bougRepository->getBougsByPaternSearched($nameSearched);

            $friends = $friendsRepository->getFriendsOfBoug($user);
            $friendsRequestedByBoug = $friendsRepository->getFriendsRequestedBy($user);
            $bougsRequestingUser = $friendsRepository->getBougsRequesting($user);
            
            $nonFriendBougs = array_diff($nonFriendBougs, $friends, $friendsRequestedByBoug, $bougsRequestingUser);

            return new JsonResponse(['nonFriendBougs' => $this->prepareBougsJSON($nonFriendBougs),
                                     'friendsRequestedByBoug' => $this->prepareBougsJSON($friendsRequestedByBoug),
                                     'bougsRequestingUser' => $this->prepareBougsJSON($bougsRequestingUser)]);
        }
        return new Response("Error : this is not an Ajax request", 400);
    }

    public function addFriendAction(Request $request)
    {
        if($request->isXMLHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();


            $idBoug2 = $request->get('idBoug');
            $boug1 = $this->get('security.token_storage')->getToken()->getUser();
            $boug2 = $em->getRepository('CoreBundle:Boug')->find($idBoug2);

            $newFriendship = new Friends();
            $newFriendship->setBoug1($boug1)
                          ->setBoug2($boug2);

            $em->persist($newFriendship);
            $em->flush();
                          
            return new JsonResponse([]);
        }
        return new Response("Error : this is not an Ajax request", 400);
    }

    public function friendRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $friendRequests = $em->getRepository('CoreBundle:Friends')->getBougsRequesting($user);
        return $this->render('CoreBundle:Friends:friendRequests.html.twig', [
                    'friendRequests' => $this->prepareBougsJSON($friendRequests),
                ]);
    }

    public function acceptFriendRequestAction(Request $request)
    {
        if($request->isXMLHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $boug = $request->get('idBoug');
            $friends = $em->getRepository('CoreBundle:Friends')->findOneBy(['boug1' => $boug, 'boug2' => $user]);
            if($friends->getWaitingForAnswer() == true)
            {
                $friends->setDateSinceAgreement(new \DateTime('now'));
                $friends->setWaitingForAnswer(false);
                $em->persist($friends);
                $em->flush();
            }
                          
            return new JsonResponse(['friendAdded' => 'success']);
        }
        return new Response("Error : this is not an Ajax request", 400);
    }



    //Prépare un JSON à partir d'une liste de bougs
    private function prepareBougsJSON($bougs)
    {
        $json = '[';
        $numBougs = count($bougs);
        $i = 0;
        foreach ($bougs as $key => $boug)
        {
            $json.='{"id" : "'.$boug->getId().'", "username" : "'.$boug->getUsername().'"}';
            if(++$i !== $numBougs)
                $json.= ',';
        }
        $json.= ']';
        return $json;
    }
}

?>
