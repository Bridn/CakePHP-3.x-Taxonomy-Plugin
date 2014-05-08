<?php namespace Taxonomy\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Taxonomy\Model\Table\TaxonomiesAppTable;

class TermsRelationshipsTable extends TaxonomiesAppTable {

	public function initialize(array $config)
	{
        $this->belongsTo('Taxonomy.Terms', [
        	'className' => 'Taxonomy\Model\Table\TermsTable',
        	'foreignKey' => 'term_id',
        ]);
    }

    public function addRelationship($entity, $termID, $table)
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