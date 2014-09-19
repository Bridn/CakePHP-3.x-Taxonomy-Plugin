# CakePHP 3.x Taxonomy Plugin

Simple Taxonomy Plugin.

### 1 - First, use Migration plugin to create tables

	cd to your app path
	src/Console/cake migrations migrate -p Taxonomy

More information about Migration at : http://github.com/cakephp/migrations

### 2 - Load the Taxonomy plugin (app/config/bootstrap.php)

	Plugin::load('Taxonomy', ['bootstrap' => false, 'routes' => true]);

### 3 - Add the behavior to your Table model :

	public function initialize(array $config)
	{
        $this->addBehavior('Taxonomy.Taxonomy');
    }

    The Taxonomy plugin will be automatically associated to your model.

### 4 - Use the TaxonomyHelper to add tags to your content.

Add the helper to your content controller.

	public $helpers = ['Taxonomy.Taxonomy'];

After each query on your content, the plugin will return an object and an array of terms attached to your content.
The array is used to inject terms in the form.

The array path is like **$myContent['terms_format']['tag']** or **$myContent['terms_format']['category']** or whatever you used in your input to name your taxonomy.

the first parameter 'tag' will be used to group your terms. An example of form :

	<?= $this->Taxonomy->input('tag', ['rows' => '2', 'value' => $article['terms_format']['tag']]) ?>

### 5 - Use it !

Separate your words with ";"