<?php
class SqlHelper{
	
	/**
	 * createSqlWhere
	 *
	 * @param array $where
	 * @return unknown
	 */
	public static function createSqlWhere($where=array()){
		$sqlWhere = join(' AND ', $where);
		return (!$sqlWhere) ? ' 1 ' : $sqlWhere;
	}
	
	/**
	 * createSqlJoin
	 *
	 * @param array $order
	 * @param unknown_type $orderByString
	 * @return unknown
	 */
	public static function createSqlJoin($join=array()){
		return join(' ', $join);
	}
	
	
	public static function createSqlOrder($order=array(), $prependString='ORDER BY'){
		return $prependString.' '.join(', ', $order);
	}
	
	/**
	 * createSqlLimit
	 *
	 * @param unknown_type $limit
	 * @return unknown
	 */
	public static function createSqlLimit($limit=array()){
		if ( count($limit) == 2 && isset($limit[0]) && isset($limit[1])) {
			return ' LIMIT '.(int)$limit[0].', '.(int)$limit[1].'';
		}
		return "";
	}
	
	/**
	 * createSqlExtSearch
	 *
	 * @param array $eSearch
	 * @return unknown
	 */
	public static function createSqlExtSearch($eSearch=array()){
		if (!$eSearch) return "";
		$out = array();
		foreach ($eSearch as $key => $int) {
			
			// special for dump type
			if($key == 'scb_dump_type') {
				if($int) $out[] = "dump_type = ".(int)$int;
			}
			else if($key == 'scb_multiplayer') {
				if($int) $out[] = "multiplayer = ".(int)$int;
			}
			else {
				$field = str_replace("scb_", '', $key);
				switch ($int) {
					case '0': break;												// *
					case '1': $out[] = "$field <= 0"; break;						// no
					case '2': $out[] = "($field <= 0 OR $field is NULL)"; break;	// no?
					case '3': $out[] = "$field > 0"; break;							// yes
					case '4': $out[] = "($field > 0 OR $field is NULL)"; break;		// yes?
					case '5': $out[] = "$field is NULL"; break;						// ?
				}				
			}

		}
		return join(' AND ', $out);
	}
}
?>