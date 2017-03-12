<?php

namespace App\Model;

trait WithProperties
{
    private $properties;
    private $propertyNames;
    
    public function defineProperties(array $propertyNames)
    {
        $this->propertyNames = $propertyNames;
        foreach ($propertyNames as $propertyName) {
            $this->properties[$propertyName] = null;
        }
    }
    
    public function __get($property)
    {
        if (!in_array($property, $this->propertyNames)) {
            throw new \Exception(get_class($this) . " does not have the property `$property`.");
        }
        return $this->properties[$property];
    }
    
    public function __set($property, $value)
    {
        if (!in_array($property, $this->propertyNames)) {
            throw new \Exception(get_class($this) . " does not have the property `$property`.");
        }
        $this->properties[$property] = $value;
    }
    
    public function __isset($property)
    {
        if (!in_array($property, $this->propertyNames)) {
            throw new \Exception(get_class($this) . " does not have the property `$property`.");
        }
        return isset($this->properties[$property]);
    }
    
    public function __toString()
    {
        return json_encode($this->properties);
    }
    
    public function __sleep()
    {
        return $this->__toString();
    }
    
    public function fromJsonString($json)
    {
        $properties = json_decode($json);
        foreach ($properties as $property => $value) {
            $this->$property = $value;
        }
    }
}
