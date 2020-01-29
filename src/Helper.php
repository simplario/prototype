<?php

namespace Simplario;

/**
 * Class Helper
 */
class Helper
{
    /**
     * @param       $string
     * @param array $data
     *
     * @return string
     */
    public static function strf($string, array $data = [])
    {
        $replace = [];
        foreach ($data as $key => $value) {
            $replace['{' . $key . '}'] = $value;
        }

        return strtr($string, $replace);
    }


    /**
     * @param        $input
     * @param string $separator
     *
     * @return mixed
     */
    public static function stringCamelize($input, $separator = '_')
    {
        return str_replace($separator, '', ucwords($input, $separator));
    }

    /**
     * @param array $array1
     * @param mixed ...$params
     *
     * @return array
     */
    public static function array_extends(array $array1 = [], ...$params)
    {
        $merged = $array1;

        while ($array2 = array_shift($params)) {
            foreach ($array2 as $key => &$value) {
                if (is_array($value) && isset ($merged[$key]) && is_array($merged[$key])) {
                    $merged[$key] = self::array_extends($merged[$key], $value);
                } else {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }

    /**
     * @param      $data
     * @param      $path
     * @param null $default
     *
     * @return null
     */
    public static function array_dot_get($data, $path, $default = null)
    {
        $pathKeys = explode('.', $path);
        foreach ($pathKeys as $key) {
            if (isset($data[$key])) {
                $data = $data[$key];
            } else {
                return $default;
            }
        }

        return $data;
    }


    /**
     * @param array  $data
     * @param string $path
     * @param mixed  $value
     * @param bool   $append
     *
     * @return bool
     */
    public static function array_dot_set(&$data, $path, $value, $append = false)
    {
        $pathKeys = explode('.', $path);
        foreach ($pathKeys as $count => $path) {
            if (!isset($data[$path]) && $count != count($pathKeys)) {
                $data[$path] = [];
            }
            $data =& $data[$path];
        }

        if ($append === true) {
            $data[] = $value;
        } else {
            $data = $value;
        }

        return true;
    }

    /**
     * @param array  $data
     * @param string $path
     */
    public static function array_dot_unset(&$data, $path)
    {
        $pathKeys = explode('.', $path);
        foreach ($pathKeys as $count => $path) {
            if (isset($data[$path]) && $count == count($pathKeys) - 1) {
                unset($data[$path]);

                return;
            } elseif (isset($data[$path])) {
                $data =& $data[$path];
            } else {
                return;
            }
        }
    }

    /**
     * @param $data
     * @param $path
     * @param $value
     *
     * @return bool
     */
    public static function array_dot_push(&$data, $path, $value)
    {
        $array = (array)self::array_dot_get($data, $path);
        $array[] = $value;
        self::array_dot_set($data, $path, $array);

        return true;
    }

    /**
     * @param $data
     * @param $path
     *
     * @return mixed
     */
    public static function array_dot_shift(&$data, $path)
    {
        $array = (array)self::array_dot_get($data, $path);
        $result = array_shift($array);
        self::array_dot_set($data, $path, $array);

        return $result;
    }


    /**
     * @param $data
     * @param $path
     *
     * @return mixed
     */
    public static function array_dot_pop(&$data, $path)
    {
        $array = (array)self::array_dot_get($data, $path);
        $result = array_pop($array);
        self::array_dot_set($data, $path, $array);

        return $result;
    }

    /**
     * @param $data
     * @param $path
     *
     * @return int
     */
    public static function array_dot_count(&$data, $path)
    {
        $array = (array)self::array_dot_get($data, $path);
        $result = count($array);

        return $result;
    }


    /**
     * @param array $array
     * @param array $only
     *
     * @return array
     */
    public static function array_only(array $array, array $only = [])
    {
        $result = [];
        foreach ($only as $key) {
            if (isset($array[$key])) {
                $result[$key] = $array[$key];
            }
        }

        return $result;
    }


    /**
     * @param array  $data
     * @param string $path
     *
     * @return bool
     */
    public static function array_dot_isset($data, $path)
    {
        $pathKeys = explode('.', $path);
        foreach ($pathKeys as $key) {
            if (isset($data[$key])) {
                $data = $data[$key];
            } else {
                return false;
            }
        }

        return true;
    }
}
