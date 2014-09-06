<?php namespace Taxonomy\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Taxonomy\Model\Table\TaxonomiesAppTable;

class TermsRelationshipsTable extends TaxonomiesAppTable {

    /**
     * Initialize
     * @param $config
     */
	public function initialize(array $config)
	{
        $this->belongsTo('Taxonomy.Terms', [
        	'className' => 'Taxonomy\Model\Table\TermsTable',
        	'foreignKey' => 'term_id',
        ]);
        $this->addBehavior('Timestamp');
        $this->addBehavior('CounterCache', [
            'Terms' => ['term_count']
        ]);
    }

    /**
     * Add a term relationship
     * @param $entity, $termID, $table alias
     */
    public function addRelationship($entity, $termID = null, $table = null)
    {
        $data = [
            'reference_id' => $entity->id,
            'term_id' => $termID,
            'reference_model' => $table
        ];
        $relationship = $this->newEntity($data);
        $this->save($relationship);
    }

}