<?php namespace Taxonomy\View\Helper;
/**
 * Taxonomy View Helper
 *
 * PHP version 5.3
 * CakePHP 2.2+
 *
 * @package  Taxonomy.Taxonomy.View.Helper
 * @version  1.0
 * @author   Bridn - (based on Grafikart beta plugin https://github.com/Grafikart/CakePHP-Taxonomy)
 * @date 	 August 2012
 */

use App\View\Helper\AppHelper;

class TaxonomyHelper extends AppHelper {

	public $helpers = ['Form'];

	public function input($type, $options = array())
	{
		return $this->Form->input('Taxonomy.'.$type, $options);
	}
}