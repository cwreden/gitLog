<?php

namespace CWreden\GitLog\Entity;


/**
 * Class ChangeLogEntry
 * @package CWreden\GitLog\Entity
 * @Entity(repositoryClass="CWreden\GitLog\ChangeLogEntryRepository")
 */
class ChangeLogEntry
{
    /**
     * @var integer
     * @ID
     * @GeneratedVaule(strategy="AUTO")
     * @Column(type="integer")
     */
    private $id;
    /**
     * @var string
     * @Column(type="string")
     */
    private $notice = null;
    /**
     * @var bool
     * @Column(type="boolean", nullable=false)
     */
    private $ignore = false;
    /**
     * @var string
     * @Column(type="string", nullable=false)
     */
    private $commitHash;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNotice()
    {
        return $this->notice;
    }

    /**
     * @param string $notice
     */
    public function setNotice($notice)
    {
        $this->notice = $notice;
    }

    /**
     * @return boolean
     */
    public function isIgnore()
    {
        return $this->ignore;
    }

    /**
     * @param boolean $ignore
     */
    public function setIgnore($ignore)
    {
        $this->ignore = $ignore;
    }

    /**
     * @return string
     */
    public function getCommitHash()
    {
        return $this->commitHash;
    }

    /**
     * @param string $commitHash
     */
    public function setCommitHash($commitHash)
    {
        $this->commitHash = $commitHash;
    }
}
