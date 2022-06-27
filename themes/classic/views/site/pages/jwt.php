<?php
//https://ru.wikipedia.org/wiki/JSON_Web_Token
//https://jwt.io/ проверка токенов

include('C:\Users\Igor\vendor\firebase\php-jwt\src\JWT.php');
include('C:\Users\Igor\vendor\firebase\php-jwt\src\SignatureInvalidException.php');

use \Firebase\JWT\JWT;

$key = "example_key";
$key1 = "exdsfsdfample_key";
$payload = array(
    "iss" => "http://example.org",
    "aud" => "http://example.com",
    "iat" => 1356999524,
    "nbf" => 1357000000
);

/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */
$jwt = JWT::encode($payload, $key);
echo '<br>$jwt  = <br>';
print_r($jwt );

try {
    $decoded = JWT::decode($jwt, $key, array('HS256'));
    echo '<br>$decoded = <br>';
    print_r($decoded);
} catch (Exception $e) {
    print_r($e);
}







/*
 NOTE: This will now be an object instead of an associative array. To get
 an associative array, you will need to cast it as such:
*/

$decoded_array = (array) $decoded;

/**
 * You can add a leeway to account for when there is a clock skew times between
 * the signing and verifying servers. It is recommended that this leeway should
 * not be bigger than a few minutes.
 *
 * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
 */
JWT::$leeway = 60; // $leeway in seconds
$decoded = JWT::decode($jwt, $key, array('HS256'));

?>

