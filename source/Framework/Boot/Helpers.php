<?php

/**
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

/**
 * @param string $email
 * @return bool
 */
function is_email(string $email): bool
{
    return (filter_var($email, FILTER_VALIDATE_EMAIL) !== false);
}

/**
 * @param string $password
 * @return bool
 */
function is_passwd(string $password): bool
{
    if (mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN) {
        return true;
    }

    return false;
}

/**
 * Utilizado para esqueci minha senha e novos acessos ao sistema web
 * @return String
 */
function password_generate()
{
    $chars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $size = strlen($chars) - 1;
    $password = null;

    for ($i = 0; $i < 6; $i++) {
        $password .= $chars[mt_rand(0, $size)];
    }

    return $password;
}

/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

/**
 * @param string $string
 * @return string
 */
function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    $slug = str_replace(
        ["-----", "----", "---", "--"],
        "-",
        str_replace(
            " ",
            "-",
            trim(strtr(
                mb_convert_encoding(($string !== false ? $string : ''), 'ISO-8859-1', 'UTF-8'),
                mb_convert_encoding($formats, 'ISO-8859-1', 'UTF-8'),
                $replace)
            )
        )
    );
    return $slug;
}

/**
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
    $string = str_slug($string);
    $studlyCase = str_replace(
        " ",
        "",
        mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
    );

    return $studlyCase;
}

/**
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
}

/**
 * @param string $string
 * @return string
 */
function str_title(string $string): string
{
    $stringFiltered = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
    return mb_convert_case(($stringFiltered !== false ? $stringFiltered : ''), MB_CASE_TITLE);
}

/**
 * @param string $text
 * @return string
 */
function str_textarea(string $text): string
{
    $text = filter_var($text, FILTER_SANITIZE_STRIPPED);
    $arrayReplace = ["&#10;", "&#10;&#10;", "&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;&#10;"];
    return "<p>" . str_replace($arrayReplace, "</p><p>", ($text !== false ? $text : '')) . "</p>";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
    $stringFiltered = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
    $string = trim(($stringFiltered !== false ? $stringFiltered : ''));
    $arrWords = explode(" ", $string);
    $numWords = count($arrWords);

    if ($numWords < $limit) {
        return $string;
    }

    $words = implode(" ", array_slice($arrWords, 0, $limit));
    return "{$words}{$pointer}";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = "..."): string
{
    $stringFiltered = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
    $string = trim(($stringFiltered !== false ? $stringFiltered : ''));
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $substr = mb_substr($string, 0, $limit);
    $lastSpace = mb_strrpos($substr, " ");

    // Garante que um int válido será usado
    $cutLength = $lastSpace !== false ? $lastSpace : $limit;

    $chars = mb_substr($string, 0, $cutLength);
    return "{$chars}{$pointer}";
}

/**
 * @param ?string $price
 * @return string
 */
function str_price(?string $price): string
{
    $value = (float) (!empty($price) ? $price : 0);
    return number_format($value, 2, ",", ".");
}

/**
 * @param string|null $search
 * @return string
 */
function str_search(?string $search): string
{
    if (!$search) {
        return "all";
    }

    $search = preg_replace("/[^a-z0-9A-Z\@\ ]/", "", $search);
    return (!empty($search) ? $search : "all");
}

/**
 * @return array<string, mixed>
*/
function objToArray(object $object): array
{
    $json = json_encode($object);
    return json_decode(($json !== false ? $json : ''), true);
}

function objToJson(object $object): string
{
    $json = json_encode($object);
    return ($json !== false ? $json : '');
}

/**
 * ###############
 * ###   URL   ###
 * ###############
 */

/**
 * @param ?string $path
 * @return string
 */
function url(?string $path = null): string
{
    if (preg_match("/localhost/", $_SERVER['HTTP_HOST'])) {
        if ($path) {
            return CONF_URL_TEST . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST;
    }

    if ($path) {
        return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE;
}

/**
 * @return string
 */
function url_back(): string
{
    return ($_SERVER['HTTP_REFERER'] ?? url());
}

/**
 * @param string $url
 */
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit;
    }

    if (filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url) {
        $location = url($url);
        header("Location: {$location}");
        exit;
    }
}

/**
 * ##################
 * ###   ASSETS   ###
 * ##################
 */

// /**
//  * @return \Source\Models\User|null
//  */
// function user(): ?\Source\Models\User
// {
//     return \Source\Repositories\Auth::user();
// }

/**
 * @param ?string $path
 * @param string $theme
 * @return string
 */
function theme(?string $path = null, string $theme = CONF_VIEW_THEME): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost")) {
        if ($path) {
            return CONF_URL_TEST . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }

        return CONF_URL_TEST . "/themes/{$theme}";
    }

    if ($path) {
        return CONF_URL_BASE . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE . "/themes/{$theme}";
}

/**
 * ################
 * ###   DATE   ###
 * ################
 */

/**
 * @param string $date
 * @param string $format
 * @return string
 * @throws Exception
 */
function date_fmt(?string $date, string $format = "d/m/Y H\hi"): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format($format);
}

/**
 * @param string $date
 * @return string
 * @throws Exception
 */
function date_fmt_br(?string $date): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format(CONF_DATE_BR);
}

/**
 * @param string $date
 * @return string
 * @throws Exception
 */
function date_fmt_app(?string $date): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format(CONF_DATE_APP);
}

/**
 * @param string|null $date
 * @return string|null
 */
function date_fmt_back(?string $date): ?string
{
    if (!$date) {
        return null;
    }

    if (strpos($date, " ")) {
        $date = explode(" ", $date);
        return implode("-", array_reverse(explode("/", $date[0]))) . " " . $date[1];
    }

    return implode("-", array_reverse(explode("/", $date)));
}

/**
 * Date with timezone config
 * @throws Exception
 */
function current_date_tz(string $format = "Y-m-d H:i:sP", ?string $date = null, ?string $addInterval = null): string
{
    $current_date = new DateTime();

    if (!empty($addInterval)) {
        $current_date->add(new DateInterval($addInterval));
    }

    return $current_date->setTimezone(new DateTimeZone(CONF_TIMEZONE))->format($format);
}

/**
 * ####################
 * ###   PASSWORD   ###
 * ####################
 */

/**
 * @param string $password
 * @return string
 */
function passwd(string $password): string
{
    if (!empty(password_get_info($password)['algo'])) {
        return $password;
    }

    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * @param string $hash
 * @return bool
 */
function passwd_rehash(string $hash): bool
{
    return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * ###################
 * ###   REQUEST   ###
 * ###################
 */

/**
 * @return string
 */
function csrf_input(): string
{
    $session = new \Source\Framework\Core\Session();
    $session->csrf();
    return "<input type='hidden' name='csrf' value='" . ($session->get('csrf_token') ?? "") . "'/>";
}

/**
 * @param ?String $csrf
 * @return bool
 */
function csrf_verify(?String $csrf): bool
{
    $session = new \Source\Framework\Core\Session();
    if (empty($session->get('csrf_token')) || empty($csrf) || $csrf != $session->get('csrf_token')) {
        return false;
    }
    return true;
}

/**
 * @param string $key
 * @param int $limit
 * @param int $seconds
 * @return bool
 */
function request_limit(string $key, int $limit = 5, int $seconds = 60): bool
{
    $session = new \Source\Framework\Core\Session();
    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests < $limit) {
        $session->set($key, [
            "time" => time() + $seconds,
            "requests" => $session->$key->requests + 1
        ]);
        return false;
    }

    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests >= $limit) {
        return true;
    }

    $session->set($key, [
        "time" => time() + $seconds,
        "requests" => 1
    ]);

    return false;
}

/**
 * @param string $field
 * @param string $value
 * @return bool
 */
function request_repeat(string $field, string $value): bool
{
    $session = new \Source\Framework\Core\Session();
    if ($session->has($field) && $session->$field == $value) {
        return true;
    }

    $session->set($field, $value);
    return false;
}

/**
 * ##########################
 * ###   DATA STRUCTURE   ###
 * ##########################
 */

/**
 * @return array<string, mixed>
*/
function toArray(object $data): array
{
    $json = json_encode($data);
    return json_decode(($json !== false ? $json : ''), true);
}

/**
 * @param array<string, mixed> $data
*/
function toObject(array $data): object
{
    return (object) $data;
}

/**
 *  JWT
 *
 * @return array<string, mixed>
 */
function jwt_decode(string $token): array
{
    $jwt = explode('.', $token);

    // Extract the middle part, base64 decode, then json_decode it
    return json_decode(base64_decode($jwt[1]), true);
}

/**
 * JSON COUNTRIES
 */
function get_country_info(?string $iso = null): mixed
{
    $json = __DIR__ . "/../../Infra/Database/Json/countries.json";
    $countries = json_decode($json, true);

    if (empty($iso)) {
        return $countries;
    }

    foreach ($countries as $country) {
        if ($country["iso"] == $iso) {
            return $country;
        }
    }

    return null;
}
