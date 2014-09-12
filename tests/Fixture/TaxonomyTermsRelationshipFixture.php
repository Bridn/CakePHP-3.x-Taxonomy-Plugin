<?php
namespace Taxonomy\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TaxonomyTermsRelationshipFixture extends TestFixture {

/**
 * fields property
 *
 * @var array
 */
	public $fields = array(
		'id' => ['type' => 'integer', 'null' => false],
		'reference_id' => ['type' => 'integer', 'null' => false],
		'reference_model' => ['type' => 'string', 'null' => true],
		'term_id' => ['type' => 'integer', 'null' => false],
		'_constraints' => [
			'UNIQUE_TAG2' => ['type' => 'primary', 'columns' => ['id', 'reference_id', 'term_id']]
		]
	);

/**
 * records property
 *
 * @var array
 */
	public $records = array(
		array('id' => 1, 'reference_id' => 1, 'reference_model' => 'Article', 'term_id' => 1),
		array('id' => 2, 'reference_id' => 2, 'reference_model' => 'Article', 'term_id' => 2),
		array('id' => 3, 'reference_id' => 3, 'reference_model' => 'Article', 'term_id' => 3),
		array('id' => 4, 'reference_id' => 4, 'reference_model' => 'Article', 'term_id' => 4)
	);
}