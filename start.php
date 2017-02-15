<?php

namespace Apps\ElasticSearch;

require 'vendor' . PHPFOX_DS . 'autoload.php';

// Load phpFox module service instance, this is core of phpFox service,
// module service contains your app configuration.
$module =\Phpfox_Module::instance();

// Instead of \Apps\FirstApp every where. Let register an alias **first_app** that map to our app.
$module->addAliasNames('es', 'ElasticSearch');

// Register your controller here
$module->addComponentNames('controller', [
    'es.index' => Controller\IndexController::class,
]);

// Register template directory
$module->addTemplateDirs([
    'es' => PHPFOX_DIR_SITE_APPS . 'ElasticSearch' . PHPFOX_DS . 'views',
]);

// Routing
group('/es',function() {
    route('/', 'es.index');
    route('/:query/*', 'es.index')
        ->where(['query' => '\w+']);
});

//(new Install())->processInstall();