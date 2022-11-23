<?php

namespace Petitglacon\CategoryTreebuilder\Object;

class Category
{
    /**
     * @var int $parent
     */
    protected $uid;

    /**
     * @var int $parent
     */
    protected $pid;

    /**
     * @var int $parent
     */
    protected $parent;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var bool $updated
     */
    protected $updated;

    /**
     * @param $uid
     * @param $pid
     * @param $parent
     * @param $title
     */
    public function __construct($uid, $pid, $parent, $title, $updated = false)
    {
        $this->uid = $uid;
        $this->pid = $pid;
        $this->parent = $parent;
        $this->title = $title;
        $this->updated = $updated;
    }

    /**
     * @return int
     */
    public function getUid(): int
    {
        return $this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @return int
     */
    public function getPid(): int
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     */
    public function setPid(int $pid): void
    {
        $this->pid = $pid;
    }

    /**
     * @return int
     */
    public function getParent(): int
    {
        return $this->parent;
    }

    /**
     * @param int $parent
     */
    public function setParent(int $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return bool
     */
    public function isUpdated(): bool
    {
        return $this->updated;
    }

    /**
     * @param bool $updated
     */
    public function setUpdated(bool $updated): void
    {
        $this->updated = $updated;
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