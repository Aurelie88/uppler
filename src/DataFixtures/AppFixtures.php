<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder=$passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Add user
        $users[]=array("name"=>"Lillian","lastname"=>"Watson");
        $users[]=array("name"=>"Maxwell","lastname"=>"Barry");
        $users[]=array("name"=>"Destiny","lastname"=>"Pennington");
        $users[]=array("name"=>"Lael","lastname"=>"Manning");
        $users[]=array("name"=>"Madison","lastname"=>"Hines");
        $users[]=array("name"=>"Slade","lastname"=>"Sweeney");

        //Add Article
        $paragraph[]=array("content"=>"<p>dictum placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris ut quam vel sapien imperdiet ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas.</p>");
        $paragraph[]=array("content"=>"<p>amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus</p>");
        $paragraph[]=array("content"=>"<p>neque. In ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim, gravida sit amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur</p>");
        $paragraph[]=array("content"=>"<p>Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy ultricies ornare, elit elit fermentum risus, at fringilla purus mauris a nunc. In at pede. Cras vulputate velit eu sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta elit, a feugiat tellus lorem eu metus. In lorem. Donec elementum, lorem ut aliquam</p>");
        $paragraph[]=array("content"=>"<p>Elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit amet</p>");
        $paragraph[]=array("content"=>"<p>laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor interdum. Sed auctor odio a purus. Duis elementum, dui quis accumsan convallis, ante lectus convallis est, vitae sodales nisi magna sed dui. Fusce aliquam, enim</p>");
        $paragraph[]=array("content"=>"<p>Lectus pede et risus. Quisque libero lacus, varius et, euismod et, commodo at, libero. Morbi accumsan laoreet ipsum. Curabitur consequat, lectus sit amet luctus vulputate, nisi sem semper erat, in consectetuer ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque et nunc. Quisque ornare tortor at risus. Nunc ac sem ut dolor dapibus gravida. Aliquam tincidunt, nunc ac mattis ornare, lectus ante dictum mi, ac mattis velit justo nec ante. Maecenas mi felis, adipiscing fringilla, porttitor vulputate, posuere</p>");
        $paragraph[]=array("content"=>"<p>Purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim, gravida sit amet, dapibus id, blandit at, nisi. Cum</p>");
        $paragraph[]=array("content"=>"<p>Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit amet lorem semper auctor. Mauris vel turpis. Aliquam adipiscing lobortis risus. In mi pede, nonummy ut, molestie in, tempus eu, ligula. Aenean euismod mauris eu elit. Nulla facilisi. Sed neque. Sed eget lacus. Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis</p>");
        $paragraph[]=array("content"=>"<p>Ac libero nec ligula consectetuer rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas</p>");
        $idArticle=0;
        $user= new User();
            $user->setLastname('CUNY');
            $user->setName('Aurélie');
            $user->setPassword($this->passwordEncoder->encodePassword($user, "admin"));
            $user->setUsername('admin');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPicture('default.jpg');
            $manager->persist($user);

        for ($i=0; $i<count($users); $i++) {
            $user= new User();
            $user->setLastname($users[$i]['lastname']);
            $user->setName($users[$i]['name']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, "123456"));
            $user->setUsername($users[$i]['name']);
            $user->setPicture(($i+1).'.jpg');
            $manager->persist($user);
            //create 0 up to 3 articles by user
            $nbArticleUser=rand(0, 3);
            for ($idx=0; $idx<$nbArticleUser; $idx++) {
                $idArticle++;
                $article = new Article();
                $articleContent="";
                $nbparagraphArticle = rand(2, 10);
                for ($j=0; $j<$nbparagraphArticle; $j++) {
                    $articleContent .= $paragraph[rand(0, 9)]['content'];
                }
                $article->setContent($articleContent);
                $article->setAuthor($user);
                $article->setPicture(Article::IMAGE_ARTICLE_DEFAULT);
                $article->setTitle("Mon super titre".$idArticle);
                $manager->persist($article);
            }
        }
        $manager->flush();

        for ($k=0; $k < 50; $k++) {
            $articleComment=rand(1, $idArticle);
            $comment= new Comment();
            $article=$manager->getRepository('App:Article')->findBy(['title' => "Mon super titre".$articleComment]);
            $comment->setArticle($article[0]);
            $comment->setAuthor($manager->getRepository('App:User')->findBy(['username' => $users[rand(0, 5)]['name']])[0]);
            $comment->setContent("gllriksdj rudfhnc udj hgv dfjch vn dfjc fhvn rdijkcfhvn rodkfujv sdfj esdf jv rd fnv rdjjfv rd");
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
