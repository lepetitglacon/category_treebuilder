<?php

namespace Petitglacon\CategoryTreebuilder\Manager;

use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Resource\DuplicationBehavior;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Filter\FileExtensionFilter;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class FileManager
{
    public const BASE_EXT_NAME = 'category_treebuilder';

    protected const DIRECTORY_FOLDER_PATH = 'user_upload/category_treebuilder/';
    protected const FOLDER_PATH = 'fileadmin/user_upload/category_treebuilder/';
    protected const CONFIG_FOLDER_PATH = 'Resources/Private/Configuration/';

    protected const FILTER_EXTENSION = 'json,csv';

    /**
     * @var array
     */
    protected $directories;

    /**
     * FileStorage
     *
     * @var ResourceStorage
     */
    protected $fileStorage;

    /**
     * FileManager constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!ExtensionManagementUtility::isLoaded(self::BASE_EXT_NAME)) {
            throw new \Exception(sprintf('Extension %s not loaded', self::BASE_EXT_NAME));
        }

        $this->initializeFileStorage();
    }

    /**
     *
     */
    protected function initializeFileStorage(): void
    {
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        $this->fileStorage = $resourceFactory->getDefaultStorage();

        if (!$this->fileStorage->hasFolder(self::DIRECTORY_FOLDER_PATH)) {
            $this->fileStorage->createFolder(self::DIRECTORY_FOLDER_PATH);
        }

//        // create filter
//        $storageFilter = GeneralUtility::makeInstance(FileExtensionFilter::class);
//        $storageFilter->setAllowedFileExtensions(self::FILTER_EXTENSION);
//        $this->fileStorage->setFileAndFolderNameFilters([
//            [
//                $storageFilter,
//                'filterFileList',
//            ]
//        ]);
    }

    /**
     * @return array
     * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFolderException
     * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
     * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderWritePermissionsException
     */
    public function loadDirectories(): array
    {
        // create folder if it does not exists yet
        if (!$this->fileStorage->hasFolder(self::DIRECTORY_FOLDER_PATH)) {
            $this->fileStorage->createFolder(self::DIRECTORY_FOLDER_PATH);
        }

        // get storage folder
        $folder = $this->fileStorage->getFolder(self::DIRECTORY_FOLDER_PATH);

        // load files
        $this->directories = array_map(function ($file) {
            return new DirectoryFile($file);
        }, $this->fileStorage->getFilesInFolder($folder));

        // load files and store them in $directories
        return $this->directories;
    }

    public function loadDirectory(string $systemName): ?DirectoryFile
    {
        $files = $this->loadDirectories();
        foreach ($files as $file) {
            if ($systemName === $file->getSystemName()) {
                return $file;
            }
        }
        return null;
    }

    /**
     * @param string $filename
     * @return array
     * @throws \Exception
     */
    private function loadConfigFile(string $filename): array
    {
        $identifier = ExtensionManagementUtility::extPath(self::BASE_EXT_NAME) . self::CONFIG_FOLDER_PATH . $filename;

        try {
            $content = file_get_contents($identifier);
            $json = json_decode($content, true);

            foreach ($json as &$item) {
                // concat template lines into 1 single string
                if (isset($item['template']) && is_array($item['template'])) {
                    $item['template'] = implode('', $item['template']);
                }
            }

            return $json;
        } catch (\Exception $ex) {
            throw new \Exception(sprintf("An error occured while loading config file: %s", $ex->getMessage()));
        }

        return [];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function loadFieldTypes(): array
    {
        return $this->loadConfigFile(self::FIELD_TYPES_FILENAME);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function loadDefaultFields(): array
    {
        return $this->loadConfigFile(self::DEFAULT_FIELDS_FILENAME);
    }

    /**
     * @param array $formData
     * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
     */
    public function saveCSV($tree)
    {
        $date = new \DateTime('now');
        $filename = $date->format('d_m_Y_H-i-s') . '---' . count($tree) . '.csv';

        $folder = $this->fileStorage->getFolder(self::DIRECTORY_FOLDER_PATH);
        $filepath = GeneralUtility::getFileAbsFileName(self::FOLDER_PATH) . $filename;

        $content = [
            ['uid', 'pid', 'parent', 'title']
        ];

        foreach ($tree as $category) {
            $content[] = $category;
        }

        try {
            $file = fopen($filepath, 'w');
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($file, "file");
            foreach ($content as $row) {
                fputcsv($file, $row);
            }
        } catch (Exception $e) {
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($e->getMessage(), "error");
            exit;
        }


        // get storage folder

//        $this->createFile($filepath, $content);
    }

    /**
     * @param $filepath
     * @param $content
     * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
     * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderReadPermissionsException
     */
    protected function createFile($filepath, $content)
    {
        $folder = $this->fileStorage->getFolder(self::DIRECTORY_FOLDER_PATH);
        $absoluteFilepath = GeneralUtility::getFileAbsFileName($filepath);
        $fileName = basename($filepath);

        GeneralUtility::writeFile($absoluteFilepath, $content);

        if (!$folder->hasFile($fileName)) {
            $folder->addFile($absoluteFilepath);
        }
    }
}
