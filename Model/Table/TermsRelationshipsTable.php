<?php
namespace Taxonomy\Model\Table;

use Cake\ORM\Table;
use Taxonomy\Model\Table\TaxonomiesAppTable;

class TermsRelationshipsTable extends TaxonomiesAppTable {

	public function initialize(array $config)
	{
        $this->hasMany('Terms', [
        	'className' => 'Taxonomy\Model\Table\TermsTable',
        	'foreignKey' => 'term_id',
        ]);
    }

}