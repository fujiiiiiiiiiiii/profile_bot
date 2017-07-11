<?php
class base {
	public $db;
	public $time;
	public $date;
	
	public function __construct() {
		$this->db_connect();
		$this->time = time();
		$this->date = date('Y-m-d H:i:s', $this->time);
	}
	
	/**
	 * DB Connect
	 */
	private function db_connect(){
		try{
			$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
			$this->db = $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD, $options);
		} catch (Exception $e) {
			$this->log($e->getMessage(), "error");
			$this->error("[db] connect error");
		}
	}
	
	public function transaction(){
		try{
			$this->db->beginTransaction();
		} catch (Exception $e) {
			$this->log($e->getMessage(), "error");
			$this->error("[db]transaction error");
		}
	}
	
	public function rollback(){
		try{
			$this->db->rollBack();
		} catch (Exception $e) {
			$this->log($e->getMessage(), "error");
			$this->error("[db]rollback error");
		}
	}
	
	public function commit(){
		try{
			$this->db->commit();
		} catch (Exception $e) {
			$this->log($e->getMessage(), "error");
			$this->error("[db]transaction error");
		}
	}
	
	public function insert($table, $value){
		try{
			$sql = sprintf(
				'INSERT INTO %s (`%s`) VALUES (%s)', $table, implode('`,`', array_keys($value)), implode(',', array_pad(array(), count($value), '?'))
			);

			$stmt = $this->db->prepare($sql);
			$stmt->execute(array_values($value));
			$id = $this->db->lastInsertId();

			if($id) return $id;
			return true;
		} catch (Exception $ex) {
			$this->log($ex->getMessage(), "error");
			return false;
		}
	}
	
	public function update($table, $w, $params, $v) {
		$ph = array();
		foreach($v as $k => $val) {
			$ph[] = sprintf('`%s`=?', $k);
		}
		$sql = sprintf('UPDATE %s SET %s WHERE %s', $table, implode(',', $ph), $w);
		return $this->db->query($sql, array_merge(array_values($v), $params));
	}
	
	/**
	* Log出力
	* @param type $log
	* @param type $name
	*/
	public function log($log, $name="debug"){
		if(!is_dir(LOG_PATH)){
			mkdir(LOG_PATH, 0777);
		}

		$date = date("Ymd");
		$time_stamp = date("Y-m-d H:i:s");
		$output = print_r($log, true);
		error_log("[{$time_stamp}] ".$output.PHP_EOL, 3, LOG_PATH."{$date}_{$name}.log");
	}
	
	/**
	 * ログと出力して終わり
	 * @param type $str
	 */
	public function error($str){
		$this->log($str,"error");
		print($str);
		exit;
	}
}