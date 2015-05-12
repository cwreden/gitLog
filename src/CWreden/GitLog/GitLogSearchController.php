<?php

namespace CWreden\GitLog;


use CWreden\GitLog\GitHub\GitHubApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GitLogSearchController
{
    /**
     * @var GitHubApi
     */
    private $gitHubApi;

    /**
     * @param GitHubApi $gitHubApi
     */
    function __construct(
        GitHubApi $gitHubApi
    )
    {
        $this->gitHubApi = $gitHubApi;
    }

    /**
     * @param Request $request
     * @return array|Object
     */
    public function searchRepositories(Request $request)
    {
        $result = $this->gitHubApi->request('/search/repositories', array(
            'q' => $request->query->get('q')
        ), array(), true, true);

        return new JsonResponse($result);
    }
}
