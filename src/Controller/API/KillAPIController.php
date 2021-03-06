<?php
namespace ProjectRena\Controller\API;

use ProjectRena\RenaApp;

/**
 * Functions for the API
 */
class KillAPIController
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
        $this->contentType = "application/json";
    }

    private function validateParameters($parameters, $argumentToRemove = null) {
        $validArguments = array(
            "killID",
            "killTime",
            "solarSystemID",
            "regionID",
            "characterID",
            "corporationID",
            "allianceID",
            "factionID",
            "shipTypeID",
            "groupID",
            "vGroupID",
            "weaponTypeID",
            "shipValue",
            "damageDone",
            "totalValue",
            "pointValue",
            "numberInvolved",
            "isVictim",
            "finalBlow",
            "isNPC",
        );

        // Remove an argument from the valid arguments
        if(!empty($argumentToRemove))
            unset($validArguments[$argumentToRemove]);

        $returnArray = array();
        foreach($parameters as $key => $value)
            if(in_array($key, $validArguments))
                $returnArray[$key] = $value;

        return $returnArray;
    }

    private function urlToArrayConverter($parameters) {
        $count = 0;
        $returnArray = array();
        foreach($parameters as $param)
        {
            // We don't want empty parameters
            if(empty($param))
                continue;

            // If the number is even it'll be false, if it's odd it'll be true
            if($count % 2 == false)
                $returnArray[$param] = $parameters[$count + 1];

            // Increment count..
            $count++;
        }

        return $returnArray;
    }

    public function solarSystemKills($solarSystemID, $parameters) {
        // Convert the url to an array of parameters
        $parameters = $this->urlToArrayConverter($parameters);

        // Get the limit, order and offset before we validate the parameters
        $limit = isset($parameters["limit"]) ? (int) $parameters["limit"] > 100 ? 100 : (int) $parameters["limit"] : 100;
        $order = isset($parameters["order"]) ? strtolower($parameters["order"]) == "asc" ? "asc" : "desc" : "desc";
        $offset = isset($parameters["offset"]) ? (int) $parameters["offset"] : null;

        // Validate the parameters
        $parameters = $this->validateParameters($parameters, "solarSystemID");

        // Fetch all the killIDs (and everything else too apparently, should probably cut it down?)
        $killIDs = $this->app->participants->getBySolarSystemID($solarSystemID, $parameters, $limit, 3600, $order, $offset);

        // Fetch all the JSON data for all the killIDs
        $data = array();
        foreach($killIDs as $killID)
            $data[] = json_decode($this->app->killmails->getKill_jsonByKillID($killID["killID"]), true);

        render("", $data, null, $this->contentType);
    }

    public function regionKills($regionID, $parameters) {
        // Convert the url to an array of parameters
        $parameters = $this->urlToArrayConverter($parameters);

        // Get the limit, order and offset before we validate the parameters
        $limit = isset($parameters["limit"]) ? (int) $parameters["limit"] > 100 ? 100 : (int) $parameters["limit"] : 100;
        $order = isset($parameters["order"]) ? strtolower($parameters["order"]) == "asc" ? "asc" : "desc" : "desc";
        $offset = isset($parameters["offset"]) ? (int) $parameters["offset"] : null;

        // Validate the parameters
        $parameters = $this->validateParameters($parameters, "regionID");

        // Fetch all the killIDs (and everything else too apparently, should probably cut it down?)
        $killIDs = $this->app->participants->getByRegionID($regionID, $parameters, $limit, 3600, $order, $offset);

        // Fetch all the JSON data for all the killIDs
        $data = array();
        foreach($killIDs as $killID)
            $data[] = json_decode($this->app->killmails->getKill_jsonByKillID($killID["killID"]), true);

        render("", $data, null, $this->contentType);
    }

    public function characterKills($characterID, $parameters) {
        // Convert the url to an array of parameters
        $parameters = $this->urlToArrayConverter($parameters);

        // Get the limit, order and offset before we validate the parameters
        $limit = isset($parameters["limit"]) ? (int) $parameters["limit"] > 100 ? 100 : (int) $parameters["limit"] : 100;
        $order = isset($parameters["order"]) ? strtolower($parameters["order"]) == "asc" ? "asc" : "desc" : "desc";
        $offset = isset($parameters["offset"]) ? (int) $parameters["offset"] : null;

        // Validate the parameters
        $parameters = $this->validateParameters($parameters, "characterID");

        // Fetch all the killIDs (and everything else too apparently, should probably cut it down?)
        $killIDs = $this->app->participants->getByCharacterID($characterID, $parameters, $limit, 3600, $order, $offset);

        // Fetch all the JSON data for all the killIDs
        $data = array();
        foreach($killIDs as $killID)
            $data[] = json_decode($this->app->killmails->getKill_jsonByKillID($killID["killID"]), true);

        render("", $data, null, $this->contentType);
    }

    public function corporationKills($corporationID, $parameters) {
        // Convert the url to an array of parameters
        $parameters = $this->urlToArrayConverter($parameters);

        // Get the limit, order and offset before we validate the parameters
        $limit = isset($parameters["limit"]) ? (int) $parameters["limit"] > 100 ? 100 : (int) $parameters["limit"] : 100;
        $order = isset($parameters["order"]) ? strtolower($parameters["order"]) == "asc" ? "asc" : "desc" : "desc";
        $offset = isset($parameters["offset"]) ? (int) $parameters["offset"] : null;

        // Validate the parameters
        $parameters = $this->validateParameters($parameters, "corporationID");

        // Fetch all the killIDs (and everything else too apparently, should probably cut it down?)
        $killIDs = $this->app->participants->getByCorporationID($corporationID, $parameters, $limit, 3600, $order, $offset);

        // Fetch all the JSON data for all the killIDs
        $data = array();
        foreach($killIDs as $killID)
            $data[] = json_decode($this->app->killmails->getKill_jsonByKillID($killID["killID"]), true);

        render("", $data, null, $this->contentType);
    }

    public function allianceKills($allianceID, $parameters) {
        // Convert the url to an array of parameters
        $parameters = $this->urlToArrayConverter($parameters);

        // Get the limit, order and offset before we validate the parameters
        $limit = isset($parameters["limit"]) ? (int) $parameters["limit"] > 100 ? 100 : (int) $parameters["limit"] : 100;
        $order = isset($parameters["order"]) ? strtolower($parameters["order"]) == "asc" ? "asc" : "desc" : "desc";
        $offset = isset($parameters["offset"]) ? (int) $parameters["offset"] : null;

        // Validate the parameters
        $parameters = $this->validateParameters($parameters, "allianceID");

        // Fetch all the killIDs (and everything else too apparently, should probably cut it down?)
        $killIDs = $this->app->participants->getByAllianceID($allianceID, $parameters, $limit, 3600, $order, $offset);

        // Fetch all the JSON data for all the killIDs
        $data = array();
        foreach($killIDs as $killID)
            $data[] = json_decode($this->app->killmails->getKill_jsonByKillID($killID["killID"]), true);

        render("", $data, null, $this->contentType);
    }

    public function factionKills($factionID, $parameters) {
        // Convert the url to an array of parameters
        $parameters = $this->urlToArrayConverter($parameters);

        // Get the limit, order and offset before we validate the parameters
        $limit = isset($parameters["limit"]) ? (int) $parameters["limit"] > 100 ? 100 : (int) $parameters["limit"] : 100;
        $order = isset($parameters["order"]) ? strtolower($parameters["order"]) == "asc" ? "asc" : "desc" : "desc";
        $offset = isset($parameters["offset"]) ? (int) $parameters["offset"] : null;

        // Validate the parameters
        $parameters = $this->validateParameters($parameters, "factionID");

        // Fetch all the killIDs (and everything else too apparently, should probably cut it down?)
        $killIDs = $this->app->participants->getByFactionID($factionID, $parameters, $limit, 3600, $order, $offset);

        // Fetch all the JSON data for all the killIDs
        $data = array();
        foreach($killIDs as $killID)
            $data[] = json_decode($this->app->killmails->getKill_jsonByKillID($killID["killID"]), true);

        render("", $data, null, $this->contentType);
    }

    public function shipTypeKills($shipTypeID, $parameters) {
        // Convert the url to an array of parameters
        $parameters = $this->urlToArrayConverter($parameters);

        // Get the limit, order and offset before we validate the parameters
        $limit = isset($parameters["limit"]) ? (int) $parameters["limit"] > 100 ? 100 : (int) $parameters["limit"] : 100;
        $order = isset($parameters["order"]) ? strtolower($parameters["order"]) == "asc" ? "asc" : "desc" : "desc";
        $offset = isset($parameters["offset"]) ? (int) $parameters["offset"] : null;

        // Validate the parameters
        $parameters = $this->validateParameters($parameters, "shipTypeID");

        // Fetch all the killIDs (and everything else too apparently, should probably cut it down?)
        $killIDs = $this->app->participants->getByShipTypeID($shipTypeID, $parameters, $limit, 3600, $order, $offset);

        // Fetch all the JSON data for all the killIDs
        $data = array();
        foreach($killIDs as $killID)
            $data[] = json_decode($this->app->killmails->getKill_jsonByKillID($killID["killID"]), true);

        render("", $data, null, $this->contentType);
    }

    public function groupKills($groupID, $parameters) {
        // Convert the url to an array of parameters
        $parameters = $this->urlToArrayConverter($parameters);

        // Get the limit, order and offset before we validate the parameters
        $limit = isset($parameters["limit"]) ? (int) $parameters["limit"] > 100 ? 100 : (int) $parameters["limit"] : 100;
        $order = isset($parameters["order"]) ? strtolower($parameters["order"]) == "asc" ? "asc" : "desc" : "desc";
        $offset = isset($parameters["offset"]) ? (int) $parameters["offset"] : null;

        // Validate the parameters
        $parameters = $this->validateParameters($parameters, "groupID");

        // Fetch all the killIDs (and everything else too apparently, should probably cut it down?)
        $killIDs = $this->app->participants->getByGroupID($groupID, $parameters, $limit, 3600, $order, $offset);

        // Fetch all the JSON data for all the killIDs
        $data = array();
        foreach($killIDs as $killID)
            $data[] = json_decode($this->app->killmails->getKill_jsonByKillID($killID["killID"]), true);

        render("", $data, null, $this->contentType);
    }

    public function vGroupKills($vGroupID, $parameters) {
        // Convert the url to an array of parameters
        $parameters = $this->urlToArrayConverter($parameters);

        // Get the limit, order and offset before we validate the parameters
        $limit = isset($parameters["limit"]) ? (int) $parameters["limit"] > 100 ? 100 : (int) $parameters["limit"] : 100;
        $order = isset($parameters["order"]) ? strtolower($parameters["order"]) == "asc" ? "asc" : "desc" : "desc";
        $offset = isset($parameters["offset"]) ? (int) $parameters["offset"] : null;

        // Validate the parameters
        $parameters = $this->validateParameters($parameters, "vGroupID");

        // Fetch all the killIDs (and everything else too apparently, should probably cut it down?)
        $killIDs = $this->app->participants->getByVGroupID($vGroupID, $parameters, $limit, 3600, $order, $offset);

        // Fetch all the JSON data for all the killIDs
        $data = array();
        foreach($killIDs as $killID)
            $data[] = json_decode($this->app->killmails->getKill_jsonByKillID($killID["killID"]), true);

        render("", $data, null, $this->contentType);
    }

    public function weaponTypeKills($weaponTypeID, $parameters) {
        // Convert the url to an array of parameters
        $parameters = $this->urlToArrayConverter($parameters);

        // Get the limit, order and offset before we validate the parameters
        $limit = isset($parameters["limit"]) ? (int) $parameters["limit"] > 100 ? 100 : (int) $parameters["limit"] : 100;
        $order = isset($parameters["order"]) ? strtolower($parameters["order"]) == "asc" ? "asc" : "desc" : "desc";
        $offset = isset($parameters["offset"]) ? (int) $parameters["offset"] : null;

        // Validate the parameters
        $parameters = $this->validateParameters($parameters, "weaponTypeID");

        // Fetch all the killIDs (and everything else too apparently, should probably cut it down?)
        $killIDs = $this->app->participants->getByWeaponTypeID($weaponTypeID, $parameters, $limit, 3600, $order, $offset);

        // Fetch all the JSON data for all the killIDs
        $data = array();
        foreach($killIDs as $killID)
            $data[] = json_decode($this->app->killmails->getKill_jsonByKillID($killID["killID"]), true);

        render("", $data, null, $this->contentType);
    }

}