# CakePHP 3.x Taxonomy Plugin

Simple Taxonomy Plugin, you will be able to add tags or categories etc. to your entity.
This plugin doesn't implement nested data. Parents and order by left and right are not implemented.

### Requirements

- CakePHP 3.x
- CakePHP Migrations Plugin (Phinx >= v0.3.8)

### 1 - First, use Migrations plugin to create tables

	cd to your app path
	src/Console/cake migrations migrate -p Taxonomy

More information about Migrations at : http://github.com/cakephp/migrations

### 2 - Load the Taxonomy plugin (app/config/bootstrap.php)

	Plugin::load('Taxonomy', ['bootstrap' => false, 'routes' => true]);

### 3 - Add behaviors to your Table model :

	public function initialize(array $config)
	{
        $this->addBehavior('Taxonomy.TaxonomySync');
        $this->addBehavior('Taxonomy.TaxonomyFinder');
    }

- TaxonomySync Behavior create/update/clean your terms.
- TaxonomyFinder Behavior manage all kind of queries. If you don't want to overload your queries, dettach it on the fly.

The Taxonomy plugin use polymorph associations, it is automatically associated to your model.

### 4 - Use the TaxonomyHelper to add tags to your content.

Add the helper to your content controller.

	public $helpers = ['Taxonomy.Taxonomy'];

After each query on your content, the plugin will return an object and an array of terms attached to your content.
The first parameter 'tag' will be used to group your terms in DB (don't translate this), the second must include your entity object, the third is an array of cakephp form params (label, row etc.) e.g. for a french form :

	<?= $this->Taxonomy->input('tag', $article, ['label' => __d('Taxonomy', 'Mots clés')]) ?>
	<?= $this->Taxonomy->input('category', $article, ['label' => __d('Taxonomy', 'Categorie')]) ?>

If you need a select box for categories e.g.

	<?= $this->Taxonomy->input('category', ['entity' => $article, 'list' => $categories], ['type' => 'select', 'label' => __d('Taxonomy', 'Categorie'), 'multiple' => true]) ?>

- "list" parameter must contain all your terms (by type - e.g. category), here we previously found all categories in our controller and set it to the view.

	articles controller :

	...
	$categories = $this->{$this->name}->termsRelationships->terms->find()
	->where(['type' => 'category'])
	->all();
	$this->set(compact('article', 'categories'));

### 5 - Configuration

You can disable auto cleaning terms in Taxonomy/bootstrap.php (it will not delete your terms automatically if they are not used, you have to manage it in an admin panel)
This is essential for categories.

	Configure::write('Taxonomy.category._lockedAutoClean', true);

*Security issue :*
You can also lock the create action from client side by setting this (if you want to manage your category in admin panel) :

	Configure::write('Taxonomy.category._lockedCreate', true);

Note : Change 'category' by your term type

### 6 - Queries in controller

To return all terms by table and type (articles controller) :

	$articles = $this->{$this->name}->find('all');
	$categories = $this->{$this->name}->listAllTermsByType('category');
    $this->set(compact('articles', 'categories'));

Or

To return all relationships used by a table for a given term id (articles controller):

	$categories = $this->{$this->name}->listAllByTableAndByTerm($id);
	$this->set(compact('categories'));

### 7 - Note

Separate your words with ";"