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
}