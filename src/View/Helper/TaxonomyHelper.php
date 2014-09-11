<?php namespace Taxonomy\View\Helper;

use App\View\Helper\AppHelper;
use Cake\Utility\Hash;

class TaxonomyHelper extends AppHelper {

	public $helpers = ['Form'];

	/**
     * Create taxonomy Input
     * @param $type [e.g. Tag, Category...], array $options []
     * @return Form
     */
	public function input($type, array $options = [])
	{
		if (is_array($options['value']))
		{
			$options['value'] = implode(';', Hash::extract($options['value'], '{n}.title'));
		}

		return $this->Form->input('Taxonomy.'.$type, $options);
	}
}