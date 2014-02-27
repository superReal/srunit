<?php

namespace SrUnit\Mock\Builder\Provisioner;

use Mockery\MockInterface;
use SrUnit\Mock\Builder\AbstractProvisioner;

/**
 * Class oxArticle
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Agentur für Neue Kommunikation
 * @package SrUnit\Builder\Provisioner
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class oxArticleProvisioner extends AbstractProvisioner
{
    /** @var string */
    protected $tableName = 'oxarticles';

    /** @var array */
    protected $fieldMapping;

    /**
     * @inheritdoc
     */
    protected function getFieldMapping()
    {
        if (isset($this->fieldMapping)) {
            return $this->fieldMapping;
        }

        $defaults = $this->getDefaultFields(
            array('oxid', 'oxshopid', 'oxparentid', 'oxactive', 'oxactivefrom', 'oxactiveto')
        );

        $fieldMapping = array(
            'oxartnum' => $this->generator->numerify('#########'),
            'oxean' => $this->generator->numerify('###-############'),
            'oxdistean' => null,
            'oxmpn' => $this->generator->numerify('##-##-#####'),
            'oxtitle' => $this->generator->randomElement(array('T-Shirt', 'Bluse', 'Polo-Shirt')) . ' ' .  ucfirst($this->generator->word),
            'oxprice' => $this->generator->randomFloat(2, 100, 200),
            'oxblfixedprice' => 0,
            'oxpricea' => 0,
            'oxpriceb' => 0,
            'oxpricec' => 0,
            'oxbprice' => 0,
            'oxtprice' => 0,
            'oxunitname' => '',
            'oxunitquantity' => 0,
            'oxexturl' => '',
            'oxurldesc' => '',
            'oxurlimg' => '',
            'oxvat' => null,
            'oxthumb' => '',
            'oxicon' => '',
            'oxpic1' => '',
            'oxpic2' => '',
            'oxpic3' => '',
            'oxpic4' => '',
            'oxpic5' => '',
            'oxpic6' => '',
            'oxpic7' => '',
            'oxpic8' => '',
            'oxpic9' => '',
            'oxpic10' => '',
            'oxpic11' => '',
            'oxpic12' => '',
        );

        $this->fieldMapping = array_merge($defaults, $fieldMapping);

        return $this->fieldMapping;
    }

    /**
     * @inheritdoc
     */
    protected function applyStubs(MockInterface $object)
    {
        $fieldMapping = $this->getFieldMapping();

        $object->shouldReceive('getId')->andReturn($fieldMapping['oxid'])->byDefault();
        $object->shouldReceive('save')->andReturn(true)->byDefault();
    }
}