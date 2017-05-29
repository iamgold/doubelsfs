<?php

namespace iamgold\doublesfs;

/**
 * This is an interface of storage
 *
 * @author Eric Huang <iamgold0105@gmail.com>
 * @version 1.0.0
 */
interface StorageInterface
{
    /**
     * Put a file from source to destination
     *
     * @param string $source means source file path
     * @param string $key means a fully store file path
     * @param array $options
     * @return bool
     */
    public function put($source, $key, $options=[]);

    /**
     * Put a file by content
     *
     * @param string $content
     * @param string $key
     * @param array $options
     * @return bool
     */
    public function putContents($contents, $key, $options=[]);

    /**
     * Delete a file by specific key
     *
     * @param string $key
     * @return bool
     */
    public function delete($key);
}
