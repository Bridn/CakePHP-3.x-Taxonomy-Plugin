<?php
namespace Taxonomy\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TermFixture extends TestFixture {

/**
 * fields property
 *
 * @var array
 */
  public $fields = array(
    'id' => ['type' => 'integer'],
    'name' => ['type' => 'string', 'null' => false],
    '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
  );

/**
 * records property
 *
 * @var array
 */
  public $records = array(
    array('name' => 'tag1'),
    array('name' => 'tag2'),
    array('name' => 'tag3')
  );
}