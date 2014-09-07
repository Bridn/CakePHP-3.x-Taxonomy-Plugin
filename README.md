# CakePHP 3.x (beta 1) Taxonomy Plugin - BETA

Simple Taxonomy Plugin.
*version 0.1*

### 1 - First import the SQL file : config/taxonomy_plugin.sql in your database.

### 2 - Load the plugin (app/config/bootstrap.php)

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