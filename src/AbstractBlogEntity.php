<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

abstract class AbstractBlogEntity
{
    abstract public function getContent();
    abstract public function setContent(string $content);

    abstract public function getAuthor();
    abstract public function setAuthor(?User $author);
}
