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
 * Make query from chain of calls
 * @package Cometwpp
 * @category Interface
 */
interface CriteriaInterface
{
    public function getAssertions(): array;

    public function andFieldEqual(string $fieldName, $value): CriteriaInterface;

    public function orFieldEqual(string $fieldName, $value): CriteriaInterface;

    public function andFieldNotEqual(string $fieldName, $value): CriteriaInterface;

    public function orFieldNotEqual(string $fieldName, $value): CriteriaInterface;

    /**
     * TODO: Implement all (for example here we have SQL) methods below
     *
        Name                    ------    Description
         BETWEEN ... AND ...     ------    Check whether a value is within a range of values
         COALESCE()              ------    Return the first non-NULL argument
     +   =                       ------    Equal operator
         <=>                     ------    NULL-safe equal to operator
         >                       ------    Greater than operator
         >=                      ------    Greater than or equal operator
         GREATEST()              ------    Return the largest argument
         IN()                    ------    Check whether a value is within a set of values
         INTERVAL()              ------    Return the index of the argument that is less than the first argument
         IS                      ------    Test a value against a boolean
         IS NOT                  ------    Test a value against a boolean
         IS NOT NULL             ------    NOT NULL value test
         IS NULL                 ------    NULL value test
         ISNULL()                ------    Test whether the argument is NULL
         LEAST()                 ------    Return the smallest argument
         <                       ------    Less than operator
         <=                      ------    Less than or equal operator
         LIKE                    ------    Simple pattern matching
         NOT BETWEEN ... AND ... ------    Check whether a value is not within a range of values
     +   !=, <>                  ------    Not equal operator
         NOT IN()                ------    Check whether a value is not within a set of values
         NOT LIKE                ------    Negation of simple pattern matching
         STRCMP()                ------    Compare two strings
     *
     */
}