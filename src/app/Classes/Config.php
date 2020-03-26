<?php


namespace App\Classes;

class Config
{
    static private $data = [];

    public function __construct($config)
    {
        self::$data = $config;

        if (is_file(__DIR__ . '/config.default.php')) {
            // todo: use array_merge_recursive_distinct
            $default = (array) include_once __DIR__ . '/config.default.php';
            self::$data  = array_merge($default, self::$data);
        }
    }

    /**
     * @param $key
     * @param $args
     * @return bool|mixed
     */
    public function __call($key, $args)
    {
        return $key === 'get' ? call_user_func(['Config', 'get'], $args) : false;
    }

    /**
     * @param $key
     * @param bool $default
     * @return bool|mixed
     */
    static function get($key, $default = false)
    {
        $parts = explode('.', $key);
        $key = trim($parts[0]);
        $part = isset($parts[1]) ? trim($parts[1]) : false;

        if ($key && isset(self::$data[$key])) {
            $conf = self::$data[$key];

            return $part
                ? isset($conf[$part])
                    ? $conf[$part]
                    : $default
                : $conf;

        } else {
            return $default;
        }
    }
}
