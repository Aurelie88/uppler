<?php

namespace App;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Comment;


class CommentEvent extends Event
{
	
	const NAME ="comment.add";
	private $comment;

	public function __construct(Comment $comment)
	{
		$this->comment=$comment;
	}

	public function getComment(){
		return $this->comment;
	}
}