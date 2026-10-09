<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Validation\Validator;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Validation\Validator\TcaSchemaFieldLengthValidator;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyObjectStorage;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class TcaSchemaFieldLengthValidatorTest extends FunctionalTestCase
{
    protected ServerRequestInterface $request;

    protected array $testExtensionsToLoad = ['sf_event_mgt'];

    protected function setUp(): void
    {
        parent::setUp();
        $GLOBALS['LANG'] = $this->getContainer()->get(LanguageServiceFactory::class)->create('default');

        $this->request = (new ServerRequest())->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);
        $GLOBALS['TYPO3_REQUEST'] = $this->request;

        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tca_schema_field_length_validator.csv');
    }

    #[Test]
    public function validationFailsForNewObjectWithTooLongFieldValue(): void
    {
        $registration = $this->createRegistration();
        $registration->setFirstname(str_repeat('a', 256));

        $result = $this->getSubject()->validate($registration);

        self::assertTrue($result->hasErrors());
        self::assertTrue($result->forProperty('firstname')->hasErrors());
    }

    #[Test]
    public function validationSucceedsForNewObjectWithValidFieldValues(): void
    {
        $registration = $this->createRegistration();
        $registration->setEvent($this->findEvent(1));

        $result = $this->getSubject()->validate($registration);

        self::assertFalse($result->hasErrors());
    }

    #[Test]
    public function validationDoesNotLoadLazyRelationsOfRelatedObjects(): void
    {
        $event = $this->findEvent(1);
        $registration = $this->createRegistration();
        $registration->setEvent($event);

        $this->getSubject()->validate($registration);

        $registrations = $event->_getProperty('registration');
        self::assertInstanceOf(LazyObjectStorage::class, $registrations);
        self::assertFalse($registrations->isInitialized());
    }

    #[Test]
    public function validationFailsForChangedRelatedObjectWithTooLongFieldValue(): void
    {
        $event = $this->findEvent(1);
        $event->setTitle(str_repeat('a', 256));
        $registration = $this->createRegistration();
        $registration->setEvent($event);

        $result = $this->getSubject()->validate($registration);

        self::assertTrue($result->forProperty('event')->forProperty('title')->hasErrors());
    }

    #[Test]
    public function validationIgnoresUnchangedPersistedRelatedObject(): void
    {
        // Simulates a persisted record exceeding the field length, which has not been changed
        $event = $this->findEvent(1);
        $event->setTitle(str_repeat('a', 256));
        $event->_memorizeCleanState();
        $registration = $this->createRegistration();
        $registration->setEvent($event);

        $result = $this->getSubject()->validate($registration);

        self::assertFalse($result->hasErrors());
    }

    private function getSubject(): TcaSchemaFieldLengthValidator
    {
        $subject = GeneralUtility::makeInstance(TcaSchemaFieldLengthValidator::class);
        $subject->setRequest($this->request);

        return $subject;
    }

    private function createRegistration(): Registration
    {
        $registration = new Registration();
        $registration->setFirstname('Max');
        $registration->setLastname('Mustermann');
        $registration->setEmail('max@example.com');

        return $registration;
    }

    private function findEvent(int $uid): Event
    {
        $event = $this->get(EventRepository::class)->findByUid($uid);
        self::assertInstanceOf(Event::class, $event);

        return $event;
    }
}
