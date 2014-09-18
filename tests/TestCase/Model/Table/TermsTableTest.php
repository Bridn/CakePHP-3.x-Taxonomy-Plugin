<?php
namespace Taxonomy\Test\TestCase\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\Event\Event;
use Cake\TestSuite\TestCase;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

use Taxonomy\Model\Table\TermsTable as Term;

class TermsTableTest extends TestCase {

	/**
	* Fixture
	*
	* @var array
	*/
	public $fixtures = [
		'plugin.taxonomy.taxonomy_term'
	];

	/**
	 * setup
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
		$this->connection = ConnectionManager::get('test');
		$this->term = TableRegistry::get('Taxonomy.Terms', [
			'table' => 'taxonomy_terms',
			'connection' => $this->connection
		]);

	}

	/**
	 * teardown
	 *
	 * @return void
	 */
	public function tearDown()
	{
		parent::tearDown();
		unset($this->term);
		TableRegistry::clear();
	}

	public function testAddTermExists()
	{
		$data = ['title' => 'cake', 'type' => 'tag'];
		$result = $this->term->findFirstByTitleAndType($data['title'], $data['type']);
		$expected =  array('Term' => array('id' => 1, 'title' => 'cake'));
		$this->assertEquals($expected, $result);
	}

	public function testAddTermNotExists()
	{
		$data = ['title' => 'soupe', 'type' => 'recette'];
		$result = $this->term->addTerm($data);
		$this->assertTrue(is_int($result));
	}

}