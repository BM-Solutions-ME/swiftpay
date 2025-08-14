<?php

/**
 * ################
 * ###   .ENV   ###
 * ################
*/

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../../");
$dotenv->load();
/* ########################################################## */

/**
 * DATABASE
 */

$conf_db = [
    "host" => $_ENV["DB_CONNECT_HOST"], // IP servidor: 192.168.0.252 url: acesso.bluware.com.br
    "user" => $_ENV["DB_CONNECT_USER"],
    "pass" => $_ENV["DB_CONNECT_PASS"],
    "name" => $_ENV["DB_CONNECT_NAME"]
];

define("CONF_DB_HOST", $conf_db["host"]);
define("CONF_DB_USER", $conf_db["user"]);
define("CONF_DB_PASS", $conf_db["pass"]);
define("CONF_DB_NAME", $conf_db["name"]);

/**
 * TIMEZONE
 */
const CONF_TIMEZONE = "America/Sao_Paulo";

/**
 * PROJECT URLs
 */
$baseUrl = $_ENV['BASE_URL'];

define("CONF_URL_BASE", $baseUrl);
define("CONF_URL_TEST", $baseUrl);

/**
 * CREDENTIAL GOOGLE API KEY
 */
const GOOGLE_API_KEY = "";

/**
 * DATES
 */
const CONF_DATE_BR = "d/m/Y H:i:s";
const CONF_DATE_APP = "Y-m-d H:i:s";

/**
 * PASSWORD
 */
const CONF_PASSWD_MIN_LEN = 8;
const CONF_PASSWD_MAX_LEN = 20;
const CONF_PASSWD_ALGO = PASSWORD_DEFAULT;
const CONF_PASSWD_OPTION = ["cost" => 10];

/**
 * VIEW
 */
const CONF_VIEW_PATH = __DIR__ . "/../../shared/views";
const CONF_VIEW_EXT = "php";
const CONF_VIEW_THEME = "web";
const CONF_VIEW_APP = "app";
const CONF_VIEW_ADMIN = "adm";

/**
 * UPLOAD
 */
const CONF_UPLOAD_DIR = "storage";
const CONF_UPLOAD_IMAGE_DIR = "images";
const CONF_UPLOAD_FILE_DIR = "files";
const CONF_UPLOAD_MEDIA_DIR = "medias";

/**
 * IMAGES
 */
const CONF_IMAGE_CACHE = CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache";
const CONF_IMAGE_SIZE = 2000;
const CONF_IMAGE_QUALITY = ["jpg" => 75, "png" => 5];

/**
 * MAIL
 */
const CONF_MAIL_HOST = "mail.test.com.br";
const CONF_MAIL_PORT = "587";
const CONF_MAIL_USER = "test@test.com.br";
const CONF_MAIL_PASS = "32idlau4";
const CONF_MAIL_SENDER = ["name" => "Test", "address" => "test@test.com.br"];
const CONF_MAIL_SUPPORT = "test@test.com.br";
const CONF_MAIL_OPTION_LANG = "br";
const CONF_MAIL_OPTION_HTML = true;
const CONF_MAIL_OPTION_AUTH = true;
const CONF_MAIL_OPTION_SECURE = "tls";
const CONF_MAIL_OPTION_CHARSET = "utf-8";

/*
 *  JWT
 * **/
const JWT_SECRET_KEY = "6bd9f25b606bbcf4e34d224dffe58b62"; // secret key to jwt authenticate
const JWT_DURATION = "P0DT30M";

const REFRESH_TOKEN_SECRET = "test-clean-architecture";
const REFRESH_TOKEN_DURATION = "P0DT60M";
