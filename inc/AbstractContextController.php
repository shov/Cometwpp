<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp;

use Cometwpp\Core\Core;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Load and provide features
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractContextController implements \IteratorAggregate, InquireInterface
{
    use EntityProviderTrait;
    use EntityLoaderTrait;

    protected $aEntities = [];
    protected $aInquiringCall = [];
    const DEF_INQUIRE_PRIORITY = 10;

    /**
     * AbstractContextEntityController constructor.
     * @param string $autoLoadPath
     */
    public function __construct($autoLoadPath)
    {
        Core::getInstance();
        $this->entitiesAutoload($autoLoadPath);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->aEntities);
    }

    protected function inquiring()
    {
        ksort($this->aInquiringCall, SORT_NUMERIC );
        foreach ($this->aInquiringCall as $callStack) {
            foreach ($callStack as $call) {
                call_user_func($call);
            }
        }
    }

    public function addInquire(callable $call, $priority = self::DEF_INQUIRE_PRIORITY) {
        $priority = (int)$priority;
        $this->aInquiringCall[$priority][] = $call;
    }
}
