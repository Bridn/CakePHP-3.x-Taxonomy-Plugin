<?php
namespace Taxonomy\Test\TestCase\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\Event\Event;
use Cake\TestSuite\TestCase;

class TermsTableTest extends TestCase {

	public function setUp() {
		parent::setUp();
	}

	/**
	* Fixture
	*
	* @var array
	*/
	public $fixtures = [
			'plugin.taxonomy.taxonomy_terms_relationship',
			'plugin.taxonomy.taxonomy_term'
	];

}