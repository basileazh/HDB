<?php

namespace CoreBundle\Service;
use CoreBundle\Entity\BougStoryReadAccess;

class StoryService
{
    //Fonction retournant la moyenne et le nombre de notes d'une story
	public function getStoryRating($story)
    {
        $readAccesses = $story->getBougStoryReadAccess();
        if(count($readAccesses) == 0)
            return [0,0];
        $mean = 0;
        $count = 0;
        foreach ($readAccesses as $readAccess)
        {
        	if($readAccess->getRating() != null)
            {
                $mean+=$readAccess->getRating();
                $count++;                
            }
        }
        return [$count > 0 ? $mean/$count : 0, $count];
    }

    //Retourne un JSON contenant les opinions de chacun des personnages ainsi que le nombre de fakes, de true...
    //On aura un JSON sous la forme : {"opinions" : [{"idBoug" : 36, "username" : letest, "opinion" : 'fake'}, {}], "countTrue" : 3, "countFake", ...}
    public function getStoryOpinionsJSON($story)
    {
        $characters = $story->getBougStoryIsCharacter();
        $countCharacters = count($characters);
        $json = '{"opinions" : [';
        $i = 0;
        $countTrue = 0;
        $countFake = 0;
        $countDontRemember = 0;
        foreach ($characters as $character)
        {
            $boug = $character->getBoug();
            $opinion = $character->getOpinion();
            if($opinion != null)
            {
                switch ($opinion) {
                    case 'true':
                        $countTrue ++;
                        break;
                    case 'fake':
                        $countFake ++;
                        break;
                    case 'dontremember':
                        $countDontRemember++;
                    default:
                        break;
                }
            }
            $json.= '{"idBoug" : "'.$boug->getId().'",
                      "username" : "'.$boug->getUsername().'",
                      "opinion" : "'.$opinion.'"}';
            if(++$i !== $countCharacters)
                $json.= ',';
        }
        $json .= '], "countTrue" : "'.$countTrue.'", "countFake" : "'.$countFake.'", "countDontRemember" : "'.$countDontRemember.'"}';
        return $json;
    }

    //Retourne l'opinion d'un boug pour une story. Si il n'est pas personnage on retourne 'notCharacter'
    public function bougOpinionForStory($boug, $story)
    {
    	$characters = $story->getBougStoryIsCharacter();
    	if($characters == null)
    		return 'notCharacter';
    	foreach ($characters as $character)
    	{
    		if($character->getBoug() == $boug)
    			return $character->getOpinion();
    	}
    	return 'notCharacter';
    } 

    //TODO: initilisé?
    //Fonction permettant de savoir si un boug est personnage d'une histoire
    // public function bougIsCharacterOfStory($boug, $story)
    // {
    //     $characters = $story->getBougStoryIsCharacter();
    //     if($characters == null)
    //         return false;
    //     foreach ($characters as $character)
    //     {
    //         if($character->getBoug() == $boug)
    //             return true;
    //     }
    //     return false;
    // }
}
