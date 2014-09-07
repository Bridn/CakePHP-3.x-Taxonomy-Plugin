<?php namespace Taxonomy\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\ORM\Entity;
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
     * Find all terms by reference_id
     * @param $reference_id
     */
    public function findAllByReferenceID($id = null)
    {
        return $this->find()->where(['reference_id' => $id])
        ->contain([
            'Terms' => [
                'foreignKey' => 'term_id'
            ]
        ])
        ->all();
    }

    /**
     * Find first termsRelationship by reference_id, title and type
     * @param $reference_id, $title, $type
     */
    public function findFirstByRefIDTitleType($reference_id = null, $title = null, $type = null)
    {
        return $this->find()
        ->where(['reference_id' => $reference_id])
        ->contain([
            'Terms' => [
                'foreignKey' => 'term_id',
                'queryBuilder' => function($q) use($title, $type) {
                    return $q->where(['Terms.title =' => $title, 'Terms.type =' => $type]);
                }
            ]
        ])
        ->first();
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
        return $relationship->id;
    }

    /**
     * Clean a term relationship
     * @param $entity, $title, $type
     */
    public function cleanRelationship($entity, $title, $type)
    {
        $termToClean = $this->findFirstByRefIDTitleType($entity->id, $title, $type);
        $query = $this->query();
        $query->delete()
            ->where(['id' => $termToClean->id])
            ->execute();
    }

}