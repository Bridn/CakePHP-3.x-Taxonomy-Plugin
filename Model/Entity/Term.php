<?php namespace Taxonomy\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Inflector;

class Term extends Entity {

	public function setTitle($title)
	{
        $this->set('slug', Inflector::slug($title));
        $title = trim($title);
        return $title;
    }
}