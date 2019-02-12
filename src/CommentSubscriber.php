<?php

namespace App;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentSubscriber implements EventSubscriberInterface
{
	
	public static function getSubscribedEvents(){
		return [
			CommentEvent::NAME => 'onAddComment'
		];
	}

	public function onAddComment(CommentEvent $event){
		die('stop');
	}
}