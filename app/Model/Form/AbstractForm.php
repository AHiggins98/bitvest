<?php
namespace App\Model\Form;

abstract class AbstractForm
{
    private $fields;
    
    protected $hasErrors;
    protected $errors;
    protected $values;
    
    public function __construct(array $fields)
    {
        $this->hasErrors = false;
        
        $this->fields = $fields;
        
        // Initialize to an array with empty string (no error) for each field.
        $this->errors = array_combine($fields, 
                array_fill(0, count($fields), ''));
        
        $this->values = $this->errors;
    }
    
    public function getState()
    {
        return [
            'hasErrors' => $this->hasErrors,
            'formErrors' => $this->errors,
            'formValues' => $this->values,
        ];
    }
    
    protected function setValues(array $fields)
    {
        foreach ($fields as $field => $value) {
            $this->values[$field] = $value;
        }
    }
    
    public function hasErrors()
    {
        return $this->hasErrors;
    }
    
    public function getValue($field)
    {
        return $this->values[$field];
    }
}