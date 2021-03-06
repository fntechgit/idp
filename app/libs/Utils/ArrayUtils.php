<?php namespace Utils;
/**
* Copyright 2015 OpenStack Foundation
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
* http://www.apache.org/licenses/LICENSE-2.0
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
**/
/**
 * Class ArrayUtils
 * @package Utils
 */
final class ArrayUtils
{
    /**
     * @param array $single_array
     * @return array
     */
    static public function convert2Assoc(array $single_array)
    {
        $multi = [];
        foreach($single_array as $v)
        {
            $multi[$v] = $v;
        }
        return $multi;
    }

    /**
     * @param array $single_array
     * @return string
     */
    static public function toJson(array $single_array)
    {
        return json_encode($single_array);
    }
}