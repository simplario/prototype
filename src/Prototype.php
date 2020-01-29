<?php

namespace Simplario;

/**
 * Class Prototype
 */
class Prototype
{
    /**
     * @var string
     */
    protected $group = 'root';

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $schema = [];

    /**
     * @var array
     */
    protected $tag = [];

    /**
     * @var array
     */
    protected $tmpl = [];


    /**
     * @return array
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function config(array $config = [])
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param       $name
     * @param array $params
     *
     * @return $this
     */
    public function tmpl($name, array $params = null)
    {
        if ($params === null) {
            $index = count($this->schema) - 1;
            $this->schema[$index] = Helper::array_extends($this->schema[$index], $this->tmpl[$name]);

            return $this;
        }

        $this->tmpl[$name] = $params;

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function group($name)
    {
        $this->group = $name;

        return $this;
    }

    /**
     * @param $tag
     *
     * @return $this
     */
    public function tag($tag)
    {
        $this->tag = $tag;

        return $this;
    }


    /**
     * @param array $array
     * @param       $key
     * @param       $value
     *
     * @return array
     */
    public function filter(array $array, $key, $value)
    {
        $pack = array_filter($array, function ($item) use ($key, $value) {
            return $item[$key] == $value;
        });

        return $pack;
    }

    /**
     * @param array $array
     * @param       $key
     * @param bool  $unique
     *
     * @return array
     */
    public function column(array $array, $key, $unique = false)
    {

        $pack = array_column($array, $key);

        if ($unique) {
            $pack = array_unique($pack);
        }

        return array_values($pack);
    }

    /**
     * @return $this
     */
    public function dump()
    {
        print_r($this->schema);

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function render(callable $callback)
    {
        $callback($this);

        return $this;
    }

    /**
     * @param        $target
     * @param        $request
     * @param        $response
     * @param string $message
     *
     * @return $this
     */
    public function target($target, $request, $response, $message = '')
    {

        $target = trim($target);
        $target = preg_replace('/\s+/', ' ', $target);

        list($method, $url) = explode(' ', $target);

        $add = [
            'type'     => __FUNCTION__,
            'method'   => $method,
            'url'      => $url,
            'request'  => $request,
            'response' => $response,
            'message'  => $message,
            // add extra -----------------
            'config'   => $this->config,
            'group'    => $this->group,
            'tag'      => $this->tag,
        ];

        $this->schema[] = $add;

        return $this;
    }


    /**
     * @param        $name
     * @param array  $field
     * @param string $message
     *
     * @return $this
     */
    public function model($name, $field = [], $message = '')
    {
        $name = trim($name);

        $add = [
            'type'    => __FUNCTION__,
            'name'    => $name,
            'field'   => $field,
            'message' => $message,
            // add extra -----------------
            'config'  => $this->config,
            'group'   => $this->group,
            'tag'     => $this->tag,
        ];

        $this->schema[] = $add;

        return $this;
    }

}