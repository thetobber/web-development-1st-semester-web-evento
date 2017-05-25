<?php
namespace Evento;

/*
Insert timezones in MySQL database
https://dev.mysql.com/downloads/timezones.html

DATE format
MySQL: YYYY-MM-DD
PHP:   Y-m-d

DATETIME format
MySQL: YYYY-MM-DD HH:MM:SS
PHP:   Y-m-d H:i:s

Possible shown like this:
d-m-Y H:i:s \U\T\C P
*/
class Config
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'evento';
    const DB_USER = 'evento';
    const DB_PASS = 'nhQrQQzf7C6mTybsm47Hy4ae';
    const DB_CHARSET = 'utf8';

    const BASE_PATH = '';
    const TIMEZONE = 'Europe/Copenhagen';

    const DEBUG = true;
    const CACHE_DIR = false; //__DIR__.'/cache'
    const DATE_FORMAT = 'Y-m-d H:i:s';

    private function __construct()
    {
    }
}