<?php

namespace Petitglacon\CategorytreeBuilder\Object;

class Category
{
    protected $uid;
    protected $pid;
    protected $parent;
    protected $title;

    /**
     * @param $uid
     * @param $pid
     * @param $parent
     * @param $title
     */
    public function __construct($uid, $pid, $parent, $title)
    {
        $this->uid = $uid;
        $this->pid = $pid;
        $this->parent = $parent;
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param mixed $pid
     */
    public function setPid($pid): void
    {
        $this->pid = $pid;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function toArray() {
        return [
            'uid' => $this->uid,
            'pid' => $this->pid,
            'parent' => $this->parent,
            'title' => $this->title
        ];
    }


}