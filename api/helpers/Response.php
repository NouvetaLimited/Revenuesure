<?php

/**
 * Created by PhpStorm.
 * User: Kibet
 * Date: 27-Apr-17
 * Time: 4:10 PM
 */
class Response
{
    const STATUS_SUCCESS = 200;
    const STATUS_ERROR = 500;

    public $status;
    public $success;
    public $message;
    public $data;
}