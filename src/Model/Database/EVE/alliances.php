<?php
namespace ProjectRena\Model\Database\EVE;

use ProjectRena\RenaApp;

/**
 * Class invTypes
 *
 * @package ProjectRena\Model\EVE
 */
class alliances
{
    /**
     * @var RenaApp
     */
    private $app;
    /**
     * @var \ProjectRena\Lib\Db
     */
    private $db;

    /**
     * @param RenaApp $app
     */
    function __construct(RenaApp $app)
    {
        $this->app = $app;
        $this->db = $this->app->Db;
    }

    /**
     * @param $allianceID
     *
     * @return array
     */
    public function getAllByID($allianceID)
    {
        return $this->db->queryRow("SELECT * FROM alliances WHERE allianceID = :id", array(":id" => $allianceID), 0);
    }

    /**
     * @param $allianceName
     *
     * @return array
     */
    public function getAllByName($allianceName)
    {
        return $this->db->queryRow("SELECT * FROM alliances WHERE allianceName = :name", array(":name" => $allianceName), 3600);
    }

    /**
     * @param $allianceID
     *
     * @return array
     */
    public function getAllByAllianceID($allianceID)
    {
        return $this->db->queryRow("SELECT * FROM alliances WHERE allianceID = :id", array(":id" => $allianceID), 3600);
    }

    /**
     * @param $allianceID
     *
     * @return string
     */
    public function getInformationByID($allianceID)
    {
        return $this->db->queryField("SELECT history FROM alliances WHERE allianceID = :id", "history", array(":id" => $allianceID), 3600);
    }

    /**
     * @param $allianceName
     *
     * @return string
     */
    public function getInformationByName($allianceName)
    {
        return $this->db->queryField("SELECT history FROM alliances WHERE allianceName = :name", "history", array(":name" => $allianceName), 3600);
    }

    /**
     * @param $allianceID
     *
     * @return string
     */
    public function getExecutorCorporationIDByID($allianceID)
    {
        return $this->db->queryField("SELECT executorCorporationID FROM alliances WHERE allianceID = :id", "executorCorporationID", array(":id" => $allianceID), 3600);
    }

    /**
     * @param $allianceName
     *
     * @return string
     */
    public function getExecutorCorporationIDByName($allianceName)
    {
        return $this->db->queryField("SELECT executorCorporationID FROM alliances WHERE allianceName = :name", "executorCorporationID", array(":name" => $allianceName), 3600);
    }

    /**
     * @param $allianceID
     *
     * @return string
     */
    public function getAllianceTickerByID($allianceID)
    {
        return $this->db->queryField("SELECT allianceTicker FROM alliances WHERE allianceID = :id", "allianceTicker", array(":id" => $allianceID), 3600);
    }

    /**
     * @param $allianceName
     *
     * @return string
     */
    public function getAllianceTickerByName($allianceName)
    {
        return $this->db->queryField("SELECT allianceTicker FROM alliances WHERE allianceName = :name", "allianceTicker", array(":name" => $allianceName), 3600);
    }

    /**
     * @param $allianceID
     *
     * @return string
     */
    public function getMemberCountByID($allianceID)
    {
        return $this->db->queryField("SELECT memberCount FROM alliances WHERE allianceID = :id", "memberCount", array(":id" => $allianceID), 3600);
    }

    /**
     * @param $allianceName
     *
     * @return string
     */
    public function getMemberCountByName($allianceName)
    {
        return $this->db->queryField("SELECT memberCount FROM alliances WHERE allianceName = :name", "memberCount", array(":name" => $allianceName), 3600);
    }

    public function getMembersByID($allianceID)
    {
        return $this->db->query("SELECT characterID, characterName FROM characters WHERE allianceID = :allianceID", array(":allianceID" => $allianceID), 3600);
    }

    public function getMembersByName($allianceName)
    {
        return $this->db->query("SELECT characterID, characterName FROM characters WHERE allianceID = (SELECT allianceID FROM alliances WHERE allianceName = :allianceName)", array(":allianceName" => $allianceName), 3600);
    }

    /**
     * @param null $allianceID
     * @param null $allianceName
     * @param null $allianceTicker
     * @param null $memberCount
     * @param null $executorCorporationID
     * @param null $information
     */
    public function updateAllianceDetails($allianceID, $allianceName = null, $allianceTicker = null, $memberCount = null, $executorCorporationID = null, $information = null)
    {
        $this->db->execute("INSERT INTO alliances (allianceID, allianceName, allianceTicker, memberCount, executorCorporationID, information) VALUES (:allianceID, :allianceName, :allianceTicker, :memberCount, :executorCorporationID, :information) ON DUPLICATE KEY UPDATE allianceID = :allianceID, allianceName = :allianceName, allianceTicker = :allianceTicker, memberCount = :memberCount, executorCorporationID = :executorCorporationID, information = :information", array(
            ":allianceID" => $allianceID,
            ":allianceName" => $allianceName,
            ":allianceTicker" => $allianceTicker,
            ":memberCount" => $memberCount,
            ":executorCorporationID" => $executorCorporationID,
            ":information" => $information,
        ));
    }

    /**
     * @param $allianceID
     * @param string $lastUpdated
     */
    public function setLastUpdated($allianceID, $lastUpdated)
    {
        if ($lastUpdated)
            $this->db->execute("UPDATE alliances SET lastUpdated = :lastUpdated WHERE allianceID = :allianceID", array(":lastUpdated" => $lastUpdated, ":allianceID" => $allianceID));
    }

    /**
     * @param $allianceID
     * @param $executorCorporationID
     *
     */
    public function setExecutorCorporationID($allianceID, $executorCorporationID)
    {
        if ($executorCorporationID)
            $this->db->execute("UPDATE alliances SET executorCorporationID = :executorCorporationID WHERE allianceID = :allianceID", array(":executorCorporationID" => $executorCorporationID, ":allianceID" => $allianceID));
    }

    /**
     * @param $allianceID
     * @param $memberCount
     */
    public function setMemberCount($allianceID, $memberCount)
    {
        if ($memberCount)
            $this->db->execute("UPDATE alliances SET memberCount = :memberCount WHERE allianceID = :allianceID", array(":memberCount" => $memberCount, ":allianceID" => $allianceID));
    }
}