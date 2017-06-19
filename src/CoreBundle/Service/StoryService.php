<?php

namespace CoreBundle\Service;
use CoreBundle\Entity\BougStoryReadAccess;

class StoryService
{
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
