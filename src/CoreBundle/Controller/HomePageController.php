<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use CoreBundle\Entity\Boug;

class HomePageController extends Controller
{
  public function indexAction()
  {
    $bougRepository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('CoreBundle:Boug');

    $bougs = $bougRepository->findAll();

    $boug = $bougs[0];

  	return $this->render('CoreBundle:HomePage:homepage.html.twig', [
      'boug' => $boug,
    ]);
  }

}

?>
