<?php namespace Taxonomy\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Association;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Event\Event;
use Taxonomy\Model\Behavior\TaxonomyAbstractBehavior;

class TaxonomySyncBehavior extends TaxonomyAbstractBehavior {

	/**
	 * Construct
	 * @param Table $table, array $config
	 */
	public function __construct(Table $table, array $config = []) {
		parent::__construct($table, $config);
		$this->_table = $table;
		$this->termsRelationship = TableRegistry::get('Taxonomy.TermsRelationships', [
			'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable'
		]);
		$this->_processAssociations();
	}

	/**
	 * AfterSave Callback
	 * Add and Sync Terms to the model
	 * @param Event $event, Entity $entity
	 */
	public function afterSave(Event $event, Entity $entity)
	{
		$this->termsRelationship->terms->addAndSync($entity, $this->_table->alias());
	}

}