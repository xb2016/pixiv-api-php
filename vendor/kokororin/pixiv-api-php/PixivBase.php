<?php
/**
 * pixiv-api-php
 * Pixiv API for PHP
 *
 * @package  pixiv-api-php
 * @author   Kokororin
 * @license  MIT License
 * @version  2.1
 * @link     https://github.com/kokororin/pixiv-api-php
 */

use Curl\Curl;

abstract class PixivBase
{
    /**
     * @var string
     */
    protected $oauth_url = 'https://oauth.secure.pixiv.net/auth/token';

    /**
     * @var array
     */
    protected $headers = array(
        'Accept-Language' => 'zh_CN',
    );

    /**
     * @var string
     */
    protected $oauth_client_id = 'MOBrBDS8blbauoSck0ZfDbtuzpyT';

    /**
     * @var string
     */
    protected $oauth_client_secret = 'lsACyCD94FhDUtGTXi3QzcFE2uU1hqtDaKeqrdwj';

    /**
     * @var string
     */
    protected $hash_secret = '28c1fdd170a5204386cb1313c7077b34f83e4aaf4aa829ce78c231e05b0bae2c';

    /**
     * @var null
     */
    protected $access_token = null;

    /**
     * @var null
     */
    protected $refresh_token = null;

    /**
     * @var null
     */
    protected $authorization_response = null;

    public function __construct()
    {
        if (!in_array('curl', get_loaded_extensions())) {
            throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
        }
    }

    /**
     * ログイン
     *
     * @param $refresh_token
     */
    public function login($refresh_token = null)
    {
        $local_time = date('Y-m-d') . 'T' . date('H:i:s+08:00');
        $request = array(
            'client_id' => $this->oauth_client_id,
            'client_secret' => $this->oauth_client_secret,
            'get_secure_url' => true,
            'include_policy' => true,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token ? $refresh_token : $this->refresh_token,
        );
        $curl = new Curl();
        $curl->setOpt(CURLOPT_CONNECTTIMEOUT, 10);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        $curl->setHeader('User-Agent', 'PixivAndroidApp/5.0.200 (Android 10; MI 8 UD)');
        $curl->setHeader('App-OS', 'android');
        $curl->setHeader('App-OS-Version', '10');
        $curl->setHeader('App-Version', '5.0.200');
        $curl->setHeader('X-Client-Time', $local_time);
        $curl->setHeader('X-Client-Hash', md5($local_time . $this->hash_secret));
        $curl->post($this->oauth_url, $request);
        $result = $curl->response;
        $curl->close();
        $this->setAuthorizationResponse($result);
        if (!isset($result->has_error) && isset($result->response)) {
            $this->setAccessToken($result->response->access_token);
            $this->setRefreshToken($result->response->refresh_token);
        }
    }

    /**
     * Access Token 取得する
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * Access Token セット
     *
     * @param $access_token
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
        $this->headers['Authorization'] = 'Bearer ' . $access_token;
    }

    /**
     * AuthorizationResponse 取得する
     *
     * @return string
     */
    public function getAuthorizationResponse()
    {
        return $this->authorization_response;
    }

    /**
     * AuthorizationResponse セット
     *
     * @param $authorization_response
     */
    public function setAuthorizationResponse($authorization_response)
    {
        $this->authorization_response = $authorization_response;
    }

    /**
     * ネットワーク要求
     *
     * @param $uri
     * @param $method
     * @param array $params
     * @return mixed
     */
    protected function fetch($uri, $options = array())
    {
        $method = isset($options['method']) ? strtolower($options['method']) : 'get';
        if (!in_array($method, array('post', 'get', 'put', 'delete'))) {
            throw new Exception('HTTP Method is not allowed.');
        }
        $body = isset($options['body']) ? $options['body'] : array();
        $headers = isset($options['headers']) ? $options['headers'] : array();
        $url = $this->api_prefix . $uri;
        foreach ($body as $key => $value) {
            if (is_bool($value)) {
                $body[$key] = ($value) ? 'true' : 'false';
            }
        }
        $curl = new Curl();
        $curl->setOpt(CURLOPT_CONNECTTIMEOUT, 10);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        if (is_array($headers)) {
            foreach ($headers as $key => $value) {
                $curl->setHeader($key, $value);
            }
        }
        $curl->$method($url, $body);

        $result = $curl->response;
        $curl->close();
        $array = json_decode(json_encode($result), true);

        return $array;
    }

}
