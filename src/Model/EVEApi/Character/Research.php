<?php

namespace ProjectRena\Model\EVEApi\Character;

use ProjectRena\RenaApp;

/**
 * Class Research.
 */
class Research
{
    /**
     * @var int
     */
    public $accessMask = 65536;

    /**
     * @var
     */
    private $app;

    /**
     * @param \ProjectRena\RenaApp $app
     */
    function __construct(RenaApp $app)
    {
        $this->app = $app;
    }

    /**
     * @param $apiKey
     * @param $vCode
     * @param $characterID
     *
     * @return mixed
     */
    public function getData($apiKey, $vCode, $characterID)
    {
        try {
            $pheal = $this->app->Pheal->Pheal($apiKey, $vCode);
            $pheal->scope = 'Char';
            $result = $pheal->Research(array('characterID' => $characterID))->toArray();

            return $result;
        } catch (\Exception $exception) {
            $this->app->Pheal->handleApiException($apiKey, null, $exception);
        }
    }
}
