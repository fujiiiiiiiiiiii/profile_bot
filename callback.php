<?php
/**
 * front controller
 * ?bot=xxxで指定されたxxxがclass/bot/xxx.phpとして存在すればxxx.phpを読み込みnewして実行する
 */
require_once(dirname(__FILE__)."/config/config.php");

# bot name
$bot_name = (string) strtolower(filter_input(INPUT_GET, 'bot')."_bot");
if($bot_name == "_bot"){
	echo "bye!";
	exit;
}

# is bot type ?
$bot_file = BOT_PATH."{$bot_name}.php";
if(!is_file($bot_file)) {
	echo "bye!!";
	exit;
}

# reqire
require_once($bot_file);

# bot start
$bot = new $bot_name();
$bot->run();