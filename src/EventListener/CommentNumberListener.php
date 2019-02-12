<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class CommentNumberListener implements EventSubscriberInterface
{
	
	public function onAddComment(CommentEvent $event){
		throw new Exception("Error Processing Request", 1);
		
	}

	public static function getSubscribedEvents(){
		return [
			'add.comment' => 'onAddComment'];
	}
}