<?php

namespace common\components;

/**
 * 
 */
class ClaArray {

    /**
     * @return array
     * @param array $src
     * @param array $in
     * @param int|string $pos
     */
    static function array_push_after($src, $in, $pos) {
        if (is_int($pos))
            $R = array_merge(array_slice($src, 0, $pos + 1), $in, array_slice($src, $pos + 1));
        else {
            foreach ($src as $k => $v) {
                $R[$k] = $v;
                if ($k == $pos)
                    $R = array_merge($R, $in);
            }
        }return $R;
    }

    /**
     * Move element of array with value
     * @param type $arr
     * @param type $value
     * @param type $step
     */
    static function moveWithValue($arr = array(), $value = '', $step = 1) {
        if ($step != 1)
            $step = -1;
        $key = array_search($value, $arr);
        if ($key === false)
            return $arr;
        return self::moveWithKey($arr, $key, $step);
    }

    /**
     * Move up, down of array's element with key
     * @param type $arr
     * @param type $key
     * @param type $step
     * @return type
     */
    static function moveWithKey($arr = array(), $key = '', $step = 1) {
        if ($step != 1)
            $step = -1;
        $posi = self::getPositionOfElement($arr, $key);
        if ($posi === false)
            return $arr;
        if ($step == 1 && $posi == 0)
            return $arr;
        elseif ($step == -1 && $posi == count($arr) - 1)
            return $arr;
        $temp = array_splice($arr, $posi, 1);
        $temp2 = array_splice($arr, $posi - $step);
        $result = array_merge($arr, $temp, $temp2);
        return $result;
    }

    /**
     * Delete element of array
     * @param type $arr
     * @param type $key
     * @return type
     */
    static function deleteWithKey($arr = array(), $key = '') {
        unset($arr[$key]);
        return $arr;
    }

    /**
     * delete with value
     * @param type $arr
     * @param type $key
     * @return type
     */
    static function deleteWithValue($arr = array(), $value = '') {
        $key = array_search($value, $arr);
        if ($key !== false)
            unset($arr[$key]);
        return $arr;
    }

    /**
     * Trả về vị trí của một đối tượng trong mảng
     * @param type $arr
     * @param type $key
     */
    static function getPositionOfElement($arr = array(), $key = '') {
        $posi = array_search($key, array_keys($arr));
        return $posi;
    }

    /**
     * trả về mảng các phần tử ngẫu nhiên trong mảng
     * @param type $arr
     * @param type $num
     */
    static function getRandomInArray($arr = array(), $num = 1) {
        $count = count($arr);
        $num = (int) $num;
        $return = array();
        if ($count < $num)
            return $arr;
        $_array = array_rand($arr, $num);
        foreach ($_array as $i)
            $return[$i] = $arr[$i];
        return $return;
    }

    /**
     * return first element of array
     * @param type $array
     * @return element of array or null
     */
    static function getFirst($array = array()) {
        if (is_array($array))
            return reset($array);
        return null;
    }

    /**
     * return last element of array
     * @param type $array
     * @return element or null
     */
    static function getLast($array = array()) {
        if (is_array($array))
            return end($array);
        return null;
    }

    /**
     * remove first element of array
     * @param type $array
     * @return array
     */
    static function removeFirstElement($array = array()) {
        if (is_array($array)) {
            //array_shift($array);
            unset($array[current(array_keys($array))]);
        }
        return $array;
    }

    /**
     * remove last element of array
     * @param type $array
     * @return type
     */
    static function removeLastElement($array = array()) {
        if (is_array($array)) {
            array_pop($array);
        }
        return $array;
    }

    /**
     * add element to first array
     * @param type $array
     * @param type $element
     */
    static function addElementToFirst($array = array(), $element) {
        array_unshift($array, $element);
        return $array;
    }

    /**
     * add element to first array
     * @param type $array
     * @param type $element
     */
    static function addElementToLast($array = array(), $element) {
        array_push($array, $element);
        return $array;
    }

    /**
     * add elements to beginning of array
     * @param type $array
     * @param type $array_added
     * @return type
     */
    static function AddArrayToBegin($array = array(), $array_added = array()) {
        if (is_array($array_added)) {
            $array = $array_added + $array;
        }
        return $array;
    }

    /**
     * add elements to ending of array
     * @param type $array
     * @param type $array_added
     * @return type
     */
    static function AddArrayToEnd($array = array(), $array_added = array()) {
        if (is_array($array_added) && is_array($array)) {
            $array = $array + $array_added;
        }
        return $array;
    }

    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     * // does in PHP 5.5 build-in
     */
    static function array_column($input = null, $columnKey = null, $indexKey = null) {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();
        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }
        if (!is_array($params[0])) {
            trigger_error(
                    'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING
            );
            return null;
        }
        if (!is_int($params[1]) && !is_float($params[1]) && !is_string($params[1]) && $params[1] !== null && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        if (isset($params[2]) && !is_int($params[2]) && !is_float($params[2]) && !is_string($params[2]) && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }
        $resultArray = array();
        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;
            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }
            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }
            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }
        }
        return $resultArray;
    }

    /**
     * sort by element of arr
     * @param type $arr
     * @param type $key
     * @param type $type
     */
    static function sortByElement($arr = array(), $key = '', $type = 'asc') {
        if ($arr && $key) {
            usort($arr, function($a, $b) use ($type, $key) {
                $valA = isset($a[$key]) ? $a[$key] : 0;
                $valB = isset($b[$key]) ? $b[$key] : 0;
                if ($type == 'asc') {
                    if ($valA >= $valB) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if ($valA >= $valB) {
                        return false;
                    } else {
                        return true;
                    }
                }
            });
        }
        return $arr;
    }

    /**
     * build options array
     * @param type $data
     * @param type $id_field
     * @param type $text_field
     * @param type $options
     * @return type
     */
    static function builOptions($data = array(), $id_field = '', $text_field = '', $options = array()) {
        $results = array();
        foreach ($data as $option) {
            $results[$option[$id_field]] = $option[$text_field];
        }
        return $results;
    }

    /**
     * @author :HaTV
     * Sort Array By first letter
     */
    static function sortAryByFirstLetter($arrayVal, $col_key = 'name', $first_is_key = false) {
        $newArray = array();
        foreach ($arrayVal as $value) {
            if (empty($newArray[($value[$col_key][0])]) && $first_is_key) {
                $newArray[($value[$col_key][0])][] = $value[$col_key][0];
            }
            $newArray[($value[$col_key][0])][] = $value;
        }
        return $newArray;
    }

    static function partition($list, $p) {
        $listlen = count($list);
        $partlen = floor($listlen / $p);
        $partrem = $listlen % $p;
        $partition = array();
        $mark = 0;
        for ($px = 0; $px < $p; $px++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice($list, $mark, $incr);
            $mark += $incr;
        }
        return $partition;
    }

    /**
     * @hungtm
     * @return type
     */
    static function sortMultidimensionalArray() {
        // Normalize criteria up front so that the comparer finds everything tidy
        $criteria = func_get_args();
        foreach ($criteria as $index => $criterion) {
            $criteria[$index] = is_array($criterion) ? array_pad($criterion, 3, null) : array($criterion, SORT_ASC, null);
        }

        return function($first, $second) use (&$criteria) {
            foreach ($criteria as $criterion) {
                // How will we compare this round?
                list($column, $sortOrder, $projection) = $criterion;
                $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

                // If a projection was defined project the values now
                if ($projection) {
                    $lhs = call_user_func($projection, $first[$column]);
                    $rhs = call_user_func($projection, $second[$column]);
                } else {
                    $lhs = $first[$column];
                    $rhs = $second[$column];
                }

                // Do the actual comparison; do not return if equal
                if ($lhs < $rhs) {
                    return -1 * $sortOrder;
                } else if ($lhs > $rhs) {
                    return 1 * $sortOrder;
                }
            }

            return 0; // tiebreakers exhausted, so $first == $second
        };
    }

}
