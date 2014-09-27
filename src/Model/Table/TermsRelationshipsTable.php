<?php namespace Taxonomy\Model\Table;

use Taxonomy\Model\Table\TaxonomiesAppTable;
use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\Event\Event;

class TermsRelationshipsTable extends TaxonomiesAppTable {

	/**
	 * Initialize
	 * @param $config
	 */
	public function initialize(array $config = [])
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
	 * Find first by reference_id and term_id.
	 * @param string $reference_id, string $term_id
	 */
	public function findFirstByReferenceIDAndTermID($reference_id = null, $term_id = null)
	{
		return $this->find()
		->where(['reference_id' => $reference_id, 'term_id' => $term_id])
		->first();
	}

	/**
	 * Find all terms by reference_id.
	 * @param string $id, string $type
	 */
	public function findAllByReferenceIDAndType($id = null, $type = null)
	{
		return $this->find()->where(['reference_id' => $id, 'Terms.type =' => $type])
		->contain([
			'Terms' => [
				'foreignKey' => 'term_id'
			]
		])
		->all();
	}

	/**
	 * Find first termsRelationship by reference_id, title and type.
	 * @param string $reference_id, string $title, string $type
	 */
	public function findFirstByReferenceIDAndTitleAndType($reference_id = null, $title = null, $type = null)
	{
		return $this->find()
		->where(['reference_id' => $reference_id, 'Terms.title =' => $title, 'Terms.type =' => $type])
		->contain([
			'Terms' => [
				'foreignKey' => 'term_id'
			]
		])
		->first();
	}

	/**
	 * Add a term relationship.
	 * @param Entity $entity, string $termID, string $table (alias)
	 * @return string $relationship->id
	 */
	public function addRelationship(Entity $entity, $termID = null, $table = null)
	{
		$data = [
			'reference_id' => $entity->id,
			'term_id' => $termID,
			'reference_model' => $table
		];
		$relationship = $this->newEntity($data);
		$this->save($relationship);

		return $relationship->id;
	}

	/**
	 * Clean a term relationship.
	 * @param Entity $entity, string $title, string $type
	 */
	public function cleanRelationship(Entity $entity, $title = null, $type = null)
	{
		$termRelationshipToClean = $this->findFirstByReferenceIDAndTitleAndType($entity->id, $title, $type);

		// CounterCache decrements to 0 only with an Entity as param,
		// not ID ($termRelationshipToClean->id wont work)
    	return $this->delete($termRelationshipToClean);
	}

	public function beforeSave(Event $event, Entity $entity)
	{
		$termRelationshipSaved = $this->findFirstByReferenceIDAndTermID($entity->reference_id, $entity->term_id);

		if ( ! empty($termRelationshipSaved))
		{
			return false;
		}
	}
}