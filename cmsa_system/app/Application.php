<?php

namespace App;

class Application extends \Illuminate\Foundation\Application
{
    /**
     * Get the path to the public / web directory.
     *
     * @param  string  $path
     * @return string
     */
    function publicPath($path = '')
    {
        return $this->joinPaths($this->publicPath ?: $this->basePath('../html'), $path);
    }
}
