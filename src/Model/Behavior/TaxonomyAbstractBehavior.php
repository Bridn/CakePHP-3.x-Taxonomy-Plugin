<?php
namespace Taxonomy\Model\Behavior;

use Cake\ORM\Behavior;

abstract class TaxonomyAbstractBehavior extends Behavior {

	/**
	 * Process Associations
	 */
	protected function _processAssociations()
	{
		$this->_table->hasMany('TermsRelationships', [
			'className' => 'Taxonomy\Model\Table\TermsRelationshipsTable',
			'foreignKey' => 'reference_id',
			'conditions' => 'TermsRelationships.reference_table = "'.$this->_table->alias().'"',
			'dependent' => true
		]);

		$this->termsRelationship->belongsTo($this->_table->alias(), [
			'className' => $this->_table->alias(),
			'foreignKey' => 'reference_id',
			'conditions' => 'TermsRelationships.reference_table = "'.$this->_table->alias().'"',
		]);
	}

}
