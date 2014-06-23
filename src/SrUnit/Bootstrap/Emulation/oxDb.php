<?php

namespace SrUnit\Bootstrap\Emulation;

use SrUnit\Mock\Factory;

class oxDb
{
    const FETCH_MODE_ASSOC = 'assoc';

    /**
     * Sets configs object with method getVar() and properties needed for successful connection.
     *
     * @param object $oConfig configs.
     *
     * @return void
     */
    public static function setConfig( $oConfig )
    {
    }

    /**
     * Returns Singleton instance
     *
     * @return oxdb
     */
    public static function getInstance()
    {
        $db = Factory::create('\oxDb')->getMock();
        $db->shouldIgnoreMissing();
        $db->shouldReceive()->andReturn();

        return $db;
    }

    /**
     * Returns database object
     *
     * @param int $iFetchMode - fetch mode default numeric - 0
     *
     * @throws oxConnectionException error while initiating connection to DB
     *
     * @return oxLegacyDb
     */
    public static function getDb( $iFetchMode = oxDb::FETCH_MODE_NUM )
    {
        $db = Factory::create('\oxLegacyDb')->getMock();
        $db->shouldIgnoreMissing();
        $db->shouldReceive()->andReturn();

        return $db;
    }
}
