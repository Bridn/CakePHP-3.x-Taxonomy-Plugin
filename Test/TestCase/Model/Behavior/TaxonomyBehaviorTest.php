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

/**
 * Used for testing taxonomy
 */
class ArticleTable extends Table {
}

class TaxonomyBehaviorTest extends TestCase {

/**
 * Fixture
 *
 * @var array
 */
	public $fixtures = [
		'plugin.taxonomy.taxonomy_article',
		'plugin.taxonomy.terms_relationship',
		'plugin.taxonomy.term'
	];

/**
 * Method executed before each test
 *
 * @return void
 */
    public function setUp() {
    	parent::setUp();
    	$this->connection = ConnectionManager::get('test');
    	$this->article = new ArticleTable([
			'alias' => 'Article',
			'table' => 'taxonomy_articles',
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
		unset($this->article);
		TableRegistry::clear();
	}

/**
 * Test beforeFind
 * Check if terms_relationships and terms are present in Article Entity
 * @return void
 */
    public function testBeforeFind() {
    	$this->_processAssociations();
		$this->article->addBehavior('Taxonomy.Taxonomy');
		$row = $this->_getArticle();
		$expected = [
			'id' => 1,
			'title' => 'First Article',
			'body' => 'First Article Body',
			'terms_relationships' => [
				0 => [
					'reference_id' => 1,
					'reference_model' => 'Article',
					'term_id' => 1,
					'term' => [
						'id' => 1,
						'title' => 'cake',
						'type' => 'tag'
					]
				]
			],
		];
		$this->assertEquals($expected, $row->toArray());
    }

/**
 *
 * Process Assiociations
 * @return void
 */
	protected function _processAssociations()
	{
		$termsRelationships = TableRegistry::get('TermsRelationshipsTable', [
			'table' => 'terms_relationships',
			'connection' => $this->connection
		]);

        $this->article->hasMany('TermsRelationships', [
        	'table' => 'terms_relationships',
        	'foreignKey' => 'reference_id',
        	'conditions' => 'TermsRelationships.reference_model = "'.$this->article->alias().'"',
        	'dependent' => true,
        	'connection' => $this->connection
        ]);

        $termsRelationships->belongsTo($this->article->alias(), [
            'className' => $this->article->alias(),
            'foreignKey' => 'reference_id',
            'conditions' => 'TermsRelationships.reference_model = "'.$this->article->alias().'"',
            'connection' => $this->connection
        ]);
    }

/**
 * Returns entity for article 1
 *
 * @return Entity
 */
	protected function _getArticle() {
		return $this->article->find('all')->where(['id' => 1])->first();
	}

}