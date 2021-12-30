<?php

namespace Extend\Dorea\library;

class Value
{
    public static function parser($text, $useObject = true) {
        $meta = json_decode($text, true);
        return self::parse_node($meta, $useObject);
    }

    private static function parse_node($obj, $useObject = true) {
        foreach ($obj as $key => $value) {
            if ($key == "String") {
                return (string)$value;
            } else if ($key == "Number") {

                if (gettype($value) == "integer" || gettype($value) == "double") {
                    return $value;
                }

                $tdb = doubleval($value);
                if ($tdb - intval($tdb) == 0) {
                    return intval($value);
                }
                return $tdb;

            } else if ($key == "Boolean") {
                if (gettype($value) == "boolean") {
                    return $value;
                } else if (gettype($value) == "string") {
                    return strtoupper($value) == "TRUE";
                }
                return null;
            } else if ($key == "List") {
                $result = [];
                foreach ($value as $sub) {
                    $result[] = self::parse_node($sub, $useObject);
                }
                return $result;
            } else if ($key == "Dict") {
                $result = [];
                foreach ($value as $suk => $sub) {
                    $result[$suk] = self::parse_node($sub, $useObject);
                }
                return $result;
            } else if ($key == "Tuple") {
                if ($useObject) {
                    return Tuple::build(self::parse_node($value[0]), self::parse_node($value[1]));
                } else {
                    return $value;
                }
            } else if ($key == "Binary") {
                if ($useObject) {
                    return Binary::fromString((string)$value);
                }
                return $value;
            }
        }
        return null;
    }

    public static function stringify($object): string {

        if (gettype($object) == "string") {
            return "\"" . $object . "\"";
        } else if (gettype($object) == "integer" || gettype($object) == "double") {
            return (string)$object;
        } else if (gettype($object) == "boolean") {
            return $object ? "true" : "false";
        } else if (gettype($object) == "array") {

            $isArray = true;
            // 这里被迫遍历两次，因为严格区分字典和数组
            foreach ($object as $key => $value) {
                if (gettype($key) != "integer") {
                    $isArray = false;
                    break;
                }
            }

            $result = "";
            if ($isArray) {
                $result .= "[";
            } else {
                $result .= "{";
            }
            foreach($object as $key => $value) {
                if($isArray) {
                    $result .= self::stringify($value) . ",";
                } else {
                    $result .= "\"" . (string)$key. "\"" . ":" . self::stringify($value) . ",";
                }
            }

            if ($isArray) {
                return substr($result, 0, length($result) - 1) . "]";
            } else {
                return substr($result, 0, length($result) - 1) . "}";
            }
        } else if (gettype($object) == "object") {
            if( $object instanceof Tuple ) {
                return "(" . self::stringify($object->first(null)) . "," . self::stringify($object->second(null)) . ")";
            } else if ( $object instanceof Binary ) {
                return $object->stringify();
            }
        }
        return "";
    }

}