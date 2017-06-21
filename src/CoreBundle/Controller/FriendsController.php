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
    // Réponse à un appel AJAX pour récupérer la liste d'amis d'un boug. Le boug en question doit avoir son username passsé
    public function getUsernamesOfFriendsOfUserAction (Request $request)
    {
        // if($request->isXMLHttpRequest())
        // {
            $em = $this->getDoctrine()->getManager();
            $friendsRepository = $em->getRepository('CoreBundle:Friends');
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $usernameSearched = $request->get('usernameSearched');
            $friends = $friendsRepository->getFriendsOfBoug($user);

            $friendsUsernames = [];
            foreach ($friends as $friend) {
                $friendsUsernames[] = [ 'username' => $friend->getUsername(),
                                        'id'       => $friend->getId(),
                                      ];
            }

            return new JsonResponse([json_encode($friendsUsernames)]);            
        // }

        // return new Response("Error : this is not an Ajax request", 400);
    }

    //Cette fonction est appellée lors des appels Ajax pour la recherche d'amis.
    //Elle retourne une liste de boug correspondant à un pattern recherché, 
    //qui ne sont pas encore amis avec l'utilisateur
    public function getBougListForFriendsSearchAction(Request $request)
    {
        if($request->isXMLHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $bougRepository = $em->getRepository('CoreBundle:Boug');
            $friendsRepository = $em->getRepository('CoreBundle:Friends');

            $user = $this->get('security.token_storage')->getToken()->getUser();

            $nameSearched = $request->get('nameSearched');
            //On récupère la liste des bougs dont le username contient $nameSearched
            $nonFriendBougs = $bougRepository->getBougsByPaternSearched($nameSearched);

            //On récupère les bougs que l'utilisateur a demandé en ami
            $friendsRequestedByBoug = $friendsRepository->getFriendsRequestedBy($user);
            //On retire de la liste ceux qui ne correspondent pas au username recherché
            $this->removeBougFromListByUsernamePattern($friendsRequestedByBoug, $nameSearched);

            //On récupère les bougs qui ont demandé le user en ami
            $bougsRequestingUser = $friendsRepository->getBougsRequesting($user);
            $this->removeBougFromListByUsernamePattern($bougsRequestingUser, $nameSearched);

            //On récupère la liste des amis de l'utilisateur pour la retirer de la liste de bougs            
            $friends = $friendsRepository->getFriendsOfBoug($user);

            //On retire de cette variable les amis ainsi que les demandes non traitées
            $nonFriendBougs = array_diff($nonFriendBougs, $friends, $friendsRequestedByBoug, $bougsRequestingUser);

            //On retire le user pour pas qu'il ne puisse s'auto-demander en ami
            if(($key = array_search($user, $nonFriendBougs)) !== false)
                unset($nonFriendBougs[$key]);

            return new JsonResponse(['nonFriendBougs' => $this->prepareBougsJSON($nonFriendBougs),
                                     'friendsRequestedByBoug' => $this->prepareBougsJSON($friendsRequestedByBoug),
                                     'bougsRequestingUser' => $this->prepareBougsJSON($bougsRequestingUser)
                                     ]);
        }
        return new Response("Error : this is not an Ajax request", 400);
    }

    //Cette fonction est appellée lors des appels Ajax pour une demande d'ami
    //Elle crée la relation d'amitié mais laisse 'waitingForAnswer' à vrai
    public function addFriendAction(Request $request)
    {
        if($request->isXMLHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();

            $idBoug2 = $request->get('idBoug');
            $user    = $this->get('security.token_storage')->getToken()->getUser();
            $boug2   = $em->getRepository('CoreBundle:Boug')->find($idBoug2);

            $newFriendship = new Friends();
            $newFriendship->setBoug1($user)
                          ->setBoug2($boug2);

            $em->persist($newFriendship);
            $em->flush();
                          
            return new JsonResponse([]);
        }
        return new Response("Error : this is not an Ajax request", 400);
    }

    //Pour la page "mes demandes d'amis" : retourne la liste des demandes faites à l'utilisateur
    public function friendRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $friendRequests = $em->getRepository('CoreBundle:Friends')->getBougsRequesting($user);
        return $this->render('CoreBundle:Friends:friendRequests.html.twig', [
                    'friendRequests' => $this->prepareBougsJSON($friendRequests),
                ]);
    }

    //Appellée lors des appels Ajax pour l'acceptation d'une demande d'ami
    public function acceptFriendRequestAction(Request $request)
    {
        if($request->isXMLHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $boug = $request->get('idBoug');

            //On récupère la relation d'amitié
            $friendsEntity = $em->getRepository('CoreBundle:Friends')->findOneBy(['boug1' => $boug, 'boug2' => $user]);

            //Si la demande n'avait pas encore été acceptée on stocke la date d'amitié et on met 'WaitingForAnswer' à null
            if($friendsEntity->getWaitingForAnswer() == true)
            {
                $friendsEntity->setDateSinceAgreement(new \DateTime('now'));
                $friendsEntity->setWaitingForAnswer(false);
                $em->persist($friendsEntity);
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

    //retire d'une liste de bougs ceux dont le username n'inclut pas le pattern recherché
    private function removeBougFromListByUsernamePattern(&$bougs, $nameSearched)
    {
        foreach ($bougs as $key => $boug)
        {
            if (strpos($boug->getUsername(), $nameSearched) === FALSE)
                unset($bougs[$key]);
        }
    }
}

?>
