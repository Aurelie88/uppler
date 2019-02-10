<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class AppFixtures extends Fixture
{
	private $passwordEncoder;

	function __construct(UserPasswordEncoderInterface $passwordEncoder){
		$this->passwordEncoder=$passwordEncoder;
	}

    public function load(ObjectManager $manager)
    {
        // Ajout des utilisateurs
        $personne[]=array("prenom"=>"Lillian","nom"=>"Watson");
		$personne[]=array("prenom"=>"Maxwell","nom"=>"Barry");
		$personne[]=array("prenom"=>"Destiny","nom"=>"Pennington");
		$personne[]=array("prenom"=>"Lael","nom"=>"Manning");
		$personne[]=array("prenom"=>"Madison","nom"=>"Hines");
		$personne[]=array("prenom"=>"Slade","nom"=>"Sweeney");

		for ($i=0 ; $i<count($personne); $i++){
            $user= new User();
            $user->setNom($personne[$i]['nom']);
            $user->setPrenom($personne[$i]['prenom']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, "123456"));
            $user->setUsername($personne[$i]['prenom']);
            $user->setPicture('default.jpg');
            $manager->persist($user);
        }
        $manager->flush();
    }
}
