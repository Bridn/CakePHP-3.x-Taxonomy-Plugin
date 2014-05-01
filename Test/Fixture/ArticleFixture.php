<?php
namespace Taxonomy\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ArticleFixture extends TestFixture {

/**
 * fields property
 *
 * @var array
 */
  public $fields = array(
    'id' => ['type' => 'integer'],
    'author_id' => ['type' => 'integer', 'null' => true],
    'title' => ['type' => 'string', 'null' => true],
    'body' => 'text',
    'published' => ['type' => 'string', 'length' => 1, 'default' => 'N'],
    '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
  );

/**
 * records property
 *
 * @var array
 */
  public $records = array(
    array('author_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body', 'published' => 'Y'),
    array('author_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body', 'published' => 'Y'),
    array('author_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body', 'published' => 'Y')
  );

}