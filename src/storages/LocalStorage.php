<?php

namespace iamgold\doublesfs\storages;

use Exception;
use iamgold\doublesfs\AbstractStorage;
use iamgold\doublesfs\StorageInterface;

/**
 * This is a local storgae class.
 *
 * This class is used to handle access file that in local storage.
 *
 * @author Eric Huang <iamgold0105@gmail.com>
 * @version 1.0.1
 */
class LocalStorage extends AbstractStorage implements StorageInterface
{
    /**
     * Put a file from source to destination
     *
     * @param string $source means source file path
     * @param string $key means a fully store file path
     * @param array $options
     * @return bool
     */
    public function put($source, $key, $options=[])
    {
        try {
            $dest = $this->getRealPath($key);

            if (!is_string($source))
                throw new Exception("Invalid source path", 400);

            if (!file_exists($source))
                throw new Exception("The source file ($source) is not found", 404);

            if (!copy($source, $dest))
                throw new Exception("Copy file from $source to $key failur", 500);

            if (isset($options['deleteSource']) && $options['deleteSource']===true) {
                if (!unlink($source)) {
                    @unlink($dest);
                    throw new Exception("Can't delete source file", 500);
                }
            }

            return true;
        } catch (Exception $e) {
            $message = sprintf('[Error] %s {%s::exists}', $e->getMessage(), __CLASS__);
            throw new Exception($message, $e->getCode());
        }
    }

    /**
     * Put a file by content
     *
     * @param string $content
     * @param string $key
     * @param array $options
     * @return bool
     */
    public function putContents($contents, $key, $options=[])
    {
        try {
            $dest = $this->getRealPath($key);

            if (!is_string($contents))
                throw new Exception("Invalid contents", 400);

            $fp = fopen($dest, 'w');

            if (!fwrite($fp, $contents))
                throw new Exception("Written contents to $key failur", 500);

            fclose($fp);

            return true;
        } catch (Exception $e) {
            $message = sprintf('[Error] %s {%s::exists}', $e->getMessage(), __CLASS__);
            throw new Exception($message, $e->getCode());
        }
    }

    /**
     * Check the file exists
     *
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        try {
            $filepath = $this->getRealPath($key);

            return file_exists($filepath);
        } catch (Exception $e) {
            $message = sprintf('[Error] %s {%s::exists}', $e->getMessage(), __CLASS__);
            throw new Exception($message, $e->getCode());
        }
    }

    /**
     * Delete a file by specific key
     *
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        try {
            if (!$this->exists($key))
                return true;

            $filepath = $this->getRealPath($key);

            return unlink($filepath);
        } catch (Exception $e) {
            $message = sprintf('[Error] %s {%s::delete}', $e->getMessage(), __CLASS__);
            throw new Exception($message, $e->getCode());
        }
    }

    /**
     * Get real path by key
     *
     * @param string $key
     * @return string
     */
    public function getRealPath($key)
    {
        $this->validateKey($key);

        $realpath = sprintf('%s/%s', $this->sourcePath, $key);
        while(strpos($realpath, '//')) {
            $realpath = str_replace('//', '/', $realpath);
        }

        $dirname = dirname($realpath);
        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        }

        return $realpath;
    }

    /**
     * Validate configuration
     *
     * @param array $config
     */
    protected function validateConfig($config)
    {
        if (!isset($config['sourcePath']))
            throw new Exception("Undefined config of sourcePath.", 404);

        $sourcePath = $config['sourcePath'];

        if (!is_dir($sourcePath))
            throw new Exception("The source path is not a directory.", 400);

        if (!is_writable($sourcePath))
            throw new Exception("The source path is not writable.", 400);

        $this->sourcePath = $sourcePath;
    }

    /**
     * Validate key
     *
     * @param string $key
     * @return bool
     */
    protected function validateKey($key)
    {
        if (!is_string($key))
            throw new Exception("Invalid key type", 404);

        $key = trim($key);
        if (empty($key))
            throw new Exception("Invalid key value", 404);

        return true;
    }
}
