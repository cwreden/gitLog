<?php

namespace CWreden\GitLog\GitHub;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GitHubApi
{
    const BASE_URL = 'https://api.github.com';
    /**
     * @var
     */
    private $session;
    /**
     * @var string
     */
    private $appName;

    /**
     * @param SessionInterface $session
     * @param string $appName
     */
    function __construct(
        SessionInterface $session,
        $appName
    )
    {
        $this->session = $session;
        $this->appName = $appName;
    }

    /**
     * @param $api
     * @param array $post
     * @param array $headers
     * @param bool $asObject
     * @param bool $asGet
     * @return array|Object
     */
    public function request(
        $api,
        array $post = array(),
        array $headers = array(),
        $asObject = true,
        $asGet = false
    ) {
        $parameters = http_build_query($post);

        $url = self::BASE_URL . $api;
        if ($asGet) {
            $url .= '?' . $parameters;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if(!$asGet && is_array(array()) && !empty($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        }

        $headers[] = 'Accept: application/json';

        $headers[] = 'User-Agent: ' . $this->appName;
        $headers[] = 'Authorization: ' . $this->session->get('token_type') . ' ' . $this->session->get('access_token');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, !$asObject);
    }

}
