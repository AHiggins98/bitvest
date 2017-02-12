<?php

namespace App\Util;

class Email
{
    public function send($template, array $vars)
    {
        if (!isset($vars['email']) || !isset($vars['subject'])) {
            throw new \Exception('Target email and subject required');
        }
        
        $template = file_get_contents(__DIR__ . '/../EmailTemplate/' . $template . '.tpl');
        
        foreach ($vars as $key => $value) {
            $template = str_replace("{{" . $key . "}}", $value, $template);
        }
        
        $templateLines = explode("\n", $template);
        $properTemplateLines = [];
        foreach ($templateLines as $line) {
            /*if (strlen($line) > 70) {
                $properTemplateLines[] = implode("\r\n", str_split($line, 70));
            } else*/ {
                $properTemplateLines[] = $line;
            }
        }
        $properTemplate = implode("\r\n", $properTemplateLines) . "\r\n";
        
        $result = mail($vars['email'], $vars['subject'], $properTemplate, "From: support@whebsite.com\r\nReply-to: support@whebsite.com");
        
        if (!$result) {
            throw new \Exception('Not accepted for delivery');
        }
    }
}