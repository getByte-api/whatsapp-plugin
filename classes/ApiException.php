<?php

namespace GetByte\Whatsapp\Classes;

use GuzzleHttp\Psr7\Response;

class ApiException extends \Exception
{
    protected $response;

    protected $content;

    public function __construct(Response $response)
    {
        $this->response = $response;

        $this->content = json_decode($response->getBody()->getContents());
        $message = $this->prepareMessage();

        parent::__construct($message, $response->getStatusCode());
    }

    public function getResponse()
    {
        return $this->response;
    }

    protected function prepareMessage()
    {
        $message = '';
        if ($this->content->message ?? false) {
            $message = $this->content->message;
        } else if ($this->content->error ?? false) {
            $message = $this->content->error;
        }

        $detail = $this->content->response->response->message ?? [];
        $detail = end($detail);
        if ($detail && is_array($detail)) {
            $message = implode(' ', $detail);
        } else if ($detail) {
            $message = $detail;
        }

        return $message ?: json_encode($this->content);
    }
}
