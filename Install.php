<?php
namespace Apps\ElasticSearch;

use Core\App;

/**
 * Class Install
 * @author  Neil
 * @version 4.5.0
 * @package Apps\ElasticSearch
 */
class Install extends App\App
{
    private $_app_phrases = [

    ];

    protected function setId()
    {
        $this->id = 'ElasticSearch';
    }

    protected function setAlias()
    {
        $this->alias = 'es';
    }

    protected function setName()
    {
        $this->name = 'ElasticSearch';
    }

    protected function setVersion()
    {
        $this->version = '4.1.0';
    }

    protected function setSupportVersion()
    {
        $this->start_support_version = '4.5.0';
        $this->end_support_version = '4.5.0';
    }

    protected function setSettings()
    {
    }

    protected function setUserGroupSettings()
    {
    }

    protected function setComponent()
    {
    }

    protected function setComponentBlock()
    {
    }

    protected function setPhrase()
    {
        $this->phrase = $this->_app_phrases;
    }

    protected function setOthers()
    {
    }
}