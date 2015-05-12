<?php

namespace CWreden\GitLog;


use CWreden\GitLog\GitHub\GitHub;
use CWreden\GitLog\GitHub\GitHubApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
            return new JsonResponse(array(
                'repos' => 'http://git-log.org/repos',
                'user' => $user
            ));

        } else {
            $html .= '<h3>Not logged in</h3>';
            $html .= '<p><a href="/sign/in">Log In</a></p>';
        }

//            $html .= '<pre>' . print_r($request->getSession()->all(), true) . '</pre>';
        return new Response($html);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getOwnRepoListAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $repositories = $this->gitHubApi->request('/user/repos', array(
            'page' => $page
        ), array(), true, true);

        $data = array();
        foreach ($repositories as $repository) {
            $data[] = array(
                'id' => $repository->id,
                'name' => $repository->name,
                'full_name' => $repository->full_name,
                'description' => $repository->description,
                'updated_at' => $repository->updated_at,
                'pushed_at' => $repository->pushed_at,
                'commits_url' => 'http://git-log.org/repos/' . $repository->full_name . '/commits',
                'tags_url' => 'http://git-log.org/repos/' . $repository->full_name . '/tags'
            );
        }

        return new JsonResponse($data);
    }

    public function getCommitListAction($owner, $repo, Request $request)
    {

        $page = $request->query->get('page', 1);
        $commits = $this->gitHubApi->request('/repos/' . $owner . '/' . $repo . '/commits', array(
            'page' => $page
        ), array(), true, true);

        $data = array();
        foreach ($commits as $commit) {
            $data[] = array(
                'sha' => $commit->sha,
                'message' => $commit->commit->message,
                'ignored' => false,
                'originalMessage' => $commit->commit->message,
                'type' => 'general'
            );
        }

        return new JsonResponse($data);
    }

    /**
     * @param $owner
     * @param $repo
     * @param Request $request
     * @return JsonResponse
     */
    public function getTagListAction($owner, $repo, Request $request)
    {
        $page = $request->query->get('page', 1);
        $tags = $this->gitHubApi->request('/repos/' . $owner . '/' . $repo . '/tags', array(
            'page' => $page
        ), array(), true, true);

        $data = array();
        foreach ($tags as $tag) {
            $data[] = array(
                'name' => $tag->name,
                'commit' => $tag->commit,
                'commits_url' => 'http://git-log.org/repos/' . $owner . '/' . $repo . '/tags/' . $tag->name . '/commits',
                'change_log_url' => 'http://git-log.org/changelog/' . $owner . '/' . $repo . '/' . $tag->name
            );
        }

        return new JsonResponse($data);
    }

    /**
     * TODO optimize getting commits! Maybe by parents attribute
     * @param $owner
     * @param $repo
     * @param $tag
     * @return JsonResponse
     */
    public function getCommitListForTagAction($owner, $repo, $tag)
    {
        $tagEntries = $this->gitHubApi->request('/repos/' . $owner . '/' . $repo . '/tags');
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

        $commitEntry = $this->gitHubApi->request('/repos/' . $owner . '/' . $repo . '/commits/' . $tagEntry->commit->sha);
        $commitEntryFrom = null;
        if ($tagEntryFrom !== null) {
            $commitEntryFrom = $this->gitHubApi->request('/repos/' . $owner . '/' . $repo . '/commits/' . $tagEntryFrom->commit->sha);
        }

        $post = array(
            'page' => 0,
            'until' => $commitEntry->commit->committer->date
        );
        if ($commitEntryFrom !== null) {
            $post['since'] = $commitEntryFrom->commit->committer->date;
        }
        $commits = array();
        do {
            $nextCommits = null;
            $post['page']++;
            $nextCommits = $this->gitHubApi->request('/repos/' . $owner . '/' . $repo . '/commits', $post, array(), true, true);
            if (is_array($nextCommits) && count($nextCommits) > 0) $commits = array_merge($commits, $nextCommits);
        } while (is_array($nextCommits) && count($nextCommits) === 30);

        if ($tagEntryFrom !== null && !empty($commits)) {
            if ($tagEntryFrom->commit->sha === $commits[count($commits) -1]->sha) {
                array_pop($commits);
            }
        }

        $data = array();
        foreach ($commits as $commit) {
            $data[] = array(
                'sha' => $commit->sha,
                'message' => $commit->commit->message
            );
        }


        return new JsonResponse(array(
            'commits' => $data
        ));
    }

    /**
     * @param $owner
     * @param $repo
     * @param $tag
     * @param Request $request
     * @return Response
     */
    public function getChangeLogAction($owner, $repo, $tag, Request $request)
    {
        $format = $request->query->get('format', 'json');
        // TODO not implemented!
        return new Response();
    }
}
