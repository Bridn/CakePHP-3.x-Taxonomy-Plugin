<?php
namespace Taxonomy\Test\TestCase\Model\Behavior;

use Cake\ORM\Association;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Event\Event;
use Cake\TestSuite\TestCase;
use Taxonomy\Model\Behavior\Taxonomy;

/**
 * Used for testing taxonomy
 */
class ArticlesTable extends Table {

	public function initialize(array $config)
	{
		$this->addBehavior('Taxonomy.Taxonomy');
	}

}

class TaxonomyBehaviorTest extends TestCase {

/**
 * Fixture
 *
 * @var array
 */
	public $fixtures = [
		'plugin.taxonomy.taxonomy_article',
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

    	$this->article = $article = new ArticlesTable([
			'alias' => 'Article',
			'plugin' => 'Taxonomy',
			'table' => 'taxonomy_articles',
			'connection' => $this->connection
		]);

		$this->article->termsrelationships = $this->getMock(
			'Taxonomy\Model\Table\TermsRelationshipsTable',
			[],
			[
				'options' => [
					'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable',
					'table' => 'taxonomy_terms_relationships',
					'connection' => 'test'
				]
			]
		);
		// TableRegistry::get('Taxonomy.TermsRelationships', [
		// 	'table' => 'taxonomy_terms_relationships',
		// 	'connection' => $this->connection
		// ]);

		$this->article->termsrelationships->terms = $this->getMock(
			'Taxonomy\Model\Table\TermsTable',
			['config'],
			[
				'options' => [
					'table' => 'taxonomy_terms',
					'connection' => 'test'
				]
			]
		);
		// $this->term = TableRegistry::get('Taxonomy.Terms', [
		// 	'table' => 'taxonomy_terms',
		// 	'connection' => $this->connection
		// ]);
    }

/**
 * Method executed after each test
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->article, $this->termsRelationship, $this->term);
		TableRegistry::clear();
	}

/**
 * Test beforeFind
 * Check if terms_relationships and terms are present in Article Entity
 * @return void
 */
    public function testBeforeFind() {
    	$this->_processAssociations();
		$row = $this->_getArticle();
		$expected = [
			'id' => 1,
			'title' => 'First Article',
			'body' => 'First Article Body',
			'terms_relationships' => [
				0 => [
					'id' => 1,
					'reference_id' => 1,
					'reference_model' => 'Article',
					'term_id' => 1,
					'term' => [
						'id' => 1,
						'title' => 'cake',
						'type' => 'tag',
						'term_count' => 1
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
        $this->article->hasMany('TermsRelationships', [
        	'table' => 'taxonomy_terms_relationships',
        	'foreignKey' => 'reference_id',
        	'conditions' => 'TermsRelationships.reference_model = "'.$this->article->alias().'"',
        	'dependent' => true,
        	'connection' => $this->connection
        ]);

        $this->article->termsrelationships->belongsTo($this->article->alias(), [
            'className' => $this->article->alias(),
            'foreignKey' => 'reference_id',
            'conditions' => 'TermsRelationships.reference_model = "'.$this->article->alias().'"',
            'connection' => $this->connection
        ]);
    }

/**
 * Get a new Article
 *
 * @return Entity
 */
	protected function _getNewArticle() {
		return new Entity([
			'id' => 4,
			'title' => 'New Article',
			'body' => 'A New Body'
		]);
	}

/**
 * Get a new Term
 *
 * @return Entity
 */
	protected function _getNewTerm() {
		return new Entity([
			'id' => 4,
			'title' => 'fresh',
			'type' => 'tag'
		]);
	}

/**
 * Get a new TermRelationship
 *
 * @return Entity
 */
	protected function _getNewTermRelationship() {
		return new Entity([
			'id' => 5,
			'reference_id' => 1,
			'reference_model' => 'Article',
			'term_id' => 4
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

/**
 * Returns entity for all articles
 *
 * @return Entity
 */
	protected function _getAllArticles() {
		return $this->article->find('all')->toArray();
	}

/**
 * Returns entity for term 1
 *
 * @return Entity
 */
	protected function _getTerm() {
		return $this->term->find('all')->where(['id' => 1])->first();
	}

/**
 * Returns entity for all terms_relationships
 *
 * @return Entity
 */
	protected function _getAllTermsRelationships() {
		return $this->termsRelationship->find('all')->toArray();
	}

}