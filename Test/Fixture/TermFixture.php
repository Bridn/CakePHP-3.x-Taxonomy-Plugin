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
    'title' => ['type' => 'string', 'null' => false],
    'type' => ['type' => 'string', 'null' => false],
    '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
  );

/**
 * records property
 *
 * @var array
 */
  public $records = array(
    array('id' => 1, 'title' => 'cake', 'type' => 'tag'),
    array('id' => 2, 'title' => 'food', 'type' => 'tag'),
    array('id' => 3, 'title' => 'apple', 'type' => 'tag')
  );
}