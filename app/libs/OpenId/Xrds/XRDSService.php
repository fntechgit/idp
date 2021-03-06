<?php namespace OpenId\Xrds;
/**
 * Copyright 2016 OpenStack Foundation
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
 * Class XRDSService
 * XRDS Service Element
 * @package OpenId\Xrds
 */
final class XRDSService
{

    private $priority;
    private $type;
    private $uri;
    private $local_id;
    private $extensions;

    public function __construct($priority, $type, $uri, $extensions = [], $local_id = null)
    {
        $this->priority = $priority;
        $this->type = $type;
        $this->uri = $uri;
        $this->local_id = $local_id;
        $this->extensions = $extensions;
    }

    public function render()
    {
        $local_id = empty($this->local_id) ? "" : "<LocalID>{$this->local_id}</LocalID>\n";

        $extensions = "";
        foreach ($this->extensions as $extension) {
            $extensions .= "<Type>{$extension}</Type>\n";
        }

        $element = "<Service priority=\"{$this->priority}\">\n<Type>{$this->type}</Type>\n{$extensions}<URI>{$this->uri}</URI>\n{$local_id}</Service>\n";
        return $element;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getLocalId()
    {
        return $this->local_id;
    }
}