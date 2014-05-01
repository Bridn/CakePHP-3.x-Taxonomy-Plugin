<?php
namespace Taxonomy\Test\TestCase\Model\Behavior;

use Cake\ORM\Association;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Event\Event;
use Taxonomy\Model\Behavior\Taxonomy;
use Cake\TestSuite\TestCase;

class TaxonomyBehaviorTest extends TestCase {

/**
 * Fixture
 *
 * @var array
 */
	public $fixtures = [
		'plugin.taxonomy.article',
		'plugin.taxonomy.articles_term',
		'plugin.taxonomy.term'
	];

/**
 * Method executed before each test
 *
 * @return void
 */
    public function setUp() {
    	$this->connection = ConnectionManager::get('test');
    	$this->article = TableRegistry::get('Articles', [
			'table' => 'taxonomy_articles',
			'connection' => $this->connection
		]);
    	parent::setUp();
    }

/**
 * Method executed after each test
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->article);
		TableRegistry::clear();
	}

    public function testBeforeFind() {
		$this->article->addBehavior('Taxonomy.Taxonomy');
    }

}

