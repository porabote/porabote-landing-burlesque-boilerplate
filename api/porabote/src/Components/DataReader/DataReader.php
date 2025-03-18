<?php
namespace Porabote\Components\DataReader;

class DataReader
{
    private $data; //object|array

    public function __construct($data)//object|array
    {
        if (gettype($data) == "array") {
            $data = json_decode(json_encode($data));
        }
        $this->data = $data;
    }

    public function get(string $path, $returnAsDataReader = false)
    {
        $path = explode('.', $path);
        $stackCursor = $this->data;

        $i = 0;
        $value = null;
        while ($i < count($path)) {

            if (!property_exists($stackCursor, $path[$i])) {
                break;
            }

            if ($i == (count($path) - 1)) {
                $value = $stackCursor->{$path[$i]};
            } else {
                $stackCursor = $stackCursor->{$path[$i]};
            }

            $i++;
        }

        if ($returnAsDataReader) {
            return new DataReader($value);
        }

        return $value;
    }

    public function set(string $path, $value)
    {
        $path = explode('.', $path);
        $stackCursor = $this->data;

        $i = 0;
        while ($i < count($path)) {

            if (!property_exists($stackCursor, $path[$i])) {
                break;
            }

            if ($i == (count($path) - 1)) {
                $stackCursor->{$path[$i]} = $value;
            } else {
                $stackCursor = $stackCursor->{$path[$i]};
            }

            $i++;
        }
    }

    public function toArray()
    {
        return (array) $this->data;
    }

    public function toJson()
    {
        return json_encode((array) $this->data);
    }
}
