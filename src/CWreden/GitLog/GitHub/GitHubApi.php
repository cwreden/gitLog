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
     * @return array|Object
     */
    public function request(
        $api,
        array $post = array(),
        array $headers = array(),
        $asObject = true
    ) {
        $ch = curl_init(self::BASE_URL . $api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if(is_array(array()) && !empty($post)) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

        $headers[] = 'Accept: application/json';

        $headers[] = 'User-Agent: ' . $this->appName;
        $headers[] = 'Authorization: ' . $this->session->get('token_type') . ' ' . $this->session->get('access_token');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        return json_decode($response, !$asObject);
    }

}
