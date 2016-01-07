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
}
