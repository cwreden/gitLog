<?php

namespace CWreden\GitLog;


use CWreden\GitLog\GitHub\GitHub;
use CWreden\GitLog\GitHub\GitHubApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GitLogController
{
    /**
     * @var GitHub
     */
    private $gitHub;
    /**
     * @var GitHubApi
     */
    private $gitHubApi;

    /**
     * @param GitHub $gitHub
     * @param GitHubApi $gitHubApi
     */
    function __construct(
        GitHub $gitHub,
        GitHubApi $gitHubApi
    )
    {
        $this->gitHub = $gitHub;
        $this->gitHubApi = $gitHubApi;
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        $html = '';

        if($this->gitHub->isAuthorized()) {
            $user = $this->gitHubApi->request('/user');

            $html .= '<h3>Logged In</h3>';
            $html .= '<h4>' . $user->name . '</h4>';
            $html .= '<pre>';
            $html .= print_r($user, true);
            $html .= '</pre>';

        } else {
            $html .= '<h3>Not logged in</h3>';
            $html .= '<p><a href="/sign/in">Log In</a></p>';
        }

//            $html .= '<pre>' . print_r($request->getSession()->all(), true) . '</pre>';
        return new Response($html);
    }

    /**
     * @return JsonResponse
     */
    public function reposAction()
    {
        $repositories = $this->gitHubApi->request('/user/repos', array(), array());

        $data = array();
        foreach ($repositories as $repository) {
            $data[] = array(
                'id' => $repository->id,
                'name' => $repository->name,
                'full_name' => $repository->full_name,
                'description' => $repository->description,
                'updated_at' => $repository->updated_at,
                'pushed_at' => $repository->pushed_at,
                'tags_url' => 'http://git-log.org/repos/' . $repository->full_name . '/tags'
            );
        }

        return new JsonResponse($data);
    }

    /**
     * @param $user
     * @param $repo
     * @return JsonResponse
     */
    public function tagsAction($user, $repo)
    {
        $tags = $this->gitHubApi->request('/repos/' . $user . '/' . $repo . '/tags', array(), array());

        $data = array();
        foreach ($tags as $tag) {
            $data[] = array(
                'name' => $tag->name,
                'commit' => $tag->commit,
                'change_log_url' => 'http://git-log.org/repos/' . $user . '/' . $repo . '/changelog/' . $tag->name
            );
        }

        return new JsonResponse($data);
    }

    /**
     * @param $user
     * @param $repo
     * @param $tag
     * @return JsonResponse
     */
    public function changeLogAction($user, $repo, $tag)
    {
        $tagEntries = $this->gitHubApi->request('/repos/' . $user . '/' . $repo . '/tags');
        $tagEntry = null;
        $tagEntryFrom = null;
        for ($i = 0; $i < count($tagEntries); $i++) {
            if ($tagEntries[$i]->name === $tag) {
                $tagEntry = $tagEntries[$i];
                if (isset($tagEntries[$i + 1])) {
                    $tagEntryFrom = $tagEntries[$i + 1];
                }
                break;
            }
        }

        return new JsonResponse(array(
            'tag' => $tag,
            'changeLogTag' => $tagEntry,
            'tagBefore' => $tagEntryFrom
        ));
    }
}
