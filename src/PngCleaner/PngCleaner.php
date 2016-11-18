<?php
namespace PngCleaner;

use PngCleaner\Exception\PngCleanerException;

class PngCleaner
{
    private static $_allowedTypes = [
        'mkBF',
        'mkBS',
        'mkBT',
        'mkTS'
    ];

    /**
     * Strict provided private chunks from png file
     *
     * @param $srcFile src png file
     * @param $destFile destination png file
     * @param array $types chunk types to remove from png
     *
     * @return bool
     */
    public static function clean($srcFile, $destFile, array $types)
    {
        if (!empty(array_diff($types, self::$_allowedTypes))) {
            throw new PngCleanerException('Provide types don\'t allowed');
        }
        //check and open source png file
        if (!file_exists($srcFile)) {
            throw new PngCleanerException('Source file doesn\' exist');
        }

        $sf = fopen($srcFile,  "rb");

        //check and create dest png file
        try {
            $df = fopen($destFile, "w");
        }
        catch(\Exception $ex) {
            throw new PngCleanerException('Destination file can\' be created');
        }

        //read header from PNG (8-byte length)
        $header = fread($sf, 8);

        //write PNG header to destination png file
        fwrite($df, $header);

        //check file header
        if ($header != "\x89PNG\x0d\x0a\x1a\x0a")
            throw new PngCleanerException('PNG file isn\'t valid');

        //read first chunk (IHDR)
        $chunkHeader = fread($sf, 8);

        while ($chunkHeader) {
            /**
             * All PNG chunks has next format :
             * header - 8byte length
             * body - N byte length
             * CRC - 4 byte length
             */

            //get chunk size and type
            $chunk = @unpack('Nsize/a4type', $chunkHeader);

            if (empty($chunk['size'])) {
                break;
            }

            //read chunk body
            $body = fread($sf, $chunk['size']);

            //read chunk CRC
            $crc = fread($sf, 4);

            //strip chunks with provided type
            if (!in_array($chunk['type'], $types)) {
                fwrite($df, $chunkHeader);
                fwrite($df, $body);
                fwrite($df, $crc);
            }

            $chunkHeader = fread($sf, 8);
        }

        fclose($sf);
        fclose($df);

        return true;
    }
}