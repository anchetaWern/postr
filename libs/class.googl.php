<?php
/**
 * Class for interacting with Goo.gl service
 *
 * @author Rafal Kukawski <rafael@webhelp.pl>
 * @license http://kukawski.pl/mit-license.txt MIT Lincense
 * @link http://webhelp.pl/artykuly/korzystanie-z-goo-gl-api/
 * @version 0.9
 */
class Googl {
    /**
     * URL to the Goo.gl service
     *
     * @static
     */
    const GOOGL_URL = 'https://www.googleapis.com/urlshortener/v1/url';

    /**
     * Pass it to expandShortcut to ignore statistics assigned to shortcut
     *
     * @static
     * @see expandShortcut()
     */
    const ANALYTICS_NONE = '';

    /**
     * Pass it to expandShortcut to fetch click counters
     *
     * @static
     * @see expandShortcut()
     */
    const ANALYTICS_CLICKS = 'ANALYTICS_CLICKS';

    /**
     * Pass it to expandShortcut to fetch counters for various criteria
     *
     * @static
     * @see expandShortcut()
     */
    const ANALYTICS_TOP_STRINGS = 'ANALYTICS_TOP_STRINGS';

    /**
     * Pass it to expandShortcut to fetch all available statistics data
     *
     * @static
     * @see expandShortcut()
     */
    const ANALYTICS_FULL = 'FULL';

    /**
     * Key assigned to the user using the Goo.gl service
     * 
     * @var string
     */
    private $apiKey;

    public function  __construct ($apiKey = null) {
        if (is_string($apiKey)) {
            $this->apiKey = $apiKey;
        }
    }

    /**
     * Creates a shortcut to the URL
     * 
     * @param string $url URL that should be shortened
     *
     * @return string The shortcut URL
     *
     * @throws InvalidArgumentException
     *      when the param is not a valid HTTP(S) URL
     *
     * @throws GooglNetworkException
     *      when a network problem occured while creating the shortcut
     * 
     * @throws GooglServiceException
     *      when Goo.gl sevice returned an error
     */
    public function createShortcut ($url) {
        if (!$this->isUrl($url)) {
            throw new InvalidArgumentException("Valid HTTP or HTTPS URL expected");
        }

        $googlUrl = self::GOOGL_URL;
        
        if ($this->apiKey !== null) {
            $googlUrl .= '?key=' . $this->apiKey;
        }
        
        $content = array('longUrl' => $url);
        $headers = array('Content-type: application/json', 'Accept: application/json');

        $result = $this->makeJsonRequest($googlUrl, 'POST', $content, $headers);

        return $result['id'];
    }

    /**
     * Expands the shortcut to full URL and optionally gets statistics data
     * connected with the shortcut.
     *
     * @param string $url URL that should be shortened
     * @param string $includeAnalytics Type of analytics data to fetch
     *
     * @return array Array containing the full URL and statistics
     *
     * @throws GooglNetworkException
     *      when a network problem occured while creating the shortcut
     *
     * @throws GooglServiceException
     *      when Goo.gl sevice returned an error
     *
     * @see Googl::ANALYTICS_NONE
     * @see Googl::ANALYTICS_FULL
     * @see Googl::ANALYTICS_CLICKS
     * @see Googl::ANALYTICS_TOP_STRINGS
     */
    public function expandShortcut ($shortcut, $includeAnalytics = '') {
        if (!$this->isUrl($shortcut)) {
            throw new InvalidArgumentException("Valid HTTP or HTTPS URL expected");
        }
        
        $googlUrl = self::GOOGL_URL . '?shortUrl=' . urlencode($shortcut);

        if ($this->apiKey !== null) {
            $googlUrl .= '&key=' . $this->apiKey;
        }

        if (in_array($includeAnalytics, array(self::ANALYTICS_FULL, self::ANALYTICS_CLICKS, self::ANALYTICS_TOP_STRINGS))) {
            $googlUrl .= '&projection=' . $includeAnalytics;
        }

        $headers = array('Accept: application/json');

        $result = $this->makeJsonRequest($googlUrl, 'GET', null, $headers);

        return $result;
    }

    /**
     * Checks if the argument is correct HTTP(S) URL
     *
     * @param string $url URL to validate
     *
     * @return bool
     */
    public function isUrl ($url) {
        // TODO: improve HTTP URL validation, because filter_var has some limitations, especially it lacks support for non-ASCII characters
        return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED) == $url && preg_match('/^https?$/i', parse_url($url, PHP_URL_SCHEME));
    }

    /**
     * Gets data from given service and parses it.
     *
     * @param string $url URL of the resource to query
     * @param string $method Request method - GET or POST
     * @param mixed $content Content to be sent to be service
     * @param array $headers Additional HTTP headers to be sent
     *
     * @return array
     *
     * @throws GooglNetworkException
     *          when data wasn't valid JSON
     *
     * @throws GooglServiceException
     *          when Goo.gl service response contains error key
     * @see sendRequest()
     */
    protected function makeJsonRequest ($url, $method = 'GET', $content = null, $headers = null) {
        list($rawResponse, $httpCode) = $this->sendRequest($url, $method, json_encode($content), $headers);

        $data = json_decode($rawResponse, true);

        // if response is not valid json, assume it's a network issue
        if ($data === null) {
            throw new GooglNetworkException();
        } else if (isset($data['error']) || $httpCode !== 200) {
            throw new GooglServiceException($data);
        } else {
            return $data;
        }
    }

    /**
     * Makes a HTTP request to a URL and gets the result
     *
     * @param string $url URL to query
     * @param string $method HTTP request method. GET and POST supported.
     * @param mixed $body Content to be sent with the request.
     *                      Can be string or array with key-value pairs
     * @param array $headers Additional headers to be sent with the request
     *                      e.g. array('Content-Type: application/json')
     * @return array
     *          Response content and HTTP response code
     * 
     * @throws GooglNetworkException
     *          when HTTP request failed
     */
    protected function sendRequest ($url, $method = 'GET', $body = null, $headers = null) {
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => $method === 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLVERSION => 3,
            CURLOPT_HEADER => false
        );

        if (is_array($headers)) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        if ($options[CURLOPT_POST] && (is_string($body) || is_array($body))) {
            $options[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        unset($ch);

        if ($response === false) {
            throw new GooglNetworkException($error);
        }

        return array($response, $httpCode);
    }
}

/**
 * Exception thrown when Goo.gl service responds with error message
 */
class GooglServiceException extends Exception {
    private $data;

    public function  __construct($data = null) {
        $this->data = $data;

        parent::__construct($data['error']['message']);
    }

    public function getData () {
        return $this->data;
    }
}

/**
 * Exception thrown when request failed due to a network error
 */
class GooglNetworkException extends Exception {}
?>