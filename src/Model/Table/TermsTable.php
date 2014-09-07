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
     * Find first term by title and type
     * @param string $title, string $type
     */
    public function findFirstByTitleAndType($title = null, $type = null)
    {
        return $this->find()
        ->where(['title' => $title, 'type' => $type])
        ->first();
    }

    /**
     * Add a single Term without relationships
     * @param array $data
     */
    public function addTerm(array $data)
    {
        //CREATE
        if(!empty($data['title']) && !empty($data['type']))
        {
            // Check if exists in DB
            $term = $this->findFirstByTitleAndType($data['title'], $data['type']);
            if(empty($term))
            {
                $term = $this->newEntity($data);
           	    $this->save($term);
            }
            return $term->id;
        }
        return false;
    }

    /**
     * Update a single Term without relationships
     * @param array $data, $string id
     */
    public function updateTerm(array $data, $id = null)
    {
        //UPDATE
        if(!is_null($id))
        {
            $term = $this->get($id);
            $term = $this->patchEntity($term, $data);
            $this->save($term);
        }
        return false;
    }

    /**
     * Add terms and sync relationships
     * @param $entity, $table
     */
    public function addAndSync(Entity $entity, $table = null)
    {
        if (!is_null($entity->Taxonomy))
        {
        	foreach($entity->Taxonomy as $type => $terms)
        	{
                // Implode input data to array
                $termsInput = $this->_makeArray($terms);

                // Look for already saved relationships for this reference_id and this type of taxonomy
                $termsSaved = $this->termsrelationships->findAllByReferenceIDAndType($entity->id, $type);

                // Saved Terms Object To Array
                $termSavedArray = array();
                foreach ($termsSaved as $key => $termSaved) {
                    $termSavedArray[] = $termSaved->term->title;
                }

                $termsRelationshipToSave = array_diff($termsInput, $termSavedArray);
                $termsRelationshipToDelete = array_diff($termSavedArray, $termsInput);

                if(!empty($termsRelationshipToDelete))
                {
                    foreach($termsRelationshipToDelete as $term)
                    {
                        $this->termsrelationships->cleanRelationship($entity, $term, $type);
                    }
                }
                if(!empty($termsRelationshipToSave))
                {
                    foreach($termsRelationshipToSave as $term)
                    {
                        $data = array('type' => $type, 'title' => $term);
                        $termID = $this->addTerm($data);
                        if($termID)
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
     * @param string $terms
     * @return $terms [array]
     */
    private function _makeArray($terms = null)
    {
		if (is_string($terms))
		{
			$terms = explode(';', $terms);
		} elseif (!is_array($terms)) {
			$terms = array(null);
		}
		return array_map('trim', $terms);
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