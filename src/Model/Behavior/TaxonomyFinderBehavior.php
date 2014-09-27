<?php namespace Taxonomy\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Association;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Event\Event;
use Taxonomy\Model\Behavior\TaxonomyAbstractBehavior;

class TaxonomyFinderBehavior extends TaxonomyAbstractBehavior {

	/**
	 * Construct
	 * @param Table $table, array $config
	 */
	public function __construct(Table $table, array $config = []) {
		parent::__construct($table, $config);
		$this->_table = $table;
		$this->termsRelationship = TableRegistry::get('Taxonomy.TermsRelationships', [
			'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable'
		]);
		$this->_processAssociations();
	}

	/**
	 * BeforeFind Callback
	 * Add Terms to the associated model object
	 * @param Event $event, $query, array $options
	 */
	public function beforeFind(Event $event, $query, $options = [])
	{
		$query->contain([
			'TermsRelationships' => [
				'foreignKey' => 'reference_id',
				'Terms' => [
					'foreignKey' => 'term_id',
					'queryBuilder' => function($q) {
						return $q->where(['Terms.title !=' => '']);
					}
				]
			]
		]);

		/**
		 * Add an array of terms(id,title,...) to query result.
		 */
		$query->formatResults(function($results, $query)
		{
			return $results->map(function($row)
			{

				if ( ! empty($row['terms_relationships']))
				{
					$terms = array();

					foreach($row['terms_relationships'] as $k => $v)
					{
						$terms[$v['term']['type']][$k] = [
							'title' => $v['term']['title'],
							'id' => $v['term']['id'],
							'reference_id' => $v['reference_id'],
							];
					}

					$row['terms_format'] = $terms;
				}

				return $row;
			});
		});
	}

	/**
	 * listAllTermsByType
	 * List all terms by type (e.g. category) for a table
	 * @param  null $type
	 */
	public function listAllTermsByType($type = null)
	{
		return $this->termsRelationship->find()->where(['reference_table' => $this->_table->alias(), 'Terms.type =' => $type])
		->contain([
			'Terms' => [
				'foreignKey' => 'term_id'
			]
		])
		->group('Terms.title')
		->all();
	}

	/**
	 * listAllByTableAndByTerm for a table
	 * List all terms by table and term id
	 * @param  null $id
	 */
	public function listAllByTableAndByTerm($id = null)
	{
		return $this->_table->find()
			->where(['tr.reference_table' => $this->_table->alias(), 't.id =' => $id])
		    ->join([
		        'tr' => [
		            'table' => 'terms_relationships',
		            'type' => 'INNER',
		            'conditions' => 'tr.reference_id = '. $this->_table->alias().'.id',
		        ],
		        't' => [
		            'table' => 'terms',
		            'type' => 'INNER',
		            'conditions' => 'tr.term_id = t.id',
		        ]
		    ])
		    ->all();
	}

}