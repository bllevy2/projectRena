<?php


namespace ProjectRena\Model\EVEApi\Corporation;

use ProjectRena\Lib\PhealLoader;

/**
 * Class CustomsOffices
 *
 * @package ProjectRena\Model\EVEApi\Corporation
 */
class CustomsOffices {
	/**
	 * @var int
	 */
	public static $accessMask = 2;

	/**
	 * @param $apiKey
	 * @param $vCode
	 * @param $characterID
	 *
	 * @return mixed
	 */
	public static function getData($apiKey, $vCode, $characterID)
	{
		$pheal = PhealLoader::loadPheal($apiKey, $vCode);
		$pheal->scope = "Corp";
		$result = $pheal->CustomsOffices(array("characterID" => $characterID))->toArray();

		return $result;
	}
}