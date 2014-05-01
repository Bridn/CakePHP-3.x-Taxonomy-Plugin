<?php
namespace Taxonomy\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Association;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Event\Event;

class TaxonomyBehavior extends Behavior {

	public function __construct(Table $table, array $config = []) {
		parent::__construct($table, $config);
		$this->_table = $table;
		$this->_processAssociations($this->_table);
	}

	public function _processAssociations($table)
	{
        $this->_table->hasMany('TermsRelationships', [
        	'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable',
        	'foreignKey' => 'relationship_id',
        	'conditions' => 'TermsRelationships.table = "'.$this->_table->alias().'"',
        	'dependent' => true
        ]);

        $termsRelationships = TableRegistry::get('Taxonomy.TermsTable', [
            'className' => 'Taxonomy\Model\Table\TermsTable'
        ]);

        $termsRelationships->belongsTo($this->_table->alias(), [
            'className' => $this->_table->alias(),
            'foreignKey' => 'relationship_id',
            'conditions' => 'TermsRelationships.table = "'.$this->_table->alias().'"',
        ]);
    }

    public function beforeFind(Event $event, $query, $options = [])
    {
        $contain = ['TermsRelationships' => ['Terms']];
        $query->contain($contain);
        // $query->formatResults(function($results) {
        //     return $this->_rowMapper($results);
        // }, $query::PREPEND);
    }

    // public function _rowMapper($results)
    // {

        // $contain = ['TermsRelationships' => ['Terms']];
        // $query->contain($contain);
        // $termsRelationships = TableRegistry::get('Taxonomy.TermsRelationships');
        // $terms = TableRegistry::get('Taxonomy.Terms');

        // foreach($results as $key => $result)
        // {
        //     $termsRs = $termsRelationships->find('all')
        //     ->where(['relationship_id' => $result->id]);

        //     foreach($termsRs as $key => $value){
        //         $query = $terms->find('all', [
        //             'conditions' => ['id' => $value->term_id]
        //         ])
        //         ->first();
        //     }
        // }



        //     if( empty($result[$Model->alias][$Model->primaryKey]) ){    return false; }

        //     if( empty($result['TermR']) ) { return false; }

        //     $query = array();



        //     if(empty($query)){ return false; }
        //         $results[$key]['Taxonomy'] = Set::combine($row, '{n}.Term.id', '{n}', '{n}.Term.type');
        //     }

       // return $results;
        // }
}