<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use CoreBundle\Entity\Boug;

class HomePageController extends Controller
{
  public function indexAction(Boug $user)
  {
  		return $this->render('CoreBundle:HomePage:homepage.html.twig', [
  			 'stories' => $this->getStories($user),
  			 ]);
  }

}

?>