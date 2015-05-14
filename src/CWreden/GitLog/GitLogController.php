<?php

namespace CWreden\GitLog;


use CWreden\GitLog\GitHub\GitHub;
use CWreden\GitLog\GitHub\GitHubApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

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
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @param GitHub $gitHub
     * @param GitHubApi $gitHubApi
     * @param Twig_Environment $twig
     */
    function __construct(
        GitHub $gitHub,
        GitHubApi $gitHubApi,
        Twig_Environment $twig
    )
    {
        $this->gitHub = $gitHub;
        $this->gitHubApi = $gitHubApi;
        $this->twig = $twig;
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
        return $this->twig->render('index.html', array(
            'navigation' => array(
                array(
                    'href' => '/repos',
                    'caption' => 'Repositories'
                )
            )
        ));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getOwnRepoListAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $repositories = $this->gitHubApi->getOwnRepositories($page);

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

    /**
     * @param Request $request
     * @param $owner
     * @return JsonResponse
     */
    public function getRepoListAction(Request $request, $owner)
    {
        $page = $request->query->get('page', 1);
        $repositories = $this->gitHubApi->getRepositoriesForUser($owner, $page);

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

    /**
     * @param $owner
     * @param $repo
     * @param Request $request
     * @return JsonResponse
     */
    public function getCommitListAction($owner, $repo, Request $request)
    {

        $page = $request->query->get('page', 1);
        $commits = $this->gitHubApi->getCommitsForRepository($owner, $repo, $page);

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
        $tags = $this->gitHubApi->getGitTagsForRepository($owner, $repo, $page);

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
     * @param $owner
     * @param $repo
     * @param $tag
     * @return JsonResponse
     */
    public function getCommitListForTagAction($owner, $repo, $tag)
    {
        $commits = $this->gitHubApi->getCommitsForTag($owner, $repo, $tag);
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
