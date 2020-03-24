<?php


namespace App\Classes;


class Database
{

    /** @type SPDO */
    public $spdo;

    public function __construct (array $dbMain = null)
    {
        $configuration =  $dbMain ? $dbMain : Config::get('db.mysql');

        try {
            $this->config($configuration);
        } catch (DatabaseException $error) {
            $error->render();
        }
    }

    public function config ($configuration)
    {
        if ($configuration) {
            $host = $configuration['host'];
            $port = $configuration['port'];
            $user = $configuration['user'];
            $pass = $configuration['pass'];
            try {
                $this->connection($host, $port, $user, $pass);
            } catch (DatabaseException $error) {

            }
        } else {
            throw new DatabaseException('Configuration for connection is not exists!');
        }
    }

    public function connection ($host, $port, $user, $pass)
    {
        $this->spdo = new SPDO($host . ($port ? $port : ''), $user, $pass);

        if ($this->spdo->getError()) {
            throw new DatabaseException('SPDO Error - ' . print_r($this->spdo->getError(), true) );
        }
    }

}