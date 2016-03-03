<?php

class Object
{
    protected function populateWithArray($array) {
        foreach ($this as $property => $value) {
            if (array_key_exists($property, $array)) {
                $this->$property = $array[$property];
            }
        }
    }
}