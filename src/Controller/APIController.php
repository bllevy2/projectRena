<?php
namespace ProjectRena\Controller;

use ProjectRena\RenaApp;

/**
 * Functions for the API
 */
class APIController
{

    /**
     * The Slim Application
     */
    private $app;

    /**
     * The Cache
     */
    private $cache;

    /**
     * The baseConfig (config/config.php)
     */
    private $config;

    /**
     * cURL interface (getData / setData)
     */
    private $curl;

    /**
     * The Database
     */
    private $db;

    /**
     * The logger, outputs to logs/app.log
     */
    private $log;

    /**
     * StatsD for tracking stats
     */
    private $statsd;

    /**
     * @var string
     */
    private $contentType;
    /**
     * @param RenaApp $app
     */
    public function __construct(RenaApp $app)
    {
        $this->app = $app;
        $this->db = $app->Db;
        $this->config = $app->baseConfig;
        $this->cache = $app->Cache;
        $this->curl = $app->cURL;
        $this->statsd = $app->StatsD;
        $this->log = $app->Logging;

        // Only accept json and xml as valid outputs otherwise default to json
        if(in_array($app->request->getContentType(), array("application/json", "application/xml")))
            $this->contentType = $app->request->getContentType();
        else
            $this->contentType = "application/json";
    }

    /**
     * @param $page
     */
    public function main($page)
    {
        render("", array($page), null, $this->contentType);
    }
}
