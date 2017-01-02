<?php

namespace App\Utils;


class Utils
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
                self::delete($path . DIRECTORY_SEPARATOR . $file);
            }
            return rmdir($path);
        }
        return false;
    }
}