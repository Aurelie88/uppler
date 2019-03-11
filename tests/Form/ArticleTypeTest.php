<?php

namespace App\Tests\Form;

use App\Form\ArticleType;
use App\Entity\Article;
use Symfony\Component\Form\Test\TypeTestCase;

class ArticleTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {

        $formData = [
            'titre' => 'Mon titre',
            'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus quaerat perferendis id quidem unde laborum iste debitis, voluptas sunt, necessitatibus reiciendis. Repellendus blanditiis, esse aliquam nobis aut, sequi itaque sint?</p>',
        ];

        $objectToCompare = new Article();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(ArticleType::class, $objectToCompare);

        $object = new Article();
        // ...populate $object properties with the data stored in $formData
        $object->setTitre($formData['titre']);
        $object->setContent($formData['content']);
        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($object, $objectToCompare);

        /*$view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }*/
    }
}
