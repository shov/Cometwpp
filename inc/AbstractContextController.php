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
    const DEF_SORT_PRIORITY = 10;

    /**
     * AbstractContextEntityController constructor.
     * @param string $autoLoadPath
     */
    public function __construct($autoLoadPath)
    {
        Core::getInstance();
        $this->entitiesAutoload($autoLoadPath);
        $self = $this;
        uasort($this->aEntities, function ($a, $b) use ($self) {
            return $self->cmpEntityPriority($a, $b);
        });
    }

    /**
     * Compare function for sort entities array
     * @param $a
     * @param $b
     * @return int
     */
    protected function cmpEntityPriority($a, $b): int
    {
        $aPr = self::DEF_INQUIRE_PRIORITY;
        $bPr = self::DEF_INQUIRE_PRIORITY;
        if($a instanceof SortPriorityInterface) $aPr = $a->sortPriority();
        if($b instanceof SortPriorityInterface) $bPr = $b->sortPriority();
        return $aPr - $bPr;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->aEntities);
    }


    /**
     *  Call all callbacks (inquiring) with priority
     */
    protected function inquiring()
    {
        ksort($this->aInquiringCall, SORT_NUMERIC);
        foreach ($this->aInquiringCall as $callStack) {
            foreach ($callStack as $call) {
                call_user_func($call);
            }
        }
    }

    /**
     * Add callback to queue with priority
     * @param callable $call
     * @param $priority
     * @return mixed
     */
    public function addInquire(callable $call, $priority = self::DEF_INQUIRE_PRIORITY)
    {
        $priority = (int)$priority;
        $this->aInquiringCall[$priority][] = $call;
    }
}
