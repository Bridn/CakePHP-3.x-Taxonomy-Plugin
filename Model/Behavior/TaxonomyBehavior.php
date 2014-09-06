<?php namespace Taxonomy\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Association;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Event\Event;
use Cake\Utility\Hash;

class TaxonomyBehavior extends Behavior {

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
     * Process Associations
     */
	protected function _processAssociations()
	{
        $this->_table->hasMany('TermsRelationships', [
        	'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable',
        	'foreignKey' => 'reference_id',
        	'conditions' => 'TermsRelationships.reference_model = "'.$this->_table->alias().'"',
        	'dependent' => true
        ]);

        $this->termsRelationship->belongsTo($this->_table->alias(), [
            'className' => $this->_table->alias(),
            'foreignKey' => 'reference_id',
            'conditions' => 'TermsRelationships.reference_model = "'.$this->_table->alias().'"',
        ]);
    }

    /**
     * BeforeFind Callback
     * Add Terms to the model queries
     * Return Term properties parameters as null if title is null
     * @param Event $event, $query, array $options
     */
    public function beforeFind(Event $event, $query, $options = [])
    {
        debug($query);die();
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
        // return $q->formatResults(function($results, $query) {
        //         return $results->map(function($row) {
        //             $row['term'] = 2222;
        //         return $row;
        //     });
        // });
    }

    /**
     * AfterSave Callback
     * Add Terms to the model
     * @param Event $event, Entity $entity
     */
    public function afterSave(Event $event, Entity $entity)
    {
        $this->termsRelationship->terms->addAndSync($entity, $this->_table->alias());
    }

}