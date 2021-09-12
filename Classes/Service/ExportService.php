<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Exception;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Utility\CsvUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class ExportService
 */
class ExportService
{
    protected RegistrationRepository $registrationRepository;
    protected EventRepository $eventRepository;

    public function __construct(RegistrationRepository $registrationRepository, EventRepository $eventRepository)
    {
        $this->registrationRepository = $registrationRepository;
        $this->eventRepository = $eventRepository;
    }

    /**
     * Initiates the CSV downloads for registrations of the given event uid
     *
     * @param int $eventUid EventUid
     * @param array $settings Settings
     * @throws Exception RuntimeException
     */
    public function downloadRegistrationsCsv(int $eventUid, array $settings = []): void
    {
        $content = $this->exportRegistrationsCsv($eventUid, $settings);
        header('Content-Disposition: attachment; filename="event_' . $eventUid . '_reg_' . date('dmY_His') . '.csv"');
        header('Content-Type: text/csv');
        header('Content-Length: ' . strlen($content));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: no-cache');
        echo $content;
    }

    /**
     * Returns all Registrations for the given eventUid as a CSV string
     *
     * @param int $eventUid EventUid
     * @param array $settings Settings
     * @throws Exception RuntimeException
     * @return string
     */
    public function exportRegistrationsCsv(int $eventUid, array $settings = []): string
    {
        $hasRegistrationFields = false;
        $registrationFieldData = [];
        $fieldsArray = array_map('trim', explode(',', ($settings['fields'] ?? '')));

        if (in_array('registration_fields', $fieldsArray)) {
            $hasRegistrationFields = true;
            $registrationFieldData = $this->getRegistrationFieldData($eventUid);
            $fieldsArray = array_diff($fieldsArray, ['registration_fields']);
        }
        $registrations = $this->registrationRepository->findByEvent($eventUid);
        $exportedRegistrations = CsvUtility::csvValues(
            array_merge($fieldsArray, $registrationFieldData),
            $settings['fieldDelimiter'] ?? ',',
            $settings['fieldQuoteCharacter'] ?? '"'
        ) . chr(10);
        /** @var Registration $registration */
        foreach ($registrations as $registration) {
            $exportedRegistration = [];
            foreach ($fieldsArray as $field) {
                $exportedRegistration[] = $this->getFieldValue($registration, $field, $settings);
            }
            if ($hasRegistrationFields) {
                $exportedRegistration = array_merge(
                    $exportedRegistration,
                    $this->getRegistrationFieldValues($registration, $registrationFieldData)
                );
            }
            $exportedRegistrations .= CsvUtility::csvValues(
                $exportedRegistration,
                $settings['fieldDelimiter'] ?? ',',
                $settings['fieldQuoteCharacter'] ?? '"'
            ) . chr(10);
        }

        return $this->prependByteOrderMark($exportedRegistrations, $settings);
    }

    /**
     * Returns an array with fieldvalues for the given registration
     *
     * @param Registration $registration
     * @param array $registrationFieldData
     * @return array
     */
    protected function getRegistrationFieldValues(Registration $registration, array $registrationFieldData): array
    {
        $result = [];
        $registrationFieldValues = [];
        /** @var Registration\FieldValue $fieldValue */
        foreach ($registration->getFieldValues() as $fieldValue) {
            $field = $fieldValue->getField();
            if ($field !== null) {
                $registrationFieldValues[$field->getUid()] =
                    $this->replaceLineBreaks($fieldValue->getValueForCsvExport());
            }
        }
        foreach ($registrationFieldData as $fieldUid => $fieldTitle) {
            if (isset($registrationFieldValues[$fieldUid])) {
                $result[] = $registrationFieldValues[$fieldUid];
            } else {
                $result[] = '';
            }
        }

        return $result;
    }

    /**
     * Returns an array of registration field uids and title
     *
     * @param int $eventUid
     * @return array
     */
    protected function getRegistrationFieldData(int $eventUid): array
    {
        $result = [];
        /** @var Event $event */
        $event = $this->eventRepository->findByUid($eventUid);
        if ($event !== null) {
            $result = $event->getRegistrationFieldUidsWithTitle();
        }

        return $result;
    }

    /**
     * Prepends Byte Order Mark to exported registrations
     *
     * @param string $exportedRegistrations
     * @param array $settings
     * @return string
     */
    protected function prependByteOrderMark(string $exportedRegistrations, array $settings): string
    {
        if ((bool)($settings['prependBOM'] ??  false)) {
            $exportedRegistrations = chr(239) . chr(187) . chr(191) . $exportedRegistrations;
        }

        return $exportedRegistrations;
    }

    /**
     * Returns the requested field from the given registration. If the field is a DateTime object,
     * a formatted date string is returned
     *
     * @param Registration $registration
     * @param string $field
     * @param array $settings
     * @return string
     */
    protected function getFieldValue(Registration $registration, string $field, array $settings): string
    {
        $value = ObjectAccess::getPropertyPath($registration, $field);
        if ($value instanceof \DateTime) {
            $dateFormat = $settings['dateFieldFormat'] ?? 'd.m.Y';
            $value = $value->format($dateFormat);
        }

        return $this->replaceLineBreaks($value);
    }

    /**
     * Replaces all line breaks with a space
     *
     * @param mixed $value
     * @return mixed
     */
    protected function replaceLineBreaks($value)
    {
        return str_replace(["\r\n", "\r", "\n"], ' ', $value);
    }

    protected function getBackendUser(): ?BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'] ?? null;
    }
}
