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
     * @param            $name
     * @param array|null $params
     *
     * @return $this
     * @throws \Exception
     */
    public function tmpl($name, array $params = null)
    {
        if ($params === null) {

            if (!isset($this->tmpl[$name])) {
                throw new \Exception('Tmpl is undefined, please setup it first!');
            }

            $this->updateLast($this->tmpl[$name]);

            return $this;
        }

        $this->tmpl[$name] = $params;

        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return $this
     */
    public function __call($name, $arguments)
    {
        $this->updateLast([$name => $arguments]);

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function updateLast(array $data = [])
    {
        $index = count($this->schema) - 1;
        $this->schema[$index] = Helper::array_extends($this->schema[$index], $data);

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
     * @param array $bind
     *
     * @return $this
     */
    public function bind(array $bind = [])
    {
        $this->updateLast(['bind' => $bind]);

        return $this;
    }

    /**
     * @param array $request
     *
     * @return $this
     */
    public function req(array $request = [])
    {
        $this->updateLast(['request' => $request]);

        return $this;
    }

    /**
     * @param array $response
     *
     * @return $this
     */
    public function res(array $response = [])
    {
        $this->updateLast(['response' => $response]);

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
     * @param array $array
     *
     * @return $this
     */
    public function add(array $array = [])
    {
        $array['group'] = $this->group;
        $array['config'] = $this->config;
        $array['tag'] = $this->tag;

        $this->schema[] = $array;

        return $this;
    }


    /**
     * @param        $target
     * @param string $message
     *
     * @return Prototype
     */
    public function target($target, $message = '')
    {
        $target = trim($target);
        $target = preg_replace('/\s+/', ' ', $target);

        list($method, $url) = explode(' ', $target);

        $add = [
            'type'     => __FUNCTION__,
            'method'   => $method,
            'url'      => $url,
            'message'  => $message,
        ];

        return $this->add($add);
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
        ];

        return $this->add($add);
    }

}