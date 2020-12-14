<?php


namespace App\Models;


class DBC
{
    const DB_USERNAME = 'ST58211';
    const DB_PASSWORD = 'Andr7265357';
    const DB_CONNECTION_STRING = '//fei-sql1.upceucebny.cz:1521/IDAS.UPCEUCEBNY.CZ';

    static public function getConnection() {
        return oci_connect(DBC::DB_USERNAME, DBC::DB_PASSWORD, DBC::DB_CONNECTION_STRING);
    }
}
