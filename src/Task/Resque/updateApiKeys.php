<?php
namespace ProjectRena\Task\Resque;

/**
 * Updates the APIKeys and populates the apiKeyCharacters table
 */
class updateApiKeys
{
    protected $app;
    /**
     * Performs the task, can access all $this->crap setup in setUp)
     */
    public function perform()
    {
        $keyID = $this->args["keyID"];
        $vCode = $this->args["vCode"];
        $data = $this->app->EVEAccountAPIKeyInfo->getData($keyID, $vCode);
        if(isset($data["errorCode"]) && isset($data["errorMessage"]))
        {
            // Update the key to being faulty
            $this->app->Db->execute("UPDATE apiKeys SET errorCode = :errorCode, lastValidation = :lastValidation WHERE keyID = :keyID and vCode = :vCode", array(":errorCode" => $data["errorCode"], ":lastValidation" => date("Y-m-d H:i:s", time() + 86400), ":keyID" => $keyID, ":vCode" => $vCode));
            exit(); // we're done, just quit
        }

        $keyType = $data["result"]["key"]["type"]; // Corporation, Account (All chars), Character
        $accessMask = $data["result"]["key"]["accessMask"];
        $expires = $data["result"]["key"]["expires"];

        // Update the api key info in the table
        $this->app->Db->execute("UPDATE apiKeys SET accessMask = :accessMask, expires = :expires, lastValidation = :lastValidation WHERE keyID = :keyID AND vCode = :vCode", array(":accessMask" => $accessMask, ":expires" => $expires, ":lastValidation" => date("Y-m-d H:i:s"), ":keyID" => $keyID, ":vCode" => $vCode));

        // Insert the key data to the apiKeyCharacters table
        foreach($data["result"]["key"]["characters"] as $c)
        {
            $this->app->Db->execute("INSERT INTO apiKeyCharacters (keyID, characterID, corporationID, isDirector) VALUES (:keyID, :characterID, :corporationID, :isDirector)", array(
                    ":keyID" => $keyID,
                    ":characterID" => $c["characterID"],
                    ":corporationID" => $c["corporationID"],
                    ":isDirector" => $keyType == "Corporation" ? true : false,
                )
            );
        }

    }

    /**
     * Sets up the task (Setup $this->crap and such here)
     */
    public function setUp()
    {
        $this->app = \ProjectRena\RenaApp::getInstance();
    }

    /**
     * Tears the task down, unset $this->crap and such
     */
    public function tearDown()
    {
        $this->app = null;
    }
}