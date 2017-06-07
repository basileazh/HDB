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
            $repository = $em->getRepository('CoreBundle:Boug');

            $nameSearched = $request->get('nameSearched');
            $bougs = $repository->getBougsByPaternSearched($nameSearched);

            return new JsonResponse(['bougs' => $this->prepareBougJSON($bougs)]);
        }
        return new Response("Error : this is not an Ajax request", 400);
    }

    public function prepareBougJSON($bougs)
    {
        $json = '{"bougs" : [';
        $numBougs = count($bougs);
        $i = 0;
        foreach ($bougs as $key => $boug)
        {
            $json.='{"id" : "'.$boug->getId().'", "username" : "'.$boug->getUsername().'"}';
            if(++$i !== $numBougs)
                $json.= ',';
        }
        $json.= ']}';
        return $json;
    }

}

?>
