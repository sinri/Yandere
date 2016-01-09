<?php
require_once(__DIR__."/../toolkit/init.php");

/**
* 
*/
class KeywordModel
{
	
	function __construct()
	{
		# code...
	}

	public static function getKeywordId($keyword){
		global $db;

		$keyword_quoted=$db->quote($keyword);

		$sql="SELECT `keyword_id` FROM `keyword` WHERE `keyword`={$keyword_quoted}";
		$keyword_id=$db->getOne($sql);

		if(empty($keyword_id)){
			$keyword_id = KeywordModel::createKeywordItem($keyword);
		}

		return $keyword_id;
	}

	public static function createKeywordItem($keyword){
		global $db;

		$keyword_quoted=$db->quote($keyword);

		$sql="INSERT INTO `keyword` (`keyword_id`,`keyword`) VALUES (NULL,{$keyword_quoted})";
		$keyword_id=$db->insert($sql);
		return $keyword_id;
	}

	public static function queryKeywordWithPrefix($prefix='',$limit=10){
		global $db;

		$keyword_quoted=$db->quote($prefix."%");

		$sql="SELECT `keyword` as itemName FROM `keyword` 
			WHERE `keyword` like {$keyword_quoted}
			ORDER BY keyword
			LIMIT ".intval($limit);
		$keyword_list=$db->getAll($sql);
		QLog::log("KeywordModel::queryKeywordWithPrefix($prefix,$limit)->sql: ".$sql." -> list: ".json_encode($keyword_list));
		return $keyword_list;
	}
}
