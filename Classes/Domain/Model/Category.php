<?php

namespace Petitglacon\CategoryTreebuilder\Domain\Model;

class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    protected string $title = '';
    protected Category|null $parent = null;

    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getParent(): ?Category
    {
        return $this->parent;
    }

    public function setParent(Category|null $parent): void
    {
        if (is_null($parent)) {
            $cat = new Category();
            $cat->setUid(0);
            $this->parent = $cat;
            return;
        }

        $this->parent = $parent;
    }

    public function toArray(): array
    {
        return [
            'uid' => $this->getUid(),
            'pid' => $this->getPid(),
            'title' => $this->getTitle(),
            'parent' => $this->getUid() === 0 ? -1 : $this->getParent()?->getUid() ?? 0,
        ];
    }

}