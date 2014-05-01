<?php
namespace Taxonomy\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ArticlesTermFixture extends TestFixture {

/**
 * fields property
 *
 * @var array
 */
	public $fields = array(
		'article_id' => ['type' => 'integer', 'null' => false],
		'tag_id' => ['type' => 'integer', 'null' => false],
		'_constraints' => [
			'UNIQUE_TAG2' => ['type' => 'primary', 'columns' => ['article_id', 'tag_id']]
		]
	);

/**
 * records property
 *
 * @var array
 */
	public $records = array(
		array('article_id' => 1, 'tag_id' => 1),
		array('article_id' => 1, 'tag_id' => 2),
		array('article_id' => 2, 'tag_id' => 1),
		array('article_id' => 2, 'tag_id' => 3)
	);
}