<?php

use Cake\Core\Configure;

Configure::write('Taxonomy.tag._lockedCreate', false);
Configure::write('Taxonomy.tag._lockedAutoClean', false);

Configure::write('Taxonomy.category._lockedAutoClean', true);
Configure::write('Taxonomy.category._lockedCreate', true);