<?php
namespace Tests;

trait WithMockHelper
{
    /** @var \PHPUnit_Framework_MockObj_MockObj[] */
    private $mocks;
    
    public function setupMocks(array $classes)
    {
        foreach ($classes as $class) {
            $this->mocks[$class] = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
        }
    }
}
