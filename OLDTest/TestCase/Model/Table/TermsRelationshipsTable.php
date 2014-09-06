<?php
namespace Taxonomy\Test\TestCase\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\Database\Query;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;
use Taxonomy\Model\Table\TermsRelationshipsTable;

class TermsRelationshipsTableTest extends TestCase {

/**
 * Used for testing counter cache with custom finder
 */
    public function findReferenced(Query $query, array $options) {
        return $query->where(['reference_id' => 1]);
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

/**
 * Method executed before each test
 *
 * @return void
 */
    public function setUp() {
        parent::setUp();
        $this->connection = ConnectionManager::get('test');

        $this->term = TableRegistry::get('Taxonomy.Terms', [
            'table' => 'taxonomy_terms',
            'connection' => $this->connection
        ]);

        $this->termsRelationship = TableRegistry::get('Taxonomy.TermsRelationships', [
            'table' => 'taxonomy_terms_relationships',
            'connection' => $this->connection
        ]);
    }

/**
 * Method executed after each test
 *
 * @return void
 */
    public function tearDown() {
        parent::tearDown();
        unset($this->term, $this->termsRelationship);
        TableRegistry::clear();
    }

/**
 * Test CounterCache
 * count reference_id in terms_relationships to term table
 * @return void
 */
    public function testCounterCache() {
    	$this->_processAssociations();
		$this->termsRelationship->addBehavior('CounterCache', [
            'Terms' => [
            	'term_count' => [
            		'findType' => 'referenced'
            	]
            ]
        ]);
		$before = $this->_getTerm();
		$this->termsRelationship->save($this->_getSecondTermRelationship());
		$after = $this->_getTerm();
		$this->assertEquals(1, $before->get('term_count'));
		$this->assertEquals(2, $after->get('term_count'));
    }

/**
 *
 * Process Assiociations
 * @return void
 */
    protected function _processAssociations()
    {
        $this->term->hasMany('Taxonomy.TermsRelationships', [
            'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable',
            'foreignKey' => 'term_id',
            'dependent' => true,
        ]);

        $this->termsRelationship->belongsTo('Taxonomy.Terms', [
            'className' => 'Taxonomy\Model\Table\TermsTable',
            'foreignKey' => 'term_id',
        ]);
    }

/**
 * Get a second TermRelationship for an existing term
 *
 * @return Entity
 */
    protected function _getSecondTermRelationship() {
        return new Entity([
            'id' => 6,
            'reference_id' => 2,
            'reference_model' => 'Article',
            'term_id' => 1
        ]);
    }

/**
 * Returns entity for term 1
 *
 * @return Entity
 */
    protected function _getTerm() {
        return $this->term->find('all')->where(['id' => 1])->first();
    }

}