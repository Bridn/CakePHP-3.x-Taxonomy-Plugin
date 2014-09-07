<?php namespace Taxonomy\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Taxonomy\Model\Table\TaxonomiesAppTable;
use Cake\ORM\Entity;
use Cake\Event\Event;
use Cake\Utility\Hash;

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
    public function findFirstByTitle($title = null)
    {
        return $this->find()->where(['title' => $title])->first();
    }

    /**
     * Add a single Term without relationships
     * @param array $data
     */
    public function addTerm(array $data)
    {
        if($this->_notEmptyTerm($data['title']) === false)
        {
            return false;
        }

        $term = $this->findFirstByTitle(trim($data['title']));

        //UPDATE
        if(isset($term->id) && !is_null($term->id))
        {
            $term = $this->get($term->id);
            $term = $this->patchEntity($term, $data);
        //CREATE
        } else {
            $term = $this->newEntity($data);
        }
        //SAVE
       	$this->save($term);
        return $term->id;
    }

    /**
     * Add terms and sync relationships
     * @param $entity, $table
     */
    public function addAndSync($entity, $table = null)
    {
        if (!is_null($entity->Taxonomy))
        {
            $termsInDB = $this->termsrelationships->findAllByReferenceID($entity->id);

        	foreach($entity->Taxonomy as $type => $terms)
        	{
                // Implode input data to array
                $terms = $this->_inputExplode($terms);
                // Clean relationships
                foreach ($termsInDB as $key => $value) {
                    $check = array_search($value->term->title, $terms);
                    if($check === false)
                    {
                       $this->termsrelationships->cleanRelationship($entity, $value->term->title, $type);
                    }
                }
                // Add Term And Relationship
        		foreach ($terms as $title)
        		{
        			if ($this->_notEmptyTerm($title))
        			{
    		    		$data = array('type' => $type, 'title' => $title);
    		    		$termID = $this->addTerm($data);
                        if ($termID)
                        {
    		    		    $this->termsrelationships->addRelationship($entity, $termID, $table);
                        }
    	    		}
        		}
        	}
        }
    }

    /**
     * Explode string by ';' to array
     * @param $terms
     * @return $terms [array]
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
     * Check if Term title is not empty
     * @param $title
     * @return false [if title is empty]
     */
    private function _notEmptyTerm($title)
    {
        if (trim($title) === '')
    	{
    		return false;
    	}
        return true;
    }

    /**
     * @param Event $event, Entity $entity
     * @return void
     */
    public function afterSave(Event $event, Entity $entity)
    {
        // Clean DB from unused terms. (by counter cache value)
        $termsNotUsed = $this->find()->where(['term_count =' => 0])->all();
        foreach($termsNotUsed as $term)
        {
            $query = $this->query();
            $query->delete()
                ->where(['id' => $term->id])
                ->execute();
        }
    }

}