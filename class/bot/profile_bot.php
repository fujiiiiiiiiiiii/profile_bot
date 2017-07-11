<?php

class profile_bot extends line_bot {
	# 検索対象年齢
	const MIN_AGE = 0;
	const MAX_AGE = 31;
	
	# 対象外年齢を指定された際に返す文章
	const OUTSIDE_TEXT = "すみません。その年齢の思い出はありません。。。";
	
	# 対象外年齢を指定された際に返す画像ファイルURLの配列
	private $arr_outside_image = array(
		"[your_image_url]",
		"[your_image_url]"
	);
	
	# 対象外年齢を指定された際に返すパターン
	# 0:text,image両方 1:textのみ 2:imageのみ
	const OUTSIDE_TYPE = 0;
	
	/**
	 * construct
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 該当年齢がない場合の処理
	 * @return string
	 */
	public function outside() {
		$arr_outside['text'] = self::OUTSIDE_TEXT;
		
		if(self::OUTSIDE_TYPE != 1) {
			$r = $this->outside_image();
			if($r){
				$arr_outside['image'] = $r;
			}
		}
		return $arr_outside;
	}
	
	/**
	 * 
	 * @return type
	 */
	private function outside_image() {
		if(empty($this->arr_outside_image)) return false;
		return $this->arr_outside_image[array_rand($this->arr_outside_image, 1)];
	}
	
	/**
	 *  abstract method
	 */
	public function run() {
		# ngwordチェック
		$r = $this->ngword();
		if($r !== true) $this->reply($r);
		
		# 受信データチェック
		$r = $this->receive_validation();
		if($r !== true) $this->reply($r);
		
		# 受信データからselect
		$message = $this->select_message();
		$response_message = !empty($message) ? $message : $this->outside();
		$this->reply($response_message);
	}
	
	/**
	 * 想定データの場合はtrueを返し、それ以外の場合はエラーメッセージを返します。<br/>
	 * if($response !== true)で判定してください。
	 */
	public function receive_validation() {
		# テキストか
		if($this->receive[0]['message']['type'] != "text"){
			return array('text' => "知りたい年齢で話しかけてください(> <)".PHP_EOL."例：18".PHP_EOL);
		}
		
		# 数字のみか
		if(!preg_match("/^[0-9]+$/", $this->receive[0]['message']['text'])){
			$str = "半角数字のみで話しかけてください(> <)".PHP_EOL.PHP_EOL;
			$str .= "NG例1：半角数字以外が入っている".PHP_EOL."18才を教えて！".PHP_EOL.PHP_EOL;
			$str .= "NG例2：全角数字になっている".PHP_EOL."１８".PHP_EOL.PHP_EOL;
			$str .= "OK例：半角数字のみ".PHP_EOL."18";
			return array('text' => $str);
		}
		
		# 範囲内か
		if(self::MIN_AGE > $this->receive[0]['message']['text'] || $this->receive[0]['message']['text'] > self::MAX_AGE){
			return array('text' => self::MIN_AGE."-".self::MAX_AGE."の範囲でお願いします");
		}
		
		return true;
	}
	
	/**
	 * NGワードチェック
	 * @return type
	 */
	private function ngword() {
		$sql = "SELECT ngword FROM ngword WHERE ngword LIKE :ngword";
		$stmt = $this->db->prepare($sql);
		$val = "%".$this->receive[0]['message']['text']."%";
		$stmt->bindValue(":ngword", $val);
		$stmt->execute();
		$r = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!empty($r)){
			$text = "BOT登録されてる皆様へ".PHP_EOL.PHP_EOL;
			$text .= "{$this->user['displayName']}さんは".PHP_EOL;
			$text .= "「{$this->receive[0]['message']['text']}」".PHP_EOL.PHP_EOL;
			$text .= "と言っています(｀_´)ゞ";
		}
		
		return empty($r) ? true : array('text' => $text);
	}
	
	/**
	 * 
	 * @return type
	 */
	private function select_message() {
		$sql = "SELECT content AS `text`,image_url AS `image` FROM profile p WHERE p.age = :age";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":age", $this->receive[0]['message']['text']);
		$stmt->execute();
		$r = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return empty($r) ? array() : $r;
	}
}