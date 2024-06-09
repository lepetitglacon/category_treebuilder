<?php

namespace Petitglacon\CategoryTreebuilder\Domain\Model;

class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    protected string $title = '';
    protected Category|null $parent = null;

//    public function _setProperty(string $propertyName, mixed $propertyValue): bool
//    {
//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($propertyName, '$propertyName');
//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($propertyValue, '$propertyValue');
//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this, 'this');
//        if ($propertyName === 'parent') {
//            if ($propertyValue == 0) {
//                return parent::_setProperty($propertyName, new Category());
//            }
//        }
//        return parent::_setProperty($propertyName, $propertyValue);
//    }


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