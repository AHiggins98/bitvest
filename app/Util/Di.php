<?php
namespace App\Util;

class Di
{
    static $objects = [];
    static $handlers = [];
    static $instance;   
    
    /**
     * 
     * @return Di
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Di();
        }
        return self::$instance;
    }
    
    public function get($className)
    {
        if (!isset(self::$objects[$className])) {
            
            if (!isset(self::$handlers[$className]) && !class_exists($className)) {               
                throw new \Exception("Cannot instantiate $className, no handler found");
            } else if (!isset(self::$handlers[$className]) && class_exists($className)) {
                self::$objects[$className] = new $className;
            } else {
                $callable = self::$handlers[$className];
                self::$objects[$className] = $callable($this);
            }
        }
        
        return self::$objects[$className];
    }
    
    public function register($className, callable $callback)
    {
        self::$handlers[$className] = $callback;
    }
}