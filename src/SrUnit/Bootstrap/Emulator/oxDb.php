<?php

namespace SrUnit\Bootstrap\Emulator;

class oxDb
{
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
        $db = \SrUnit\Mock\Factory::create('\oxDb')->getMock();
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
        $db = \SrUnit\Mock\Factory::create('\oxLegacyDb')->getMock();
        $db->shouldIgnoreMissing();
        $db->shouldReceive()->andReturn();

        return $db;
    }

    /**
     * Quotes an array.
     *
     * @param array $aStrArray array of strings to quote
     *
     * @return array
     */
    public function quoteArray( $aStrArray )
    {
        $oDb = self::getDb();

        foreach ( $aStrArray as $sKey => $sString ) {
            $aStrArray[$sKey] = $oDb->quote( $sString );
        }
        return $aStrArray;
    }

    /**
     * Call to reset table description cache
     *
     * @return null
     */
    public function resetTblDescCache()
    {
        self::$_aTblDescCache = array();
    }

    /**
     * Extracts and returns table metadata from DB.
     *
     * @param string $sTableName Name of table to invest.
     *
     * @return array
     */
    public function getTableDescription( $sTableName )
    {
        // simple cache
        if ( isset( self::$_aTblDescCache[$sTableName] ) ) {
            return self::$_aTblDescCache[$sTableName];
        }

        $aFields = self::getDb()->MetaColumns( $sTableName );

        self::$_aTblDescCache[$sTableName] = $aFields;

        return $aFields;
    }

    /**
     * Bidirectional converter for date/datetime field
     *
     * @param object $oObject       data field object
     * @param bool   $blToTimeStamp set TRUE to format MySQL compatible value
     * @param bool   $blOnlyDate    set TRUE to format "date" type field
     *
     * @deprecated from 2012-11-21, use oxRegistry::get('oxUtilsDate')->convertDBDateTime()
     *
     * @return string
     */
    public function convertDBDateTime( $oObject, $blToTimeStamp = false, $blOnlyDate = false )
    {
        return oxRegistry::get('oxUtilsDate')->convertDBDateTime( $oObject, $blToTimeStamp, $blOnlyDate );
    }

    /**
     * Bidirectional converter for timestamp field
     *
     * @param object $oObject       oxField type object that keeps db field info
     * @param bool   $blToTimeStamp if true - converts value to database compatible timestamp value
     *
     * @deprecated from 2012-11-21, use oxRegistry::get('oxUtilsDate')->convertDBTimestamp()
     *
     * @return string
     */
    public function convertDBTimestamp( $oObject, $blToTimeStamp = false )
    {
        throw new UnexpectedValueException(sprintf('%s is not implemented yet.'))
    }

    /**
     * Bidirectional converter for date field
     *
     * @param object $oObject       oxField type object that keeps db field info
     * @param bool   $blToTimeStamp if true - converts value to database compatible timestamp value
     *
     * @deprecated from 2012-11-21, use oxRegistry::get('oxUtilsDate')->convertDBDate()
     *
     * @return string
     */
    public function convertDBDate( $oObject, $blToTimeStamp = false )
    {
        return '';
    }

    /**
     * Checks if given string is valid database field name.
     * It must contain from alphanumeric plus dot and underscore symbols
     *
     * @param string $sField field name
     *
     * @return bool
     */
    public function isValidFieldName( $sField )
    {
        return false;
    }

    /**
     * Escape string for using in mysql statements
     *
     * @param string $sString string which will be escaped
     *
     * @return string
     */
    public function escapeString( $sString )
    {
        if ( 'mysql' == self::_getConfigParam( "_dbType" )) {
            return mysql_real_escape_string( $sString, $this->_getConnectionId() );
        } elseif ( 'mysqli' == self::_getConfigParam( "_dbType" )) {
            return mysqli_real_escape_string( $this->_getConnectionId(), $sString );
        } else {
            return mysql_real_escape_string( $sString, $this->_getConnectionId() );
        }
    }

    /**
     * Updates shop views
     *
     * @param array $aTables If you need to update specific tables, just pass its names as array [optional]
     *
     * @deprecated since v5.0.1 (2012-11-05); Use public oxDbMetaDataHandler::updateViews().
     *
     * @return bool
     */
    public function updateViews( $aTables = null )
    {
    }
}
