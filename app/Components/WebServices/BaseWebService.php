<?php

namespace App\Components\WebServices;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class BaseWebService
{
    public string $_method;
    public string $_url;
    public array  $_data = [];
    public array  $_headers = [];

    public function sendRequest(): Response
    {
        return Http::withHeaders($this->_headers)->{$this->_method}($this->_url, $this->_data);
    }
}
