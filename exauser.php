<?php
$urlparamstr = $_SERVER["QUERY_STRING"];
$user_sessionid = '';
$user = '';
if (!empty($urlparamstr)) {
    $urlparams = explode('&', $urlparamstr);
    foreach ($urlparams as $k=>$v)
        if (strpos($v, '=') == false) {
            $user_sessionid = base64_decode($v);
            break;
        }
}
if (empty($user_sessionid)) {
    $user = !empty($_GET['user'])? $_GET['user']:'';
    if (empty($user)) die("login required...");
    $user_sessionid = $user . '_sessionid';
}
else if (!isset($_COOKIE[$user_sessionid])) header("location: ?");
if (!isset($_COOKIE[$user_sessionid])) {
    $pg_fetch_obj = null;
    $pgconnectstr = "host=192.168.1.217 port=5433 dbname=KM user=postgres password=1q2w3e4r5t";
    $pgconnect = pg_connect($pgconnectstr) or die('could not connect: '.pg_last-error());
    $user_settings_query = "select id, password, company_id, user_settings::json from users where is_active = true and has_deleted = false and username = '" . $user . "'";
    $user_settings_result = pg_query($user_settings_query) or die('query failed'.pg_last_error());
    if (pg_num_rows($user_settings_result)) {
        while ($object = pg_fetch_object($user_settings_result)) $pg_fetch_obj = $object;
    }
    else die('login does not exist...');
    pg_free_result($user_settings_result);
    pg_close($pgconnect);
    print_r($pg_fetch_obj);
    //$hash = '$2a$08$zGN5mGdIs44rGfL/AWyNXuQx6PS34ZZ5vEuHTagBC8efTVcrkwN1O';
    $pwd = !empty($_GET['pwd'])? $_GET['pwd']:'';
    if (empty($pwd)) die("login pass word required...");
    if (password_verify($pwd, $pg_fetch_obj->password)) {
        $user_settings = json_decode($pg_fetch_obj->user_settings);
        $exasessionname = session_name("exaphp");
        session_start();
        setcookie($user_sessionid, session_create_id(), time()+$user_settings->sessionInterval);
        setcookie($user . '_id', $pg_fetch_obj->id, time()+$user_settings->sessionInterval);
        setcookie($user . '_company_id', $pg_fetch_obj->id, time()+$user_settings->sessionInterval);
        header("location: ?" . base64_encode($user_sessionid));
    } else {
        echo 'Invalid password.';
    }
}
else {
    echo "login active...";
    //$ciphertext_dec = base64_decode($ciphertext_base64);
}
//if (isset($_SESSION[$user_sessionid])) die("login active...");
?>