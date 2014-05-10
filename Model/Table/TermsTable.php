<?php namespace Taxonomy\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Taxonomy\Model\Table\TaxonomiesAppTable;
use Cake\ORM\Entity;
use Cake\Event\Event;

class TermsTable extends TaxonomiesAppTable {

    /**
     * Initialize
     * @param $config
     */
	public function initialize(array $config)
	{
        $this->hasMany('Taxonomy.TermsRelationships', [
        	'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable',
        	'foreignKey' => 'term_id',
        	'dependent' => true,
        ]);
        $this->addBehavior('Timestamp');
    }

    /**
     * Find First By Title
     * @param $term
     * @return void
     */
    public function findFirstByTitle($title)
    {
        return $this->find()->where(['title' => $title])->first();
    }

    /**
     * Add a single Term without relationships
     * @param $data
     */
    public function addTerm(array $data)
    {
        $term = $this->newEntity($data);
        if($this->_notEmptyTerm($term))
    	{
       		$this->save($term);
        	return $term->id;
        }
    }

    /**
     * Add terms and hydrate relationships
     * @param $entity, $table
     */
    public function addAndHydrate($entity, $table = null)
    {
        if ( ! is_null($entity->Taxonomy) )
        {
        	foreach($entity->Taxonomy as $type => $terms)
        	{
        		$terms = $this->_inputExplode($terms);
        		foreach($terms as $term)
        		{
        			if ($this->_notEmptyTerm($term))
        			{
    		    		$data = array('type' => $type, 'title' => $term);
    		    		$termID = $this->addTerm($data);
    		    		$this->termsrelationships->addRelationship($entity, $termID, $table);
    	    		}
        		}
        	}
        }
    }

    /**
     * Explode string by ';' to array
     * @param $terms
     * @return  $terms [array]
     */
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

    /**
     * Check if Term is not empty
     * @param $term
     * @return false [if Term is empty]
     */
    private function _notEmptyTerm($term)
    {
    	if (trim($term) !== '')
    	{
    		return $term;
    	}
    	return false;
    }

    /**
     * Update term if exist
     * @param Event $event, Entity $entity
     * @return boolean
     */
    public function beforeSave(Event $event, Entity $entity)
    {
        $term = $this->findFirstByTitle($entity->title);
        if (!is_null($term))
        {
            //doesn't work
            $entity = $this->get($term->id);
        }
    }

}