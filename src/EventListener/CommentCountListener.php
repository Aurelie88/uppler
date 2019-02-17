<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use App\CommentCount;


class CommentCountListener
{
	protected $commentCount;

	public function __construct(CommentCount $commentCount){
		$this->commentCount=$commentCount;
	}

	public function processComment(FilterResponseEvent $event){
		//die(var_dump(get_class_methods($event)));
		$response = $this->commentCount->displayNbComment($event->getResponse());
		$event->setResponse($response);
	}

}