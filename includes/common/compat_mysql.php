<?php
/*
 * mysql_* compatibility layer for PHP 7.0+ / 8.x (the ext/mysql extension was removed in PHP 7.0).
 *
 * - Does NOTHING on a PHP that still has ext/mysql (PHP 5.x): the native functions are used, so the very
 *   same code base runs unchanged on PHP 5.6 and PHP 8.2.
 * - On newer PHP the functions below are defined on top of mysqli with the old behaviour:
 *     * "default link" = the most recently opened link, mysql_connect() with identical parameters
 *       returns the already open link, mysql_close() closes that shared link;
 *     * failures return false (PHP 8.1+ mysqli throws exceptions by default: switched off here);
 *     * no connection charset is forced, exactly like ext/mysql did;
 *     * fetch functions return false (not null) after the last row.
 * - Safe to include many times (require_once / include_once) from any entry file.
 * Written to stay valid PHP 5.4 syntax so that the file can also be linted on the old server.
 */

if (!function_exists('mysql_connect') && function_exists('mysqli_connect')) {

    if (function_exists('mysqli_report')) {
        mysqli_report(MYSQLI_REPORT_OFF);
    }

    if (!defined('MYSQL_ASSOC')) {
        define('MYSQL_ASSOC', 1);
    }
    if (!defined('MYSQL_NUM')) {
        define('MYSQL_NUM', 2);
    }
    if (!defined('MYSQL_BOTH')) {
        define('MYSQL_BOTH', 3);
    }
    if (!defined('MYSQL_CLIENT_COMPRESS')) {
        define('MYSQL_CLIENT_COMPRESS', 32);
    }

    /*
     * Result set that still behaves like the old "resource": legacy code indexes a result like an array
     * (silently NULL on PHP 5) or prints it ("Resource id #N"); both are errors for a real object on PHP 8.
     */
    class MysqlCompatResult extends mysqli_result implements ArrayAccess
    {
        #[\ReturnTypeWillChange]
        public function offsetExists($offset)
        {
            return false;
        }

        #[\ReturnTypeWillChange]
        public function offsetGet($offset)
        {
            return null;
        }

        #[\ReturnTypeWillChange]
        public function offsetSet($offset, $value)
        {
        }

        #[\ReturnTypeWillChange]
        public function offsetUnset($offset)
        {
        }

        public function __toString()
        {
            return 'Resource id #' . (function_exists('spl_object_id') ? spl_object_id($this) : 0);
        }
    }

    /* A mysqli link that remembers whether it was closed (using a closed mysqli object throws on PHP 8,
       the old extension only returned false). */
    class MysqlCompatLink extends mysqli
    {
        public $compatClosed = false;
    }

    /* Keeps the shared state of the layer (open links, default link, last connect error). */
    class MysqlCompatState
    {
        public static $links = array();      // "host|user|pass" => MysqlCompatLink
        public static $last = null;          // default link
        public static $connectErrno = 0;
        public static $connectError = '';

        public static function isAlive($link)
        {
            return ($link instanceof MysqlCompatLink) && !$link->compatClosed;
        }

        public static function resolve($link)
        {
            if ($link === null) {
                $link = self::$last;
            }
            return self::isAlive($link) ? $link : false;
        }
    }

    function mysql_connect($server = null, $username = null, $password = null, $new_link = false, $client_flags = 0)
    {
        if ($server === null || $server === '') {
            $server = ini_get('mysqli.default_host') ? ini_get('mysqli.default_host') : 'localhost';
        }
        if ($username === null) {
            $username = ini_get('mysqli.default_user') ? ini_get('mysqli.default_user') : '';
        }
        if ($password === null) {
            $password = ini_get('mysqli.default_pw') ? ini_get('mysqli.default_pw') : '';
        }

        $host = (string)$server;
        $port = (int)ini_get('mysqli.default_port');
        $socket = null;
        if ($port <= 0) {
            $port = 3306;
        }
        /* "host:port" or "host:/path/to/socket" like the old extension */
        if (strpos($host, ':') !== false && substr_count($host, ':') === 1) {
            $parts = explode(':', $host, 2);
            $host = $parts[0] === '' ? 'localhost' : $parts[0];
            if ($parts[1] !== '' && $parts[1][0] === '/') {
                $socket = $parts[1];
            } elseif (ctype_digit($parts[1])) {
                $port = (int)$parts[1];
            }
        }

        $key = $server . '|' . $username . '|' . $password;
        if (!$new_link && isset(MysqlCompatState::$links[$key]) && MysqlCompatState::isAlive(MysqlCompatState::$links[$key])) {
            MysqlCompatState::$last = MysqlCompatState::$links[$key];
            return MysqlCompatState::$last;
        }

        $link = new MysqlCompatLink();
        $flags = ((int)$client_flags & MYSQL_CLIENT_COMPRESS) ? MYSQLI_CLIENT_COMPRESS : 0;
        $ok = @mysqli_real_connect($link, $host, (string)$username, (string)$password, '', $port, $socket, $flags);
        if (!$ok) {
            MysqlCompatState::$connectErrno = mysqli_connect_errno();
            MysqlCompatState::$connectError = (string)mysqli_connect_error();
            trigger_error('mysql_connect(): ' . MysqlCompatState::$connectError, E_USER_WARNING);
            return false;
        }
        MysqlCompatState::$connectErrno = 0;
        MysqlCompatState::$connectError = '';
        MysqlCompatState::$links[$key] = $link;
        MysqlCompatState::$last = $link;
        return $link;
    }

    function mysql_pconnect($server = null, $username = null, $password = null, $client_flags = 0)
    {
        return mysql_connect($server, $username, $password, false, $client_flags);
    }

    function mysql_select_db($database_name, $link = null)
    {
        $link = MysqlCompatState::resolve($link);
        return $link ? @mysqli_select_db($link, (string)$database_name) : false;
    }

    function mysql_set_charset($charset, $link = null)
    {
        $link = MysqlCompatState::resolve($link);
        return $link ? @mysqli_set_charset($link, (string)$charset) : false;
    }

    function mysql_query($query, $link = null)
    {
        $link = MysqlCompatState::resolve($link);
        if (!$link) {
            return false;
        }
        if (!@mysqli_real_query($link, (string)$query)) {
            return false;
        }
        if (mysqli_field_count($link) === 0) {
            return true; // INSERT / UPDATE / DELETE ...
        }
        $result = new MysqlCompatResult($link, MYSQLI_STORE_RESULT);
        return $result;
    }

    function mysql_unbuffered_query($query, $link = null)
    {
        $link = MysqlCompatState::resolve($link);
        return $link ? @mysqli_query($link, (string)$query, MYSQLI_USE_RESULT) : false;
    }

    function mysql_real_escape_string($unescaped_string, $link = null)
    {
        $link = MysqlCompatState::resolve($link);
        if (!$link) {
            return false;
        }
        return mysqli_real_escape_string($link, (string)$unescaped_string);
    }

    function mysql_escape_string($unescaped_string)
    {
        $link = MysqlCompatState::resolve(null);
        if ($link) {
            return mysqli_real_escape_string($link, (string)$unescaped_string);
        }
        return addslashes((string)$unescaped_string);
    }

    function mysql_fetch_array($result, $result_type = 3)
    {
        if (!($result instanceof mysqli_result)) {
            return null;
        }
        $row = mysqli_fetch_array($result, (int)$result_type);
        return $row === null ? false : $row;
    }

    function mysql_fetch_assoc($result)
    {
        if (!($result instanceof mysqli_result)) {
            return null;
        }
        $row = mysqli_fetch_assoc($result);
        return $row === null ? false : $row;
    }

    function mysql_fetch_row($result)
    {
        if (!($result instanceof mysqli_result)) {
            return null;
        }
        $row = mysqli_fetch_row($result);
        return $row === null ? false : $row;
    }

    function mysql_fetch_object($result, $class_name = 'stdClass', $params = array())
    {
        if (!($result instanceof mysqli_result)) {
            return false;
        }
        if ($class_name === 'stdClass' || $class_name === null) {
            $row = mysqli_fetch_object($result);
        } else {
            $row = mysqli_fetch_object($result, $class_name, (array)$params);
        }
        return $row === null ? false : $row;
    }

    function mysql_num_rows($result)
    {
        if (!($result instanceof mysqli_result)) {
            return null;
        }
        return mysqli_num_rows($result);
    }

    function mysql_num_fields($result)
    {
        return ($result instanceof mysqli_result) ? mysqli_num_fields($result) : null;
    }

    function mysql_data_seek($result, $row_number)
    {
        return ($result instanceof mysqli_result) ? @mysqli_data_seek($result, (int)$row_number) : null;
    }

    function mysql_result($result, $row, $field = 0)
    {
        if (!($result instanceof mysqli_result) || !@mysqli_data_seek($result, (int)$row)) {
            return false;
        }
        $data = mysqli_fetch_array($result, MYSQLI_BOTH);
        if ($data === null || !array_key_exists($field, $data)) {
            return false;
        }
        return $data[$field];
    }

    function mysql_free_result($result)
    {
        if ($result instanceof mysqli_result) {
            mysqli_free_result($result);
            return true;
        }
        return false;
    }

    function mysql_insert_id($link = null)
    {
        $link = MysqlCompatState::resolve($link);
        return $link ? (int)mysqli_insert_id($link) : false;
    }

    function mysql_affected_rows($link = null)
    {
        $link = MysqlCompatState::resolve($link);
        return $link ? (int)mysqli_affected_rows($link) : -1;
    }

    function mysql_error($link = null)
    {
        $link = MysqlCompatState::resolve($link);
        if (!$link) {
            return MysqlCompatState::$connectError;
        }
        return mysqli_error($link);
    }

    function mysql_errno($link = null)
    {
        $link = MysqlCompatState::resolve($link);
        if (!$link) {
            return MysqlCompatState::$connectErrno;
        }
        return mysqli_errno($link);
    }

    function mysql_ping($link = null)
    {
        $link = MysqlCompatState::resolve($link);
        return $link ? @mysqli_ping($link) : false;
    }

    function mysql_thread_id($link = null)
    {
        $link = MysqlCompatState::resolve($link);
        return $link ? mysqli_thread_id($link) : false;
    }

    function mysql_get_server_info($link = null)
    {
        $link = MysqlCompatState::resolve($link);
        return $link ? mysqli_get_server_info($link) : false;
    }

    function mysql_close($link = null)
    {
        $link = MysqlCompatState::resolve($link);
        if (!$link) {
            return false;
        }
        $ok = @mysqli_close($link);
        $link->compatClosed = true;
        foreach (MysqlCompatState::$links as $k => $l) {
            if ($l === $link) {
                unset(MysqlCompatState::$links[$k]);
            }
        }
        if (MysqlCompatState::$last === $link) {
            MysqlCompatState::$last = null;
        }
        return $ok;
    }
}

/*
 * PHP 8 turned count(null) and count(<scalar>) into a TypeError (PHP 5/7 returned 0 and 1 with a warning).
 * The legacy code base can reach count() with null / '' / false (empty query results, missing form fields),
 * so every count() call of the application goes through this wrapper. Valid on PHP 5.4 - 8.x.
 */
if (!function_exists('app_count')) {
    function app_count($value, $mode = 0)
    {
        if (is_array($value) || $value instanceof Countable) {
            return count($value, $mode);
        }
        return ($value === null) ? 0 : 1;
    }
}
