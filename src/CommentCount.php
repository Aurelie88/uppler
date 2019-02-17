<?php

namespace App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;

use Doctrine\ORM\EntityManager;

use Doctrine\Common\Persistence\ObjectManager;


class CommentCount
{
	private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em= $em;
    }

	public function displayNbComment(Response $response)
	{

		/*$dom = new \DOMDocument;
		//die($response->__toString());
		//die(var_dump(get_class_methods($response)));
		$dom->loadHTML($response->getContent());
		

		$xpath= new \DOMXpath($dom);
		$result = $xpath->query("//*[@id='nbComment']");
		die(var_dump($result));
		/*die(var_dump($request));
		$content = $response->getContent();
		$article=$this->em->getRepository('App:Article')->find(24);
		$nbcoment=$this->em->getRepository('App:Comment')->nbCommentaireArticle($article)[0]['nb'];
		//die(var_dump($nbcoment));
		$content=str_replace( "<div id=\"nbComment\">" ,"<div id=\"nbComment\">".$nbcoment  , $content);
		$content=str_replace("</commentaire>", "</commentaire>");
		die($content);*/
		return $response;
	}
}
