<?php
define("DB_DRIVER", "mysql");
define("DB_HOST", "localhost");
define("DB_NAME", "[your_db_name]");
define("DB_USER", "[your_db_user]");
define("DB_PASSWORD", "[your_db_password]");
define("DB_DSN", DB_DRIVER.":dbname=".DB_NAME.";host=".DB_HOST.";");