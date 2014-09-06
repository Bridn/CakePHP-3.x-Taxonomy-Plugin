<?php
namespace Taxonomy\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TaxonomyArticleFixture extends TestFixture {

/**
 * fields property
 *
 * @var array
 */
  public $fields = array(
    'id' => ['type' => 'integer'],
    'title' => ['type' => 'string', 'null' => true],
    'body' => 'text',
    '_constraints' => [
      'primary' => ['type' => 'primary', 'columns' => ['id']]
    ]
  );

/**
 * records property
 *
 * @var array
 */
  public $records = array(
    array('id' => 1, 'title' => 'First Article', 'body' => 'First Article Body'),
    array('id' => 2, 'title' => 'Second Article', 'body' => 'Second Article Body'),
    array('id' => 3, 'title' => 'Third Article', 'body' => 'Third Article Body')
  );

}