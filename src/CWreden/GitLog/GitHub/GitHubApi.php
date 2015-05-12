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

    /**
     * @param int $page
     * @return array|Object
     */
    public function getOwnRepositories($page = 1)
    {
        return $this->request('/user/repos', array(
            'page' => $page
        ), array(), true, true);
    }

    /**
     * @param $owner
     * @param int $page
     * @return array|Object
     */
    public function getRepositoriesForUser($owner, $page = 1)
    {
        return $this->request('/users/' . $owner . '/repos', array(
            'page' => $page
        ), array(), true, true);
    }

    /**
     * @param $owner
     * @param $repo
     * @param int $page
     * @return array|Object
     */
    public function getCommitsForRepository($owner, $repo, $page = 1)
    {
        return $this->request('/repos/' . $owner . '/' . $repo . '/commits', array(
            'page' => $page
        ), array(), true, true);
    }

    /**
     * @param $owner
     * @param $repo
     * @param int $page
     * @return array|Object
     */
    public function getGitTagsForRepository($owner, $repo, $page = 1)
    {
        return $this->request('/repos/' . $owner . '/' . $repo . '/tags', array(
            'page' => $page
        ), array(), true, true);
    }

    /**
     * TODO optimize getting commits! Maybe by parents attribute
     * @param $owner
     * @param $repo
     * @param $tag
     * @return array
     */
    public function getCommitsForTag($owner, $repo, $tag)
    {
        $tagEntries = $this->request('/repos/' . $owner . '/' . $repo . '/tags');
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

        $commitEntry = $this->request('/repos/' . $owner . '/' . $repo . '/commits/' . $tagEntry->commit->sha);
        $commitEntryFrom = null;
        if ($tagEntryFrom !== null) {
            $commitEntryFrom = $this->request('/repos/' . $owner . '/' . $repo . '/commits/' . $tagEntryFrom->commit->sha);
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
            $nextCommits = $this->request('/repos/' . $owner . '/' . $repo . '/commits', $post, array(), true, true);
            if (is_array($nextCommits) && count($nextCommits) > 0) $commits = array_merge($commits, $nextCommits);
        } while (is_array($nextCommits) && count($nextCommits) === 30);

        if ($tagEntryFrom !== null && !empty($commits)) {
            if ($tagEntryFrom->commit->sha === $commits[count($commits) -1]->sha) {
                array_pop($commits);
            }
        }

        return $commits;
    }
}
