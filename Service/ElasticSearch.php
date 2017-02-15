<?php

namespace Apps\ElasticSearch\Service;

use Elasticsearch;
use Phpfox;

class ElasticSearchService extends \Phpfox_Service
{
    private $_elasticClient = null;

    public function __construct()
    {
        $this->_elasticClient = Elasticsearch\ClientBuilder::create()->build();
    }

    public function mapping($sModule = null)
    {
        //clean all mappings
        $this->_elasticClient->indices()->delete([
            'index' => '_all',
        ]);

        //fetch module mappings
        if (!empty($sModule) && Phpfox::hasCallback($sModule, 'esParams')) {
            $aParams = Phpfox::callback($sModule . '.esParams');
        }

        //create mapping
        $this->_elasticClient->indices()->create($aParams);
    }

    public function fetch($sModule)
    {
        if (!empty($sModule) && Phpfox::hasCallback($sModule, 'esFetch')) {
            $aItems = Phpfox::callback($sModule . '.esFetch');
        }

        foreach ($aItems as $aItem) {
            $params['body'][] = array(
                'index' => array(
                    '_index' => 'items',
                    '_type' => 'item',
                ),
            );
            $params['body'][] = $aItem;
        }

        $this->mapping($sModule);
        $this->_elasticClient->bulk($params);
        return true;
    }

    public function add($sModule, $iItemId) {
        if (!empty($sModule) && Phpfox::hasCallback($sModule, 'esFetch')) {
            $aItems = Phpfox::callback($sModule . '.esFetch', $iItemId);
        }

        foreach ($aItems as $aItem) {
            $params['body'][] = array(
                'index' => array(
                    '_index' => 'items',
                    '_type' => 'item',
                ),
            );
            $params['body'][] = $aItem;
        }

        $this->_elasticClient->bulk($params);
    }

    public function update($sModule, $iItemId) {
        if (!empty($sModule) && Phpfox::hasCallback($sModule, 'esFetch')) {
            $aItems = Phpfox::callback($sModule . '.esFetch', $iItemId);
        }

        if (count($aItems) > 0) { //found
            $params = [
                'index' => 'items',
                'type' => 'item',
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => [
                                'match' => [
                                    'item_id' => $aItems[0]['item_id'],
                                ],
                                'match' => [
                                    'item_type_id' => $aItems[0]['item_type_id'],
                                ],
                                'match' => [
                                    'item_title' => $aItems[0]['item_title'],
                                ],
                                'match' => [
                                    'item_user_id' => $aItems[0]['item_user_id'],
                                ],
                            ]
                        ,]
                    ,]
                ,]
            ,];

            $aResult = $this->_elasticClient->search($params)['hits']['hits'][0];
        }

        unset($params); //clean

        $params = [
            'index' => 'items',
            'type' => 'item',
            'id' => $aResult['_id'],
            'body' => $aItems[0]
        ];
var_dump(json_encode($params)); die;
        $this->_elasticClient->update($params);
    }

    public function query($sQuery, $sModule = null)
    {
        $client = $this->_elasticClient;
        $result = array();

        $i = 0;

        if (!empty($sModule) && Phpfox::hasCallback($sModule, 'esQuery')) {
            $aQuery = Phpfox::callback($sModule . '.esQuery', $this->preParse()->clean($sQuery));
        }

        $query = $client->search($aQuery);

        $hits = sizeof($query['hits']['hits']);

        $hit = $query['hits']['hits'];

        $result['searchfound'] = $hits;

        while ($i < $hits) {
            $result['result'][$i] = $hit[$i]['_source'];
            $i++;
        }

        return $result;
    }
}