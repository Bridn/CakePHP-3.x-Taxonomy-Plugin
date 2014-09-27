<?php namespace Taxonomy\View\Helper;

use App\View\Helper\AppHelper;
use Cake\Utility\Hash;

class TaxonomyHelper extends AppHelper {

	public $helpers = ['Form'];

	/**
     * Create taxonomy Input
     * @param $type [e.g. Tag, Category...], $data (entity), array $options []
     * @return Form
     */
	public function input($type = null, $data = null,  array $options = [])
	{
		if (isset($data['terms_format'][$type]) && ! is_null($data['terms_format'][$type]))
		{
			if(is_array($data['terms_format'][$type]))
			{
				$options['value'] = implode(';', Hash::extract($data['terms_format'][$type], '{n}.title'));
			} else {
				$options['value'] = $data['terms_format'][$type];
			}
		}

		return $this->Form->input('Taxonomy.'.$type, $options);
	}
}