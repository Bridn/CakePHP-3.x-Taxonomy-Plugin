<?php namespace Taxonomy\View\Helper;

use App\View\Helper\AppHelper;
use Cake\Utility\Hash;

class TaxonomyHelper extends AppHelper {

	public $helpers = ['Form'];

	/**
     * Create taxonomy Input
     * @param $type [e.g. Tag, Category...], array $values ['entity' => array(), 'list' => array()], array $options []
     * @return Form
     */
	public function input($type = null, array $values = [], array $options = [])
	{

			// Select form type
			if(isset($options['type']) && $options['type'] === 'select')
			{

				$options['empty'] = true;

				foreach($values['list'] as $key => $item)
				{
					$options['options'][$item->title] = $item->title;
				}

				if( isset($values['entity']['terms_format'][$type]) && is_array($values['entity']['terms_format'][$type]) && ! empty($values['entity']['terms_format'][$type]))
				{
					$options['value'] = $select['saved'] = Hash::combine(Hash::extract($values['entity']['terms_format'][$type], '{n}.title'), '{n}', '{n}');
				}
				return $this->Form->input('Taxonomy.'.$type, $options);

			}

			// Input form type
			if(isset($values['entity']['terms_format'][$type]))
				{
				if(is_array($values['entity']['terms_format'][$type]))
				{
					$options['value'] = implode(';', Hash::extract($values['entity']['terms_format'][$type], '{n}.title'));
				} else {
					$options['value'] = $values['entity']['terms_format'][$type];
				}
			}

		return $this->Form->input('Taxonomy.'.$type, $options);

	}

}