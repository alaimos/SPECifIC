<?php

namespace App\Utils;


use App\Exceptions\CommandException;

final class Utils
{
    /**
     * Delete a file or a directory
     *
     * @param string $path something to delete
     * @return bool
     */
    public static function delete($path)
    {
        if (!file_exists($path)) {
            return false;
        }
        if (is_file($path)) {
            return unlink($path);
        } elseif (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                static::delete($path . DIRECTORY_SEPARATOR . $file);
            }
            return rmdir($path);
        }
        return false;
    }

    /**
     * Create a directory and set chmod
     *
     * @param string $directory
     * @return void
     */
    public static function createDirectory($directory)
    {
        if (!file_exists($directory)) {
            @mkdir($directory, 0777, true);
            @chmod($directory, 0777);
        }
    }

    /**
     * Returns the path of a storage directory
     *
     * @param string $type
     * @return string
     */
    public static function getStorageDirectory($type)
    {
        $path = storage_path('app/' . $type);
        if (!file_exists($path)) {
            static::createDirectory($path);
        }
        return $path;
    }

    /**
     * Returns the path of the temporary files directory
     *
     * @return string
     */
    public static function tempDir()
    {
        $dirName = storage_path('tmp');
        if (!file_exists($dirName)) {
            static::createDirectory($dirName);
        }
        return $dirName;
    }

    /**
     * Return the path of a temporary file name in the temporary files directory
     *
     * @param string $prefix
     * @param string $extension
     * @return string
     */
    public static function tempFile($prefix = '', $extension = '')
    {
        $filename = self::tempDir() . DIRECTORY_SEPARATOR . $prefix . self::makeKey($prefix . microtime(true));
        if (!empty($extension)) {
            $filename .= '.' . ltrim($extension, '.');
        }
        return $filename;
    }

    /**
     * Runs a shell command and checks for successful completion of execution
     *
     * @param string     $command
     * @param array|null $output
     * @return boolean
     */
    public static function runCommand($command, array &$output = null)
    {
        $returnCode = -1;
        exec($command, $output, $returnCode);
        if ($returnCode != 0) {
            throw new CommandException($returnCode);
        }
        return true;
    }

    /**
     * Generate an unique key starting from a set of objects
     *
     * @param mixed ...
     * @return string
     */
    public static function makeKey(/*...*/)
    {
        $objects = array_filter(array_map(function ($e) {
            if (is_object($e)) {
                if (method_exists($e, 'getKey')) {
                    return $e->getKey();
                } else {
                    return (string)$e;
                }
            } elseif (is_array($e)) {
                return implode(',', $e);
            } elseif (is_resource($e)) {
                return null;
            } else {
                return $e;
            }
        }, func_get_args()), function ($e) {
            return $e !== null;
        });
        return md5(implode('-', $objects));
    }
}