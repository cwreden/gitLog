<?php

namespace CWreden\GitLog\GitHub;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GitHub
{
    const BASE_URL = 'https://github.com';
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var string
     */
    private $oAuthClientId;
    /**
     * @var string
     */
    private $oAuthClientSecret;
    private $redirectUri;

    /**
     * @param SessionInterface $session
     * @param $oAuthClientId
     * @param $oAuthClientSecret
     * @param $redirectUri
     */
    function __construct(
        SessionInterface $session,
        $oAuthClientId,
        $oAuthClientSecret,
        $redirectUri
    )
    {
        $this->session = $session;
        $this->oAuthClientId = $oAuthClientId;
        $this->oAuthClientSecret = $oAuthClientSecret;
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return bool
     */
    public function isAuthorized()
    {
        return $this->session->has('access_token');
    }

    /**
     * @param $code
     * @return array|Object
     */
    public function getAccessToken($code)
    {
        $post = array(
            'client_id' => $this->oAuthClientId,
            'client_secret' => $this->oAuthClientSecret,
            'redirect_uri' => 'http://' . $_SERVER['SERVER_NAME'] . '/authorize/github',
            'code' => $code
        );
        return $this->request('/login/oauth/access_token', $post);
    }

    /**
     * @param $uri
     * @param array $post
     * @param array $headers
     * @param bool $asObject
     * @return array|Object
     */
    public function request(
        $uri,
        array $post = array(),
        array $headers = array(),
        $asObject = true
    ) {
        $ch = curl_init(self::BASE_URL . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if(is_array(array())) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

        $headers[] = 'Accept: application/json';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        return json_decode($response, !$asObject);
    }

}
