<?php

namespace SrUnit\Mock\MockGenerator\Pass;

use Mockery\Generator\MockConfiguration;

/**
 * Class IteratorAwarePass
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Agentur für Neue Kommunikation
 * @package SrUnit\Mock\MockGenerator\Pass
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class CustomMockMethodPass
{
    /**
     * @param string $code
     * @param MockConfiguration $config
     * @return string
     */
    public function apply($code, MockConfiguration $config)
    {
        $code = $this->appendToClass($code, $this->getCustomCode());

        return $code;
    }

    /**
     * @param string $code
     * @param string $newCode
     * @return string
     */
    protected function appendToClass($code, $newCode)
    {
        $lastBrace = strrpos($code, "}");
        $code = substr($code, 0, $lastBrace) . $newCode . "\n    }\n";

        return $code;
    }

    /**
     * @return string
     */
    protected function getCustomCode()
    {
        $code = <<<EOT
            public function implementsIteratorInterface(array \$data)
            {
                \SrUnit\Mock\CustomMock::create(\$this)->implementsIteratorInterface(\$data);
                return \$this;
            }

            public function provideArrayAccess(array \$data)
            {
                \SrUnit\Mock\CustomMock::create(\$this)->provideArrayAccess(\$data);
                return \$this;
            }
EOT;

        return $code;
    }
}