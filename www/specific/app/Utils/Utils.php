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
     * Returns the path of a random file in a storage directory
     *
     * @param string $type
     * @return string
     */
    public static function storageFile($type)
    {
        return self::getStorageDirectory($type) . DIRECTORY_SEPARATOR . self::makeKey(rand(), microtime(true));
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
        $filename = self::tempDir() . DIRECTORY_SEPARATOR . $prefix . self::makeKey($prefix, microtime(true));
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

    /**
     * Compress and encode a big array for storing in the database
     *
     * @param array $array
     * @return string
     */
    public static function compressArray(array $array)
    {
        return base64_encode(gzcompress(serialize($array), 9));
    }

    /**
     * Decode and expand a big array from the database
     *
     * @param string $string
     * @return array
     */
    public static function uncompressArray($string)
    {
        return (array)unserialize(gzuncompress(base64_decode($string)));
    }

    /**
     * Format a float number using scientific notation if needed
     *
     * @param float $number
     * @param int   $decimals
     * @param bool  $scientific
     * @return string
     */
    public static function formatDouble($number, $decimals = 4, $scientific = true)
    {
        if ($scientific && $number != 0 && abs($number) < pow(10, -$decimals)) {
            return sprintf('%.4e', $number);
        } else {
            return number_format($number, $decimals);
        }
    }
}