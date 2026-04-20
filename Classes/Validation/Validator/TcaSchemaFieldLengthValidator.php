<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Validation\Validator;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\TableColumnType;
use TYPO3\CMS\Core\Schema\TcaSchemaFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapFactory;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use TYPO3\CMS\Extbase\Validation\Validator\ObjectValidatorInterface;

/**
 * Validates that all string properties of a domain model (and its nested relations) do not exceed
 * the maximum field length defined in the database schema.
 *
 * Recursively traverses 1:1 relations (AbstractDomainObject properties) and 1:n / m:n relations
 * (Traversable properties, e.g. ObjectStorage) and validates each related model as well.
 * Circular references are detected via a shared SplObjectStorage and skipped to prevent infinite loops.
 *
 * Implements ObjectValidatorInterface so that TYPO3's validation chain can inject the shared
 * validatedInstancesContainer when this validator is used with other object validators.
 */
final class TcaSchemaFieldLengthValidator extends AbstractValidator implements ObjectValidatorInterface
{
    private const SUPPORTED_TYPES = [
        TableColumnType::INPUT->value,
        TableColumnType::EMAIL->value,
        TableColumnType::LINK->value,
        TableColumnType::SLUG->value,
        TableColumnType::PASSWORD->value,
        TableColumnType::COLOR->value,
        TableColumnType::TEXT->value,
    ];

    private ?\SplObjectStorage $validatedInstancesContainer = null;
    private array $columnCache = [];

    public function __construct(
        private readonly DataMapFactory $dataMapFactory,
        private readonly TcaSchemaFactory $tcaSchemaFactory,
        private readonly ConnectionPool $connectionPool
    ) {
    }

    public function setValidatedInstancesContainer(\SplObjectStorage $validatedInstancesContainer): void
    {
        $this->validatedInstancesContainer = $validatedInstancesContainer;
    }

    public function isValid(mixed $value): void
    {
        if (!($value instanceof AbstractDomainObject)) {
            return;
        }

        if ($this->validatedInstancesContainer === null) {
            $this->validatedInstancesContainer = new \SplObjectStorage();
        }

        $this->result->merge($this->validateDomainObject($value));
    }

    private function validateDomainObject(AbstractDomainObject $value): Result
    {
        $result = new Result();

        if ($this->validatedInstancesContainer->contains($value)) {
            return $result;
        }
        $this->validatedInstancesContainer->attach($value);

        $dataMap = $this->dataMapFactory->buildDataMap(get_class($value));
        $tableName = $dataMap->getTableName();
        $schema = $this->tcaSchemaFactory->get($tableName);

        if (!isset($this->columnCache[$tableName])) {
            $schemaManager = $this->connectionPool->getConnectionForTable($tableName)->createSchemaManager();
            $this->columnCache[$tableName] = $schemaManager->listTableColumns($tableName);
        }
        $columns = $this->columnCache[$tableName];

        foreach ($schema->getFields() as $field) {
            if (!in_array($field->getType(), self::SUPPORTED_TYPES, true)) {
                continue;
            }

            $dbFieldName = $field->getName();

            /** @var non-empty-string $fieldName */
            $fieldName = GeneralUtility::underscoredToLowerCamelCase($dbFieldName);
            $fieldValue = $value->_getProperty($fieldName);
            if (!is_string($fieldValue)) {
                continue;
            }

            $schemaColumn = $columns[$dbFieldName] ?? null;
            $maxLength = $schemaColumn?->getLength() ?? null;
            if ($maxLength === null) {
                continue;
            }

            $currentLength = mb_strlen($fieldValue, 'utf-8');
            if ($currentLength > $maxLength) {
                $message = $this->translateErrorMessage('validation.invalid_field_length', 'sf_event_mgt', [$currentLength, $maxLength]);
                $result->forProperty($fieldName)->addError(new Error($message, 1773493263));
            }
        }

        foreach ($value->_getProperties() as $propertyName => $propertyValue) {
            if ($propertyValue instanceof AbstractDomainObject) {
                $subResult = $this->validateDomainObject($propertyValue);
                if ($subResult->hasMessages()) {
                    $result->forProperty($propertyName)->merge($subResult);
                }
            } elseif ($propertyValue instanceof \Traversable) {
                foreach ($propertyValue as $index => $element) {
                    if ($element instanceof AbstractDomainObject) {
                        $subResult = $this->validateDomainObject($element);
                        if ($subResult->hasMessages()) {
                            $result->forProperty($propertyName)->forProperty((string)$index)->merge($subResult);
                        }
                    }
                }
            }
        }

        return $result;
    }
}
