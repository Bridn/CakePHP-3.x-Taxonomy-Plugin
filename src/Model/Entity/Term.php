<?php namespace Taxonomy\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Inflector;

class Term extends Entity {

	/**
	 * Delete title white spaces, and create a slug from title.
	 * @param $title
	 * @return $title
	 */
	protected function _setTitle($title)
	{
		$this->set('slug', Inflector::slug($title));
		$title = trim($title);

		return $title;
	}
}