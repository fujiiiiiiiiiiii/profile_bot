<?php
# error表示
ini_set('display_errors', 1);// 画面エラー表示する
error_reporting(E_ALL);// すべてのエラー表示

# common
define("DS", DIRECTORY_SEPARATOR);
define("PS", PATH_SEPARATOR);

# LINE
define("LINE_CHANNEL_ACCESS_TOKEN", "[your_access_token]");
define("LINE_API", "https://api.line.me/v2/bot/");

# path
define("APP_PATH", "[your_app_dir]");// index.phpがあるディレクトリを指定してください。末尾は"/"をつけてください。
define("BOT_PATH", APP_PATH."class". DS . "bot".DS);
define("CNF_PATH", APP_PATH."config".DS);
define("LOG_PATH", APP_PATH."log".DS);

# require
require_once(CNF_PATH."db".DS."config.php");
require_once(APP_PATH."class".DS."base".DS."base.php");
require_once(APP_PATH."class".DS."base".DS."line_bot.php");