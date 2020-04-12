<?php

namespace App\Lib;

use SimpleXMLElement;

/**
 * Class Response
 * @package App\Lib
 */
class Response
{
    private $status = 200;

    /**
     * Status code
     * @param int $code
     * @return $this
     */
    public function status(int $code)
    {
        $this->status = $code;
        return $this;
    }

    /**
     * Run before request
     */
    protected function beforeResponse(){
        App::log();
    }

    /**
     * Return JSON response
     *
     * @param array $data
     */
    public function toJSON($data = [])
    {
        http_response_code($this->status);
        header('Content-Type: application/json');

        $this->beforeResponse();

        echo json_encode($data, true);
    }

    /**
     * Return a plain text
     * @param $text
     */
    public function toPlainText($text)
    {
        http_response_code($this->status);
        header('Content-Type: text/plain');

//        $this->beforeResponse();

        echo trim($text);
    }

    /**
     * Return XML response
     *
     * @param array $data
     */
    public function toXML($data = [])
    {
        http_response_code($this->status);
        header('Content-Type: application/xml');

        $this->beforeResponse();

        echo $this->arrayToXml($data);
    }

    /**
     * Convert array to XML
     *
     * @param $array
     * @param null $rootElement
     * @param null $xml
     * @return mixed
     */
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