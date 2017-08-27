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
 * Present the model as data access object
 * @package Cometwpp
 * @category Interface
 */
interface DaoModelInterface
{
    public function save(?DtoInterface &$dto = null);

    public function findById(int $id): ?DtoInterface;

    public function findByCriteria(CriteriaInterface $criteria): ?DtoInterface;

    public function findAllByCriteria(CriteriaInterface $criteria): ?array;
}