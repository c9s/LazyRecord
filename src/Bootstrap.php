<?php

namespace Maghead;

use Maghead\TableBuilder\BaseBuilder;
use Maghead\Schema\SchemaCollection;
use Maghead\Schema\SchemaFinder;
use Maghead\Manager\DataSourceManager;
use Maghead\Runtime\BaseModel;
use Maghead\Runtime\BaseCollection;
use CLIFramework\Logger;

use ConfigKit\ConfigCompiler;
use PDOException;
use Exception;
use ArrayAccess;

class Bootstrap
{
    /**
     * Run bootstrap script if it's defined in the config.
     * This is used for the command-line app.
     */
    protected static function loadBootstrap($config)
    {
        if (isset($config['bootstrap'])) {
            foreach ((array) $config['bootstrap'] as $bootstrap) {
                require_once $bootstrap;
            }
        }
    }

    /**
     * load external schema loader.
     */
    protected static function loadExternalSchemaLoader($config)
    {
        if (isset($config['schema']['loader'])) {
            require_once $config['schema']['loader'];

            return true;
        }

        return false;
    }

    protected static function loadSchemaFromFinder($config)
    {
        // Load default schema loader
        $paths = $config->getSchemaPaths();
        if (!empty($paths)) {
            $finder = new SchemaFinder($paths);
            $finder->find();
        }
    }

    protected static function loadSchemaLoader($config)
    {
        if (!self::loadExternalSchemaLoader($config)) {
            self::loadSchemaFromFinder($config);
        }
    }

    public static function setupDataSources(Config $config, DataSourceManager $dataSourceManager)
    {
        foreach ($config->getDataSources() as $nodeId => $dsConfig) {
            $dataSourceManager->addNode($nodeId, $dsConfig);
        }
        if ($nodeId = $config->getMasterDataSourceId()) {
            $dataSourceManager->setMasterNodeId($nodeId);
        }
    }


    public static function setupGlobalVars(Config $config, DataSourceManager $dataSourceManager)
    {
        BaseModel::$dataSourceManager = $dataSourceManager;
        BaseCollection::$dataSourceManager = $dataSourceManager;
    }

    public static function setup(Config $config)
    {
        $dataSourceManager = DataSourceManager::getInstance();

        // TODO: this could be moved to Environment class.
        BaseModel::$yamlExtension = extension_loaded('yaml');

        self::setupDataSources($config, $dataSourceManager);
        self::setupGlobalVars($config, $dataSourceManager);
    }

    /**
     * Setup environment for command-line application
     * This could be an override method from Bootstrap class.
     */
    public static function setupForCLI(Config $config)
    {
        self::setup($config);
        self::loadBootstrap($config);
        self::loadSchemaLoader($config);
    }
}
