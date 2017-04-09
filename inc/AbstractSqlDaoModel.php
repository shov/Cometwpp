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

use Error;
use ReflectionClass;
use ReflectionProperty;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Dao Model based on SQL data source
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractSqlDaoModel extends AbstractSqlBasedModel implements DaoModelInterface
{
    protected $prefixedTableName;

    /**
     * AbstractSqlDaoModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->prefixedTableName = $this->addPrefixTo($this->getTableName());
    }

    abstract protected function migrate();

    abstract protected function getTableName(): string;

    abstract protected function getDtoClass(): string;

    /**
     * @param int $id
     * @return DtoInterface
     */
    public function findById(int $id): ?DtoInterface
    {
        $query = $this->db->prepare("SELECT * FROM $this->prefixedTableName WHERE id = %d", $id);
        $result = $this->db->get_row($query, ARRAY_A);

        return $this->dtoFactoryByResultArray($result);
    }

    /**
     * Find by the criteria, return first result
     * @param CriteriaInterface $criteria
     * @return DtoInterface
     */
    public function findByCriteria(CriteriaInterface $criteria): ?DtoInterface
    {
        $criteriaQuery = $this->parseCriteriaToSql($criteria);
        $query = $this->db->prepare("SELECT * FROM $this->prefixedTableName WHERE $criteriaQuery");
        $result = $this->db->get_row($query, ARRAY_A);

        return $this->dtoFactoryByResultArray($result);
    }

    /**
     * Find by the criteria, return all results
     * @param CriteriaInterface $criteria
     * @return array of DtoInterface
     */
    public function findAllByCriteria(CriteriaInterface $criteria): ?array
    {
        $criteriaQuery = $this->parseCriteriaToSql($criteria);
        $query = $this->db->prepare("SELECT * FROM $this->prefixedTableName WHERE $criteriaQuery");
        $results = $this->db->get_results($query, ARRAY_A);

        $dtoResultArr = [];

        foreach ($results as $result) {
            $dtoResultArr[] = $this->dtoFactoryByResultArray($result);
        }

        return $dtoResultArr;
    }

    /**
     * Insert or update row in DB in accordance with the Dto
     * @param DtoInterface $dto
     * @return mixed|void
     * @throws Error
     */
    public function save(?DtoInterface $dto = null)
    {
        if(is_null($dto)) return null;

        $dtoClass = $this->getDtoClass();
        if (false === ($dto instanceof $dtoClass)) {
            throw new Error(sprintf("When save, Dto have to be instance of %s in %s Dao context", $dtoClass, static::class));
        }

        $dtoReflection = new ReflectionClass($this->getDtoClass());
        $dtoProps = $dtoReflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $toWriteValues = [];
        $format = [];

        foreach ($dtoProps as $prop) {
            $propName = $prop->getName();
            if('id' === $propName) continue;

            if (isset($dto->$propName)) {
                $format[] = (is_numeric($dto->$propName) ? (is_int($dto->$propName) ? '%d' : '%f') : '%s');
                $toWriteValues[$propName] = $dto->$propName;
            }
        }

        $self = $this;
        $insertNew = function () use ($toWriteValues, $format, $self, $dto) {
            if($self->db->insert($self->prefixedTableName, $toWriteValues, $format)) {
                $dto->setId($self->db->insert_id);
            }
        };

        $updateExists = function () use ($toWriteValues, $format, $self, $dto) {
            $self->db->update($self->prefixedTableName, $toWriteValues, ['id' => $dto->getId(),], $format);
        };

        $action = null;

        switch(true) {
            case (!isset($dto->id) || is_null($dto->id)):
                $action = $insertNew;
                break;
            case (!is_null( $this->findById($dto->getId()) )):
                $action = $updateExists;
                break;
            default:
                $action = $insertNew;
        }

        $action();
    }

    /**
     * Make part of SQL clause from Criteria object
     * @param CriteriaInterface $criteria
     * @return string
     */
    protected function parseCriteriaToSql(CriteriaInterface $criteria): string
    {
        $queryPart = '';
        $assertions = $criteria->getAssertions();

        $spaceStr = ' ';
        $fieldNameQuote = "`";
        $assert = null;
        while (count($assertions) > 0) {
            $first = (is_null($assert));

            $assert = array_pop($assertions);
            $subPart = '';

            if(!$first) {
                switch ($assert[Criteria::CONCAT_INDEX]) {
                    case Criteria:: AND:
                        $subPart .= ' AND ';
                        break;
                    case Criteria:: OR:
                        $subPart .= ' OR ';
                        break;
                    default:
                        continue;
                        break;
                }
            }

            $subPart .= $spaceStr . $fieldNameQuote . $assert[Criteria::NAME_INDEX] . $fieldNameQuote . $spaceStr;

            switch ($assert[Criteria::COND_INDEX]) {
                case Criteria::EQUAL:
                    $subPart .= ' = ';
                    break;
                case Criteria::NOT_EQUAL:
                    $subPart .= ' <> ';
                    break;
                default:
                    continue;
                    break;
            }

            $value = $assert[Criteria::VAL_INDEX];
            $quotes = (is_numeric($value)) ? '' : "'";
            $subPart .= $spaceStr . $quotes . $value . $quotes . $spaceStr;

            $queryPart .= $subPart;
        }
        return $queryPart;
    }

    /**
     * Build Dto from SQL result array
     * @param array|null $result
     * @return DtoInterface
     * @throws Error
     */
    protected function dtoFactoryByResultArray(array $result = null): ?DtoInterface
    {
        if(is_null($result)) return null;
        $dtoReflection = new ReflectionClass($this->getDtoClass());

        /**
         * @var DtoInterface $dto
         */
        $dto = $dtoReflection->newInstance();
        if (false === ($dto instanceof DtoInterface)) {
            throw new Error(sprintf("Dto have to be instance of DtoInterface, class %s given", $this->getDtoClass()));
        }

        $dtoProps = $dtoReflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($dtoProps as $prop) {
            $propName = $prop->getName();
            if (isset($result[$propName])) {
                $dto->$propName = $result[$propName];
            }
        }

        $dtoProps = null;
        $dtoReflection = null;
        return $dto;
    }
}