<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace Ibexa\Tests\Integration\Core\Repository\FieldType;

use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Contracts\Core\Test\Repository\SetupFactory\Legacy;
use Ibexa\Core\Base\Exceptions\InvalidArgumentValue;
use Ibexa\Core\FieldType\BinaryFile\Value as BinaryFileValue;

/**
 * Integration test for use field type.
 *
 * @group integration
 * @group field-type
 */
class BinaryFileIntegrationTest extends FileSearchBaseIntegrationTest
{
    /**
     * Stores the loaded image path for copy test.
     */
    protected static $loadedBinaryFilePath;

    /**
     * IOService storage prefix for the tested Type's files.
     *
     * @var string
     */
    protected static $storagePrefixConfigKey = 'ibexa.io.binary_file.storage.prefix';

    protected function getStoragePrefix()
    {
        return $this->getConfigValue(self::$storagePrefixConfigKey);
    }

    /**
     * Sets up fixture data.
     *
     * @return array
     */
    protected function getFixtureData()
    {
        return [
            'create' => [
                'id' => null,
                'inputUri' => ($path = __DIR__ . '/_fixtures/image.jpg'),
                'fileName' => 'Icy-Night-Flower-Binary.jpg',
                'fileSize' => filesize($path),
                'mimeType' => 'image/jpeg',
                // Left out'downloadCount' by intention (will be set to 0)
            ],
            'update' => [
                'id' => null,
                'inputUri' => ($path = __DIR__ . '/_fixtures/image.png'),
                'fileName' => 'Blue-Blue-Blue-Sindelfingen.png',
                'fileSize' => filesize($path),
                'downloadCount' => 23,
                // Left out 'mimeType' by intention (will be auto-detected)
            ],
        ];
    }

    /**
     * Get name of tested field type.
     *
     * @return string
     */
    public function getTypeName()
    {
        return 'ezbinaryfile';
    }

    /**
     * Get expected settings schema.
     *
     * @return array
     */
    public function getSettingsSchema()
    {
        return [];
    }

    /**
     * Get a valid $fieldSettings value.
     *
     * @return mixed
     */
    public function getValidFieldSettings()
    {
        return [];
    }

    /**
     * Get $fieldSettings value not accepted by the field type.
     *
     * @return mixed
     */
    public function getInvalidFieldSettings()
    {
        return [
            'somethingUnknown' => 0,
        ];
    }

    /**
     * Get expected validator schema.
     *
     * @return array
     */
    public function getValidatorSchema()
    {
        return [
            'FileSizeValidator' => [
                'maxFileSize' => [
                    'type' => 'int',
                    'default' => false,
                ],
            ],
        ];
    }

    /**
     * Get a valid $validatorConfiguration.
     *
     * @return mixed
     */
    public function getValidValidatorConfiguration()
    {
        return [
            'FileSizeValidator' => [
                'maxFileSize' => 2 * 1024 * 1024, // 2 MB
            ],
        ];
    }

    /**
     * Get $validatorConfiguration not accepted by the field type.
     *
     * @return mixed
     */
    public function getInvalidValidatorConfiguration()
    {
        return [
            'StringLengthValidator' => [
                'minStringLength' => new \stdClass(),
            ],
        ];
    }

    /**
     * Get initial field data for valid object creation.
     *
     * @return mixed
     */
    public function getValidCreationFieldData()
    {
        $fixtureData = $this->getFixtureData();

        return new BinaryFileValue($fixtureData['create']);
    }

    /**
     * Get name generated by the given field type (via fieldType->getName()).
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'Icy-Night-Flower-Binary.jpg';
    }

    /**
     * Asserts that the field data was loaded correctly.
     *
     * Asserts that the data provided by {@link getValidCreationFieldData()}
     * was stored and loaded correctly.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Field $field
     */
    public function assertFieldDataLoadedCorrect(Field $field)
    {
        self::assertInstanceOf(
            BinaryFileValue::class,
            $field->value
        );

        $fixtureData = $this->getFixtureData();
        $expectedData = $fixtureData['create'];

        // Will change during storage
        unset($expectedData['id']);
        $expectedData['inputUri'] = null;

        self::assertNotEmpty($field->value->id);
        $this->assertPropertiesCorrect(
            $expectedData,
            $field->value
        );

        self::assertTrue(
            $this->uriExistsOnIO($field->value->uri),
            "File {$field->value->uri} doesn't exist"
        );

        self::$loadedBinaryFilePath = $field->value->id;
    }

    public function provideInvalidCreationFieldData()
    {
        return [
            [
                [
                    'id' => '/foo/bar/sindelfingen.pdf',
                ],
                InvalidArgumentValue::class,
            ],
            [
                new BinaryFileValue(
                    [
                        'id' => '/foo/bar/sindelfingen.pdf',
                    ]
                ),
                InvalidArgumentValue::class,
            ],
        ];
    }

    /**
     * Get update field externals data.
     *
     * @return array
     */
    public function getValidUpdateFieldData()
    {
        $fixtureData = $this->getFixtureData();

        return new BinaryFileValue($fixtureData['update']);
    }

    /**
     * Get externals updated field data values.
     *
     * This is a PHPUnit data provider
     *
     * @return array
     */
    public function assertUpdatedFieldDataLoadedCorrect(Field $field)
    {
        self::assertInstanceOf(
            BinaryFileValue::class,
            $field->value
        );

        $fixtureData = $this->getFixtureData();
        $expectedData = $fixtureData['update'];

        // Will change during storage
        unset($expectedData['id']);
        $expectedData['inputUri'] = null;

        self::assertNotEmpty($field->value->id);
        $this->assertPropertiesCorrect(
            $expectedData,
            $field->value
        );

        self::assertTrue(
            $this->uriExistsOnIO($field->value->uri),
            "File {$field->value->uri} doesn't exist."
        );
    }

    public function provideInvalidUpdateFieldData()
    {
        return $this->provideInvalidCreationFieldData();
    }

    /**
     * Asserts the the field data was loaded correctly.
     *
     * Asserts that the data provided by {@link getValidCreationFieldData()}
     * was copied and loaded correctly.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Field $field
     */
    public function assertCopiedFieldDataLoadedCorrectly(Field $field)
    {
        $this->assertFieldDataLoadedCorrect($field);

        self::assertEquals(
            self::$loadedBinaryFilePath,
            $field->value->id
        );
    }

    /**
     * Get data to test to hash method.
     *
     * This is a PHPUnit data provider
     *
     * The returned records must have the the original value assigned to the
     * first index and the expected hash result to the second. For example:
     *
     * <code>
     * array(
     *      array(
     *          new MyValue( true ),
     *          array( 'myValue' => true ),
     *      ),
     *      // ...
     * );
     * </code>
     *
     * @return array
     */
    public function provideToHashData()
    {
        $fixture = $this->getFixtureData();
        $expected = $fixture['create'];
        $expected['downloadCount'] = 0;
        $expected['uri'] = $expected['inputUri'];
        $expected['path'] = $expected['inputUri'];

        $fieldValue = $this->getValidCreationFieldData();
        $fieldValue->uri = $expected['uri'];

        return [
            [
                $fieldValue,
                $expected,
            ],
        ];
    }

    /**
     * Get expectations for the fromHash call on our field value.
     *
     * This is a PHPUnit data provider
     *
     * @return array
     */
    public function provideFromHashData()
    {
        $fixture = $this->getFixtureData();
        $fixture['create']['downloadCount'] = 0;
        $fixture['create']['uri'] = $fixture['create']['inputUri'];

        $fieldValue = $this->getValidCreationFieldData();
        $fieldValue->uri = $fixture['create']['uri'];

        return [
            [
                $fixture['create'],
                $fieldValue,
            ],
        ];
    }

    public function providerForTestIsEmptyValue()
    {
        return [
            [new BinaryFileValue()],
            [new BinaryFileValue([])],
        ];
    }

    public function providerForTestIsNotEmptyValue()
    {
        return [
            [
                $this->getValidCreationFieldData(),
            ],
        ];
    }

    protected function getValidSearchValueOne()
    {
        return new BinaryFileValue(
            [
                'inputUri' => ($path = __DIR__ . '/_fixtures/image.jpg'),
                'fileName' => 'blue-blue-blue-sindelfingen.jpg',
                'fileSize' => filesize($path),
            ]
        );
    }

    /**
     * BinaryFile field type is not searchable with Field criterion
     * and sort clause in Legacy search engine.
     */
    protected function checkSearchEngineSupport()
    {
        if ($this->getSetupFactory() instanceof Legacy) {
            self::markTestSkipped(
                "'ezbinaryfile' field type is not searchable with Legacy Search Engine"
            );
        }
    }

    protected function getValidSearchValueTwo()
    {
        return new BinaryFileValue(
            [
                'inputUri' => ($path = __DIR__ . '/_fixtures/image.png'),
                'fileName' => 'icy-night-flower-binary.png',
                'fileSize' => filesize($path),
            ]
        );
    }

    protected function getSearchTargetValueOne()
    {
        $value = $this->getValidSearchValueOne();

        // ensure case-insensitivity
        return strtoupper($value->fileName);
    }

    protected function getSearchTargetValueTwo()
    {
        $value = $this->getValidSearchValueTwo();

        // ensure case-insensitivity
        return strtoupper($value->fileName);
    }

    protected function getAdditionallyIndexedFieldData()
    {
        return [
            [
                'file_size',
                $this->getValidSearchValueOne()->fileSize,
                $this->getValidSearchValueTwo()->fileSize,
            ],
            [
                'mime_type',
                // ensure case-insensitivity
                'IMAGE/JPEG',
                'IMAGE/PNG',
            ],
        ];
    }
}
