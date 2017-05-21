<?php


namespace HDB\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class HomePageController extends Controller
{
  public function viewAction($id)
  {
    $url = $this->get('router')->generate('oc_platform_home');
    
    return new RedirectResponse($url);
  }

  public function indexAction(Boug $user)
  {
  		return $this->render('CoreBundle:HomePage:homepage.html.twig', [
  			 'stories' => $this->getStories($user),
  			 ]);
  }

}

?>