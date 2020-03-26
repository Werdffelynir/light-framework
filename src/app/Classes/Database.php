<?php


namespace App\Classes;


class Database
{

    /** @type SPDO */
    public $spdo;

    /** @var string  */
    public $type;

    public function __construct (array $dbConfig = null)
    {
        $name = Config::get('db.main');
        $this->type = $name ? $name : "mysql";
        $configuration = $dbConfig ? $dbConfig : Config::get("db.$name");

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
            $dbname = $configuration['dbname'];
            try {
                $this->connection($host, $port, $dbname, $user, $pass);
            } catch (DatabaseException $error) {
                $error->render();
            }
        } else {
            throw new DatabaseException('Configuration for connection is not exists!');
        }
    }

    public function connection ($host, $port, $dbname, $user, $pass)
    {
        $dsn = "{$this->type}:host=$host". ($port ? ":$port" : "") . ";dbname=$dbname";
        $this->spdo = new SPDO($dsn, $user, $pass);
        if ($this->spdo->getError()) {
            throw new DatabaseException('SPDO Error - ' . print_r($this->spdo->getError(), true) );
        }
    }

}