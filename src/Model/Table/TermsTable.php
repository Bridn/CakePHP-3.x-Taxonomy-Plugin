<?php namespace Taxonomy\Model\Table;

use Taxonomy\Model\Table\TaxonomiesAppTable;
use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\Event\Event;

class TermsTable extends TaxonomiesAppTable {

	/**
	 * Initialize
	 * @param $config
	 */
	public function initialize(array $config = [])
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
	public function addTerm(array $data = [])
	{
		//CREATE
		if ( ! empty($data['title']) && !empty($data['type']))
		{
			// Check if exists in DB
			$term = $this->findFirstByTitleAndType($data['title'], $data['type']);

			if (empty($term))
			{
				$term = $this->newEntity($data);
				if ( ! $this->save($term))
				{
					return false;
				}
			}

			return $term->id;
		}
	}

	/**
	 * Update a single Term without relationships
	 * @param array $data, $string id
	 */
	public function updateTerm(array $data = [], $id = null)
	{
		//UPDATE
		if ( ! is_null($id))
		{
			$term = $this->get($id);
			$term = $this->patchEntity($term, $data);
			if ($this->save($term))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Add terms and sync relationships
	 * @param $entity, $table
	 * @return array $sync
	 */
	public function addAndSync(Entity $entity, $table = null)
	{
		if ( ! is_null($entity->Taxonomy) && !empty($table))
		{
			$sync = array();

			foreach($entity->Taxonomy as $type => $terms)
			{
				// Implode input data to array and delete duplicate values
				$termsInput = array_unique($this->_makeArray($terms));

				// Look for already saved relationships for this reference_id and this type of taxonomy
				$termsSaved = $this->termsrelationships->findAllByReferenceIDAndType($entity->id, $type);

				// Saved Terms Object To Array
				$termSavedArray = [];

				foreach ($termsSaved as $termSaved)
				{
					$termSavedArray[] = $termSaved->term->title;
				}

				// Compare
				$termsRelationshipToSave = array_diff($termsInput, $termSavedArray);
				$termsRelationshipToDelete = array_diff($termSavedArray, $termsInput);

				// Add and Clean
				$added = $this->toAdd($termsRelationshipToSave, $entity, $type, $table);
				$cleaned = $this->toClean($termsRelationshipToDelete, $entity, $type);

				// Push result to array
				$sync['added'] = $added;
				$sync['cleaned'] = $cleaned;
			}

			return (array) $sync;
		}
	}

	/**
	 * Loop through terms and add relationships
	 * @param array $termsRelationshipToSave, Entity $entity, string $type, string $table
	 * @return array $added
	 */
	private function toAdd(array $termsRelationshipToSave = [], Entity $entity, $type = null, $table = null)
	{
		if ( ! empty($termsRelationshipToSave) && is_array($termsRelationshipToSave))
		{

			$added = array();

			foreach($termsRelationshipToSave as $term)
			{
				$data = [
					'type' => $type,
					'title' => $term
				];

				// Add and get the id of created/updated term
				$termID = $this->addTerm($data);
				if ($termID)
				{
					$added[] = $this->termsrelationships->addRelationship($entity, $termID, $table);
				}
			}

			return (array) $added;
		}
	}

	/**
	 * Loop through terms and clean relationships
	 * @param array $termsRelationshipToDelete, Entity $entity, string $type
	 * @return array $cleaned
	 */
	private function toClean(array $termsRelationshipToDelete = [], Entity $entity, $type = null)
	{
		if ( ! empty($termsRelationshipToDelete) && is_array($termsRelationshipToDelete))
		{

			$cleaned = array();

			foreach($termsRelationshipToDelete as $title)
			{
				$cleaned[] = $this->termsrelationships->cleanRelationship($entity, $title, $type);
			}

			return (array) $cleaned;
		}
	}

	/**
	 * Explode string by ';' to array and trim all values
	 * @param string $terms
	 * @return array $terms
	 */
	protected function _makeArray($terms = null)
	{
		if (is_string($terms))
		{
			$terms = explode(';', $terms);
		} elseif ( ! is_array($terms)) {
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