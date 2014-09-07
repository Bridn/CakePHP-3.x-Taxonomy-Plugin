# CakePHP 3.x Taxonomy Plugin - BETA

Simple Taxonomy Plugin.
version 0.1

1 - First import the SQL file : config/taxonomy_plugin.sql in your database.

2 - Add the behavior to your Table model :

	public function initialize(array $config)
	{
        $this->addBehavior('Taxonomy.Taxonomy');
    }

    The Taxonomy plugin will be automatically associated to your model.

3 - Use the TaxonomyHelper to add tags to your content.

After each query on your content, the plugin will return an array of terms attached to your content.
eg. $myContent['terms_format']['tag'] or $myContent['terms_format']['category'] or wathever you used in your input to name your taxonomy.

eg. the first parameter 'tag' will be used to group your terms.
<?= $this->Taxonomy->input('tag', ['rows' => '2', 'value' => $article['terms_format']['tag']]) ?>