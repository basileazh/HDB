<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use CoreBundle\Entity\Boug;
use CoreBundle\Entity\Story;

use Doctrine\ORM\EntityRepository;

class BougController extends Controller
{
    //Pour la page du profil des utilisateurs. En fonction de si c'est l'utilisateur qui demande sa propre page
    //le traitement est différent
    public function bougProfileAction($username, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        //Le boug dont on veut la page
        $boug = $em->getRepository('CoreBundle:Boug')->findOneBy(['username' => $username]);
        //La liste des amis du boug
        $friends = $em->getRepository('CoreBundle:Friends')->getFriendsOfBoug($boug);  

        //Si c'est l'utilisateur qui demande sa propre page
        if($boug == $user)
        {
            return $this->render('CoreBundle:Boug:profile.html.twig', ['user'  => $user, 'friends' => $friends]);
        }
        else //Si on consulte la page d'un autre boug
        {
            throw new NotFountHttpException("Erreur : vous ne pouvez pas accéder au profil des autres utilisateurs pour le moment", 404);
        }
    }
}

?>
