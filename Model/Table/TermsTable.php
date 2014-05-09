<?php namespace Taxonomy\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Taxonomy\Model\Table\TaxonomiesAppTable;

class TermsTable extends TaxonomiesAppTable {

	public function initialize(array $config)
	{
        $this->hasMany('Taxonomy.TermsRelationships', [
        	'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable',
        	'foreignKey' => 'term_id',
        	'dependent' => true,
        ]);
    }

    //add a single Term
    public function addTerm(array $data)
    {
        $term = $this->newEntity($data);
        if($this->_checkTerm($term))
    	{
       		$this->save($term);
        	return $term->id;
        }
    }

    //add and Hydrate relationship table
    public function addAndHydrate($entity, $table)
    {
    	foreach($entity->Taxonomy as $type => $terms)
    	{
    		$terms = $this->_inputExplode($terms);
    		foreach($terms as $term)
    		{
    			if($this->_checkTerm($term))
    			{
		    		$data = array('type' => $type, 'title' => $term);
		    		$termID = $this->addTerm($data);
		    		$this->termsrelationships->addRelationship($entity, $termID, $table);
	    		}
    		}
    	}
    }

    //Explode term string
    private function _inputExplode($terms)
    {
		if (is_string($terms))
		{
			$terms = explode(';', $terms);
		} elseif (!is_array($terms)) {
			$terms = array(null);
		}
		return $terms;
    }

    //Check if term is not empty
    private function _checkTerm($term)
    {
    	if(trim($term) !== '')
    	{
    		return $term;
    	}
    	return false;
    }

}