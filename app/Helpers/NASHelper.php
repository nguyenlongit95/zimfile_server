<?php

namespace App\Helpers;

use phpDocumentor\Reflection\File;

class NASHelper
{
    /**
     * Define global var config NAS server
     */
    const NAS_NAME = 'zimstudio.ddns.net';
    const NAS_URL = '27.72.89.215';
    const NAS_USERNAME = 'editor';
    const NAS_PASSWORD = '123456';
    const NAS_POST = 21;

    /**
     * Helper function connect to nas server storage
     *
     * @return string|mixed
     */
    public function connectNAS()
    {
        return ftp_connect(self::NAS_URL, self::NAS_POST, 90);
    }

    /**
     * Helper function login to NAS server storage
     *
     * @param array $conn
     * @return null
     */
    public function loginNAS($conn)
    {
        return ftp_login($conn, self::NAS_USERNAME, self::NAS_PASSWORD);
    }

    /**
     * Helper function read a file in NAS storage
     *
     * @param object $conn
     * @param bool $login
     * @param string $file
     * @return mixed|File
     */
    public function readFile($conn, $login, $file)
    {
        $local_file = asset("/sources/longnct_test/longnguyen.jpg");
        $server_file = "/disk1/DATA/longnct_test/longnguyen.jpg";
        $fp = fopen($local_file,"w");
        $file = ftp_fget($conn, $fp, $server_file, FTP_ASCII, 0);
        return $file;
    }
}
