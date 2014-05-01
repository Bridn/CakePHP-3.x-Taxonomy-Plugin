<?php
namespace Taxonomy\Model\Table;

use Cake\ORM\Table;
use Taxonomy\Model\Table\TaxonomiesAppTable;

class TermsTable extends TaxonomiesAppTable {

	public function initialize(array $config)
	{
        $this->hasMany('TermsRelationships', [
        	'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable',
        	'foreignKey' => 'term_id',
        	'dependent' => true,
        ]);
    }

}