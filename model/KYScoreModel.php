<?php
require_once(__DIR__."/../toolkit/init.php");

/**
* 
*/
class KYStoreModel
{
	
	function __construct()
	{
		# code...
	}

	public static function updateKYScore($keyword_id,$yamai_id,$score){
		global $db;

		QLog::log("KYStoreModel::updateKYScore($keyword_id,$yamai_id,$score)");

		$keyword_id=$db->quote($keyword_id,PDO::PARAM_INT);
		$yamai_id=$db->quote($yamai_id,PDO::PARAM_INT);
		$score=$db->quote($score,PDO::PARAM_INT);

		if($keyword_id * $yamai_id * $score == 0){
			return false;
		}

		$sql="INSERT INTO yamai_keyword (yamai_id,keyword_id,relation_score) 
			VALUES ({$yamai_id},{$keyword_id},{$score})
  			ON DUPLICATE KEY UPDATE relation_score={$score}
  		";
  		$afx=$db->exec($sql);

  		QLog::log("KYStoreModel::UPDATE: [".$sql."] => $afx");

  		return $afx;
	}

	public static function searchKeywords($keywords=array()){
		global $db;

		if(empty($keywords)){
			return false;
		}

		$keywords_in_sql="'".implode("','", $keywords)."'";
		$sql="SELECT keyword_id FROM keyword WHERE keyword in ({$keywords_in_sql})";
		$keyword_id_list=$db->getColumn($sql);
		if(empty($keyword_id_list) || count($keyword_id_list)!=count($keywords)){
			return false;
		}

		$yamai_list=null;
		foreach ($keyword_id_list as $keyword_id) {
			$sql="SELECT yamai_id FROM yamai_keyword yk 
				WHERE yk.keyword_id={$keyword_id} and yk.relation_score>0
			";
			if($yamai_list!==null){
				$sql.=" and yk.yamai_id in (".implode(",", $yamai_list).") ";
			}

			$yamai_list_once=$db->getColumn($sql);
			if($yamai_list===null){
				$yamai_list=$yamai_list_once;
			}else{
				$yamai_list=array_intersect($yamai_list, $yamai_list_once);
			}

			if(empty($yamai_list)){
				return false;
			}
		}

		$sql="SELECT y.*,group_concat('',k.keyword) keyword_list FROM yamai y
			LEFT JOIN yamai_keyword yk ON y.yamai_id=yk.yamai_id
			LEFT JOIN keyword k ON k.keyword_id=yk.keyword_id
			WHERE y.yamai_id in (".implode(',', $yamai_list).") 
		";
		$list=$db->getAll($sql);
		return $list;
	}
}