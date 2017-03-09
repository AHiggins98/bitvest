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
    
    public function set($className, $object)
    {
        self::$objects[$className] = $object;
    }
    
    public function get($className)
    {
        if (!isset(self::$objects[$className])) {
            
            if (!isset(self::$handlers[$className]) && !class_exists($className)) {               
                throw new \Exception("Cannot instantiate $className, no handler found");
            } else if (!isset(self::$handlers[$className]) && class_exists($className)) {
                
                $reflection = new \ReflectionClass($className);
                $constructor = $reflection->getConstructor();
                
                $arguments = [];
                
                if ($constructor) {
                    $constructorParams = $constructor->getParameters();
                    
                    foreach ($constructorParams as $s => $param) {                        
                        $paramType = $param->getClass()->getName();
                        if (class_exists($paramType)) {                        
                            $arguments[] = $this->get($paramType);
                        } else {
                            throw new \Exception("Cannot auto-resolve parameter $param of $className; define a explicit handler instead.");
                        }                        
                    }                    
                } else {
                    $arguments = [];
                }
                
                $object = $reflection->newInstanceArgs($arguments);
                
                self::$objects[$className] = $object;
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