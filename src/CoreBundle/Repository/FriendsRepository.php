<?php

namespace CoreBundle\Repository;

/**
 * FriendsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FriendsRepository extends \Doctrine\ORM\EntityRepository
{
    //Donne tous les bougs demandés en amis qui n'ont pas encore accepté l'invitation
    public function getFriendsRequestedBy($boug)
    {
        $dqlQuery = 'SELECT IDENTITY(f.boug2) FROM CoreBundle:Friends f INNER JOIN CoreBundle:Boug b WHERE b.id = :idboug AND b = f.boug1 AND f.waitingForAnswer = true';

    	$query = $this->_em->createQuery($dqlQuery);
        $query->setParameter('idboug', $boug->getId());

  		return $this->getBougsFromIds($query->getResult());
    }

    //Donne tous les bougs demandés en amis qui n'ont pas encore accepté l'invitation
    public function getBougsRequesting($boug)
    {
        $dqlQuery = 'SELECT IDENTITY(f.boug1) FROM CoreBundle:Friends f INNER JOIN CoreBundle:Boug b WHERE b.id = :idboug AND b = f.boug2 AND f.waitingForAnswer = true';

    	$query = $this->_em->createQuery($dqlQuery);
        $query->setParameter('idboug', $boug->getId());

  		return $this->getBougsFromIds($query->getResult());
    }

    //Donne tous les amis d'un boug
    public function getFriendsOfBoug($boug)
    {
    	$query1 = $this->_em->createQuery('SELECT IDENTITY(f.boug2) FROM CoreBundle:Friends f INNER JOIN CoreBundle:Boug b WHERE b.id = :idboug AND b = f.boug1 AND f.waitingForAnswer = false');
        $query1->setParameter('idboug', $boug->getId());
  		$friendsAddedBy = $query1->getResult();

  		$query2 = $this->_em->createQuery('SELECT IDENTITY(f.boug1) FROM CoreBundle:Friends f INNER JOIN CoreBundle:Boug b WHERE b.id = :idboug AND b = f.boug2 AND f.waitingForAnswer = false');
        $query2->setParameter('idboug', $boug->getId());
  		$friendsAdderOf = $query2->getResult();

  		return $this->getBougsFromIds(array_merge($friendsAddedBy, $friendsAdderOf));
    }

    //Donne tous les bougs ajoutés par un boug (demande ecceptée ou non)
	public function getAllBougsAddedBy($boug)
    {
    	$query = $this->_em->createQuery('SELECT IDENTITY(f.boug2) FROM CoreBundle:Friends f INNER JOIN CoreBundle:Boug b WHERE b.id = :idboug AND b = f.boug1');
        $query->setParameter('idboug', $boug->getId());
  		return $this->getBougsFromIds($query->getResult());
    }

    private function getBougsFromIds($idList)
    {
    	$bougRepository = $this->getEntityManager()->getRepository('CoreBundle:Boug');
    	$bougList = [];
    	foreach ($idList as $key => $id)
    	{
    		$bougList[] = $bougRepository->find($id[1]);
    	}
    	return $bougList;
    }


    //Donne tous les amis (qui ont accepté) qu'un boug a ajouté
    // public function getFriendsAddedBy($boug)
    // {
    // 	$query = $this->_em->createQuery('SELECT IDENTITY(f.boug2) FROM CoreBundle:Friends f INNER JOIN CoreBundle:Boug b WHERE b.id = :idboug AND b = f.boug1 AND f.waitingForAnswer = false');
    //     $query->setParameter('idboug', $boug->getId());
  		// return $query->getResult();
    // }

    // //Donne tous les amis (qui ont accepté) qu'un boug a ajouté
    // public function getFriendsAdderOf($boug)
    // {
    // 	$query = $this->_em->createQuery('SELECT IDENTITY(f.boug2) FROM CoreBundle:Friends f INNER JOIN CoreBundle:Boug b WHERE b.id = :idboug AND b = f.boug1 AND f.waitingForAnswer = false');
    //     $query->setParameter('idboug', $boug->getId());
  		// return $query->getResult();
    // }

}
