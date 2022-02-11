<?php

namespace ana\searchEngine;

use Exception;
use stdClass;

class SearchEngine
{
    /**
     * Custom search engine ID
     *
     * @var string
     */
    protected $engineId;

    /**
     * Google console API key
     *
     * @var string
     */
    protected $apiKey;


    protected $originalResponse;


    protected $resultOutput;


    public function __construct() {
        $this->engineId = config('searchengine.google_search_engine_id');//"c3ffd216a31ac43f6";//
        $this->apiKey = config('searchengine.google_search_api_key');//"AIzaSyCKRwDaPRElV5IisV3fxpOvutVj0xb7f3E";//
    }


    public function setEngineId($engineId) {
        $this->engineId = $engineId;
    }

    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getResults($phrase, $parameters = ['num'=>5,'start'=>5])
    {
        /**
         * Check required parameters
         */
        if ($phrase == '') {
            return [];
        }

        if ($this->engineId == '') {
            throw new Exception('You must specify a engineId');
        }

        if ($this->apiKey == '') {
            throw new Exception('You must specify a apiKey');
        }

        /**
         * Create search aray
         */
        $searchArray = http_build_query(
            array_merge(
                ['key' => $this->apiKey],
                ['q' => $phrase],
                $parameters
            )
        );

        /**
         * Add unencoded search engine id
         */
        $searchArray = '?cx=' . $this->engineId . '&' . $searchArray . '&top=0';
        // die("https://www.googleapis.com/customsearch/v1/siterestrict" . $searchArray);
        /**
         * Prepare Curl and get result
         */
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/customsearch/v1/siterestrict" . $searchArray);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 0);

        $output = curl_exec($ch);

        $info = curl_getinfo($ch);

        /**
         * Check HTTP code of the result
         */
        if ($output === false || $info['http_code'] != 200) {
            throw new Exception("No data returned, code [". $info['http_code']. "] - " . curl_error($ch));
        }

        curl_close($ch);
        // die(json_decode($this->getSearchInformation()));
        // die(var_dump(json_decode($output)->items));

        /**
         * Convert JSON format to object and save
         */

        $this->originalResponse = json_decode($output);
        
        // die(var_dump($phrase));
        /**
         * If there are some results, return them, otherwise return blank array
         */
        if (isset($this->originalResponse->items)) {
            return $this->getOutput();
        } else {
            return array();
        }
    }

    /**
     * Get full original response
     *
     * Gets full originated response converted from JSON to StdClass
     * Full list of parameters is located at
     * complete list of parameters with description is located at
     * https://developers.google.com/custom-search/json-api/v1/reference/cse/list#response
     *
     * 
     * @url https://developers.google.com/custom-search/json-api/v1/reference/cse/list#response
     */
    public function getRawResult() {
        return $this->originalResponse->items;
    }



    /**
     * Get Output Result Contains url and title , description , keyword
     *
     * @return Array
     */
    public function getOutput() {
        // die(var_dump($this->originalResponse->items));
        $i=0;
        foreach($this->originalResponse->items as $val ){
            $this->resultOutput[$i]['title'] = $val->title;
            $this->resultOutput[$i]['url'] = $val->displayLink;
            $this->resultOutput[$i]['description'] = $val->snippet;
            $this->resultOutput[$i]['keywords'] = $this->originalResponse->queries->previousPage[0]->searchTerms;
            $this->resultOutput[$i]['promoted'] = true;//str_contains('ads',$val->title)
            $i++;
        }

        return $this->resultOutput;
    }

}