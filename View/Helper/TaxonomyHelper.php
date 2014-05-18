<?php namespace Taxonomy\View\Helper;

use App\View\Helper\AppHelper;

class TaxonomyHelper extends AppHelper {

	public $helpers = ['Form'];

	/**
     * Create taxonomy Input
     * @param $type [e.g. Tag, Category...], array $options []
     * @return Form
     */
	public function input($type, $options = [])
	{
		$options = [
			'value' => 'ok; gfhj gjh; fghjghj'
		];
		return $this->Form->input('Taxonomy.'.$type, $options);
	}
}