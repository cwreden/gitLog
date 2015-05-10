<?php

namespace CWreden\GitLog\GitHub;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OAuthController
 * @package CWreden\GitLog\GitHub
 */
class OAuthController
{
    private $oAuthClientId;
    /**
     * @var GitHub
     */
    private $gitHub;

    /**
     * @param $oAuthClientId
     * @param GitHub $gitHub
     */
    function __construct(
        $oAuthClientId,
        GitHub $gitHub
    )
    {
        $this->oAuthClientId = $oAuthClientId;
        $this->gitHub = $gitHub;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function signInAction(Request $request)
    {
        $session = $request->getSession();
        $state = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);
        $session->set('state', $state);
        $session->remove('access_token');

        $params = array(
            'client_id' => $this->oAuthClientId,
            'redirect_uri' => 'http://' . $_SERVER['SERVER_NAME'] . '/authorize/github',
            'scope' => 'user',
            'state' => $state
        );
        return new RedirectResponse(GitHub::BASE_URL . '/login/oauth/authorize?' . http_build_query($params));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function authorizeAction(Request $request)
    {
        $session = $request->getSession();
        if (!$request->query->has('state') || $session->get('state') !== $request->query->get('state')) {
            return new RedirectResponse('http://' . $_SERVER['SERVER_NAME']);
        }

        if ($request->query->has('code')) {
            $tokenData = $this->gitHub->getAccessToken($request->query->get('code'), $session->get('state'));

            $session->set('access_token', $tokenData->access_token);
            $session->set('token_type', $tokenData->token_type);
            $session->set('scope', $tokenData->scope);
        } elseif ($request->query->has('access_token')) {
            // TODO if maybe never used
            $session->set('access_token', $request->query->get('access_token'));
            $session->set('token_type', $request->query->get('token_type'));
            $session->set('scope', $request->query->get('scope'));
        }

        return new RedirectResponse('http://' . $_SERVER['SERVER_NAME']);
    }
}
