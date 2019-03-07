<?php

namespace App;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Twig_Environment as Twig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentSubscriber extends AbstractController implements EventSubscriberInterface
{
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig= $twig;
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => 'onMyKernelView'
        ];
    }

    public function onMyKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        
        if (!isset($result['template']) || !isset($result['data'])) {
            return;
        }
        //ajout du commentaire a la suite des autres
        
        $message = new Comment();
        $message->setId(0);
        $message->setContent($_POST['comment']['content']);
        $message->setAuthor($this->getUser());
        $message->setArticle($result['data']['article']);
        array_push($result['data']['commentaires'], $message);

        //ajoute +1 au nb de commentaire
        $result['data']['nbComment']+=1;
        unset($_POST);
        //vide le formulaire
        $newComment = new Comment();
        $formComment= $this->createForm(CommentType::class, $newComment);
        $result['data']['ajoutComment'] = $formComment->createView();

        //$data = array_merge($result['data'], array('myvar' => $this->myVar));
        $rendered = $this->twig->render($result['template'], $result['data']);

        $event->setResponse(new Response($rendered));
    }
}
