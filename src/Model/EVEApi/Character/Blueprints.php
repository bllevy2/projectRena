<?php


namespace ProjectRena\Model\EVEApi\Character;


use ProjectRena\Lib\PhealLoader;

/**
 * Class Blueprints
 *
 * @package ProjectRena\Model\EVEApi\Character
 */
class Blueprints {
	/**
	 * @var int
	 */
	public static $accessMask = 2;

	/**
	 * @param $apiKey
	 * @param $vCode
	 *
	 * @param $characterID
	 *
	 * @return mixed
	 */
	public static function getData($apiKey, $vCode, $characterID)
	{
		$pheal = PhealLoader::loadPheal($apiKey, $vCode);
		$pheal->scope = "Char";
		$result = $pheal->Blueprints(array("characterID" => $characterID))->toArray();

		return $result;
	}

}