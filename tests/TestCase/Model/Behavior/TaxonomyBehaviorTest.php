<?php
namespace Taxonomy\Test\TestCase\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Association;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Event\Event;
use Cake\TestSuite\TestCase;

class TaxonomyBehaviorTest extends TestCase {

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