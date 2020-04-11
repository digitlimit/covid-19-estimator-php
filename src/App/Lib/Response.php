<?php

namespace App\Lib;

use SimpleXMLElement;

class Response
{
    private $status = 200;

    public function status(int $code)
    {
        $this->status = $code;
        return $this;
    }
    
    public function toJSON($data = [])
    {
        http_response_code($this->status);
        header('Content-Type: application/json');
        echo json_encode($data, true);
    }


    public function toPlainText($text)
    {
        http_response_code($this->status);
        header('Content-Type: text/plain');
        echo $text;
    }

    public function toXML($data = [])
    {
        http_response_code($this->status);
        header('Content-Type: application/xml');

        echo $this->arrayToXml($data);
    }

    protected function arrayToXml($array, $rootElement = null, $xml = null)
    {
        $_xml = $xml;

        // If there is no Root Element then insert root
        if ($_xml === null) {
            $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>');
        }

        // Visit all key value pair
        foreach ($array as $k => $v) {

            // If there is nested array then
            if (is_array($v)) {

                // Call function for nested array
                $this->arrayToXml($v, $k, $_xml->addChild($k));
            } else {

                // Simply add child element.
                $_xml->addChild($k, $v);
            }
        }

        return $_xml->asXML();
    }
}
