# CakePHP 3.x Taxonomy Plugin - BETA

Simple Taxonomy Plugin.
version 0.1

1 - Import first SQL file config/taxonomy_plugin.sql in your database.

2 - Add the behavior to your Table model :

	public function initialize(array $config)
	{
        $this->addBehavior('Taxonomy.Taxonomy');
    }

    The Taxonomy plugin will be automatically associated to your model.

3 - Use the TaxonomyHelper to add tags to your content. eg.

<?= $this->Taxonomy->input('tag', ['rows' => '2', 'value' => $article['terms_format']['tag']]) ?>