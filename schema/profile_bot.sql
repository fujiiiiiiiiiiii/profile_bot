CREATE DATABASE `profile_bot` DEFAULT CHARACTER SET utf8;
GRANT ALL PRIVILEGES ON profile_bot.* TO 'user_name'@'localhost' IDENTIFIED BY 'password' WITH GRANT OPTION;
FLUSH PRIVILEGES;

/* profile */
CREATE TABLE `profile`(
  `profile_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `age` TINYINT UNSIGNED UNIQUE NOT NULL DEFAULT 0,
  `content` TEXT NOT NULL,
  `image_url` VARCHAR(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ngword` (
  `ng_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ngword` VARCHAR(32)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `ngword` (`ngword`) VALUES
('しね'),('しねよ'),('死ね'),('死ねよ'),('氏ね'),('氏ねよ'),('市ね'),('市ねよ'),('シネ'),('ｼﾈ'),('シネヨ'),('ｼﾈﾖ'),
('殺す'),('ころす'),('コロス'),('ｺﾛｽ'),
('ちんこ'),('チンコ'),('ﾁﾝｺ'),('ちんぽ'),('チンポ'),('ﾁﾝﾎﾟ'),('ちんぽこ'),('チンポコ'),('ﾁﾝﾎﾟｺ'),('ぽこちん'),('ちんちん'),('チンチン'),('ﾁﾝﾁﾝ'),('おちんちん'),('おチンチン'),('ｵﾁﾝﾁﾝ'),
('まんこ'),('マンコ'),('おまんこ'),('オマンコ'),('オメコ'),('まんまん'),('マンマン'),('オマンコ'),('ﾏﾝｺ'),('ｵﾏﾝｺ'),('ｵﾒｺ'),
('sex'),('セックス'),('せくーす'),('ｾｸｰｽ'),('sexしよ'),('sexしよう'),
('fuck'),('ファック'),('ﾌｧｯｸ'),('fuck you'),('fuck me'),
('すぐいる？')
;
