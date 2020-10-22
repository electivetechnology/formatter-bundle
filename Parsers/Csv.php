<?php

namespace Elective\FormatterBundle\Parsers;

use Elective\FormatterBundle\Parsers\ParserException;

/**
 * Elective\FormatterBundle\Parsers\Csv
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class Csv implements ParserInterface
{
    const DEFAULT_MIME_TYPE = 'text/csv';

    /**
     * Returns default mimeType for this parser
     * @return string
     */
    public static function getDefaultMimeType():string
    {
        return self::DEFAULT_MIME_TYPE;
    }

    /**
     * Decodes Csv to Array
     * @param   csv      $data
     * @return  StdClass
     */
    public static function parse($data)
    {
        return self::decode($data);
    }

    /**
     * Encodes array to Csv
     * @param   mixed      $data
     * @return  StdClass
     */
    public static function format($data)
    {
        return self::encode($data);
    }

    public static function decode($csv)
    {
        return;
    }

    /**
     * @param mixed $value The value being encoded. Can be any type except a
     * resource. This function only works with UTF-8 encoded data.
     *
     * @throws Bdcc_Exception
     * @return string
     */
    public static function encode($value)
    {
        $data = array();
        if (is_array($value)) {
            foreach ($value as $row) {
                $data[] = self::parseRow($row);
            }
        } elseif (is_object($value)) {
            $data[] = self::parseRow($value);
        }

        ob_start();
        $out = fopen('php://output', 'w');

        $headerCount = 0;
        $headers = [];
        foreach ($data as $key => $row) {
            $currentHeaders = array_keys($row);
            $currentHeaderCount = count($currentHeaders);

            if ($currentHeaderCount > $headerCount) {
                $headers = $currentHeaders;
                $headerCount = $currentHeaderCount;
            }

            fputcsv($out, array_values($row));
        }

        $csvContent = ob_get_contents();
        ob_clean();

        fputcsv($out, $headers);
        fwrite($out, $csvContent);

        fclose($out);
    }

    public static function parseRow($row, $prefix = null)
    {
        $ret = array();

        foreach ($row as $key => $value) {
            if ($prefix) {
                $key = $prefix . '.' . $key;
            }

            if (is_string($value) || is_integer($value)) {
                $ret[$key] = $value;
            }

            if (is_array($value)) {
                foreach ($value as $child) {
                    $ret[$key] = '"' . implode(",", $value) . '"';
                }
            }

            if (is_null($value)) {
                $ret[$key] = '';
            }

            if (is_bool($value)) {
                $ret[$key] = ($value) ? 'true' : 'false';
            }

            if ($value instanceof \DateTimeInterface) {
                $ret[$key] = $value->format('Y-m-d H:i:s');
            } elseif (is_object($value)) {
                $obj = self::parseRow($value, $key);
                foreach ($obj as $propKey => $prop) {
                    $ret[$propKey] = $prop;
                }
            }
        }

        return $ret;
    }
}
