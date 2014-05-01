<?php
namespace Taxonomy\Model\Behavior;

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
		$this->_processAssociations();
	}

	protected function _processAssociations()
	{
        $termsRelationships = TableRegistry::get('Taxonomy.TermsRelationshipsTable', [
            'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable'
        ]);

        $this->_table->hasMany('TermsRelationships', [
        	'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable',
        	'foreignKey' => 'reference_id',
        	'conditions' => 'TermsRelationships.reference_model = "'.$this->_table->alias().'"',
        	'dependent' => true
        ]);

        $termsRelationships->belongsTo($this->_table->alias(), [
            'className' => $this->_table->alias(),
            'foreignKey' => 'reference_id',
            'conditions' => 'TermsRelationships.reference_model = "'.$this->_table->alias().'"',
        ]);
    }

    public function beforeFind(Event $event, $query, $options = [])
    {
        $contain = ['TermsRelationships' => ['Terms']];
        $query->contain($contain);
    }

}