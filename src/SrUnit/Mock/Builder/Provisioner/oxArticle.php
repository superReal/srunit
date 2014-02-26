<?php

namespace SrUnit\Mock\Builder\Provisioner;

use SrUnit\Mock\Builder\AbstractProvisioner;

/**
 * Class oxArticle
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Agentur für Neue Kommunikation
 * @package SrUnit\Builder\Provisioner
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class oxArticle extends AbstractProvisioner
{
    /**
     * Returns db-fields with dummy data
     *
     * @return array
     */
    protected function getFieldMapping()
    {
        return array(
            'oxarticles__oxid' => $this->generator->md5,
            'oxarticles__oxtitle' => $this->generator->word,
            'oxarticles__oxean' => $this->generator->randomDigit('########'),
            'oxarticles__oxartnum' => $this->generator->randomNumber('############'),
        );
    }


} 