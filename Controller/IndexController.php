<?php

namespace Apps\ElasticSearch\Controller;

use Phpfox;
use Phpfox_Pager;
use Apps\ElasticSearch\Service\ElasticSearchService as esService;

// Index controller must be child of \Phpfox_Component class.
//
class IndexController extends \Phpfox_Component
{
    public function process()
    {
        $test = $this->request()->get('req2');
        $oService = new esService();
//        $oService->fetch('user');
        var_dump($oService->query($test, 'user'));
    }
}