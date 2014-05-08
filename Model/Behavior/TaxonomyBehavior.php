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

	public function __construct(Table $table, array $config = []) {
		parent::__construct($table, $config);
		$this->_table = $table;
        $this->termsRelationship = TableRegistry::get('Taxonomy.TermsRelationships', [
            'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable'
        ]);
		$this->_processAssociations();
	}

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

    }

    public function afterSave(Event $event, Entity $entity)
    {
        $this->termsRelationship->terms->addAndHydrate($entity, $this->_table->alias());
    }

}