<?php

namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    private static $capsule;

    /**
     * Initialiserer databaseforbindelsen.
     */
    public static function init()
    {
        if (!self::$capsule) {
            self::$capsule = new Capsule;

            self::$capsule->addConnection([
                'driver'    => 'mysql',
                'host'      => getenv('DB_HOST'),
                'database'  => getenv('DB_NAME'),
                'username'  => getenv('DB_USER'),
                'password'  => getenv('DB_PASSWORD'),
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ]);

            // Sett opp Capsule som global instans
            self::$capsule->setAsGlobal();
            self::$capsule->bootEloquent();
        }
    }

    /**
     * Hent query builder for en tabell.
     */
    public static function table($table)
    {
        self::init(); // Sørg for at init er kalt
        return self::$capsule->table($table);
    }

    /**
     * Hent databaseforbindelsen.
     */
    public static function getConnection()
    {
        self::init(); // Sørg for at init er kalt
        return self::$capsule->getConnection();
    }
}
