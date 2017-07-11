<?php

/**
 * LINE API BASE CLASS<br/>
 * https://developers.line.me/bot-api/api-reference
 */
abstract class line_bot extends base {
	public $receive = null;
	public $user;// line apiで取得したuser情報の配列

	public function __construct() {
		parent::__construct();
		$this->set_property();
		$this->logging();
	}
	
	/**
	 * [必須]メイン処理
	 */
	abstract public function run();

	private function set_property() {
		$this->receive = $this->receive();
		$this->user = $this->user();
	}

	/**
	 * LINEからのデータ受け取り
	 */
	public function receive() {
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		return $data['events'];
	}
	
	/**
	 * 送信者情報取得
	 */
	public function user() {
		$url = LINE_API.'profile/'.$this->receive[0]['source']['userId'];
		$curl = curl_init();
		
		$header = array(
			'Authorization: Bearer '.LINE_CHANNEL_ACCESS_TOKEN,
			'Content-Type: application/json',
		);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);// 証明書の検証を行わない
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);// curl_execの結果を文字列で返す
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);// リクエストにヘッダーを含める
		$response = curl_exec($curl);
		curl_close($curl);
		
		return json_decode($response, true);
	}

	/**
	 * 返信データ成形
	 * @param array $message
	 */
	public function reply(array $message) {
		$arr_text = array();
		$arr_image = array();
		foreach($message as $k => $v){
			if($k == 'text') $arr_text[] = array('type' => 'text', 'text' => $v);
			if($k == 'image' && !empty($v)) $arr_image[] = array('type' => 'image', 'originalContentUrl' => $v, 'previewImageUrl' => $v);
		}
		$arr_reply = array_merge($arr_image,$arr_text);
		$this->send($arr_reply, $this->receive[0]['replyToken']);
	}
	
	/**
	 * LINE API叩いて終了
	 * @param array $reply
	 * @param type $reply_token
	 */
	private function send(array $reply, $reply_token) {
		$post_data = array(
			"replyToken" => $reply_token,
			"messages" => $reply
		);
		
		$ch = curl_init(LINE_API."message/reply");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charser=UTF-8',
			'Authorization: Bearer ' . LINE_CHANNEL_ACCESS_TOKEN
		));
		curl_exec($ch);
		curl_close($ch);
		exit;
	}
	
	/**
	 * 受け取った際の情報をロギング
	 */
	private function logging() {
		# user
		$log = PHP_EOL;
		$log .= "[displayName] => {$this->user['displayName']}".PHP_EOL;
		$log .= "[userId] => {$this->user['userId']}".PHP_EOL;
		$log .= "[pictureUrl] => {$this->user['pictureUrl']}".PHP_EOL;
		$log .= "[statusMessage] => {$this->user['statusMessage']}".PHP_EOL;
		$log .= "[message] => {$this->receive[0]['message']['text']}";
		
		$this->user_log($log,"user");
	}
	
	/**
	 * ログ書き込み
	 * @param type $log
	 */
	private function user_log($log) {
		if(!is_dir(LOG_PATH)){
			mkdir(LOG_PATH, 0777);
		}
		$time_stamp = date("Y-m-d H:i:s");
		$output = print_r($log, true);
		error_log("[{$time_stamp}] ".$output.PHP_EOL, 3, LOG_PATH."user.log");
	}
}