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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * The Criteria, used for to find Dto with Dao
 * @see DtoInterface
 * @see DaoModelInterface
 * @package Cometwpp
 * @category Class
 */
class Criteria implements CriteriaInterface
{
    const EQUAL = 'equal';
    const NOT_EQUAL = 'notEqual';

    const AND = 'and';
    const OR = 'or';

    const NAME_INDEX = 'name';
    const VAL_INDEX = 'value';
    const CONCAT_INDEX = 'concat';
    const COND_INDEX = 'condition';

    protected $assertions = [];

    public function getAssertions(): array
    {
        return $this->assertions;
    }

    public function andFieldEqual(string $fieldName, $value): CriteriaInterface
    {
        $this->assertions[] = [
            static::CONCAT_INDEX => static::AND,
            static::COND_INDEX => static::EQUAL,
            static::NAME_INDEX => $fieldName,
            static::VAL_INDEX => $value,
        ];
        return $this;
    }

    public function orFieldEqual(string $fieldName, $value): CriteriaInterface
    {
        $this->assertions[] = [
            static::CONCAT_INDEX => static::OR,
            static::COND_INDEX => static::EQUAL,
            static::NAME_INDEX => $fieldName,
            static::VAL_INDEX => $value,
        ];
        return $this;
    }

    public function andFieldNotEqual(string $fieldName, $value): CriteriaInterface
    {
        $this->assertions[] = [
            static::CONCAT_INDEX => static::AND,
            static::COND_INDEX => static::NOT_EQUAL,
            static::NAME_INDEX => $fieldName,
            static::VAL_INDEX => $value,
        ];
        return $this;
    }

    public function orFieldNotEqual(string $fieldName, $value): CriteriaInterface
    {
        $this->assertions[] = [
            static::CONCAT_INDEX => static::OR,
            static::COND_INDEX => static::NOT_EQUAL,
            static::NAME_INDEX => $fieldName,
            static::VAL_INDEX => $value,
        ];
        return $this;
    }
}