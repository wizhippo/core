<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Integration\Core\Repository\FieldType;

use Ibexa\Contracts\Core\Repository\Exceptions\ContentFieldValidationException;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Core\Base\Exceptions\InvalidArgumentType;
use Ibexa\Core\FieldType\TextLine\Value as TextLineValue;

/**
 * Integration test for use field type.
 *
 * @group integration
 * @group field-type
 */
class TextLineIntegrationTest extends SearchBaseIntegrationTest
{
    /**
     * Get name of tested field type.
     *
     * @return string
     */
    public function getTypeName()
    {
        return 'ezstring';
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
            'StringLengthValidator' => [
                'minStringLength' => [
                    'type' => 'int',
                    'default' => null,
                ],
                'maxStringLength' => [
                    'type' => 'int',
                    'default' => null,
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
            'StringLengthValidator' => [
                'minStringLength' => 1,
                'maxStringLength' => 42,
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
        return new TextLineValue('Example');
    }

    /**
     * Get name generated by the given field type (via fieldType->getName()).
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'Example';
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
        $this->assertInstanceOf(
            TextLineValue::class,
            $field->value
        );

        $expectedData = [
            'text' => 'Example',
        ];
        $this->assertPropertiesCorrect(
            $expectedData,
            $field->value
        );
    }

    public function provideInvalidCreationFieldData()
    {
        return [
            [
                new \stdClass(),
                InvalidArgumentType::class,
            ],
            [
                42,
                InvalidArgumentType::class,
            ],
            [
                new TextLineValue(str_repeat('.', 64)),
                ContentFieldValidationException::class,
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
        return new TextLineValue('Example  2');
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
        $this->assertInstanceOf(
            TextLineValue::class,
            $field->value
        );

        $expectedData = [
            'text' => 'Example  2',
        ];
        $this->assertPropertiesCorrect(
            $expectedData,
            $field->value
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
        $this->assertInstanceOf(
            TextLineValue::class,
            $field->value
        );

        $expectedData = [
            'text' => 'Example',
        ];
        $this->assertPropertiesCorrect(
            $expectedData,
            $field->value
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
        return [
            [
                new TextLineValue('Simple value'),
                'Simple value',
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
        return [
            [
                'Foobar',
                new TextLineValue('Foobar'),
            ],
        ];
    }

    public function providerForTestIsEmptyValue()
    {
        return [
            [new TextLineValue()],
            [new TextLineValue(null)],
            [new TextLineValue('')],
            [new TextLineValue('   ')],
        ];
    }

    public function providerForTestIsNotEmptyValue()
    {
        return [
            [
                $this->getValidCreationFieldData(),
            ],
            [new TextLineValue(0)],
            [new TextLineValue('0')],
        ];
    }

    protected function getValidSearchValueOne()
    {
        return 'aaa';
    }

    protected function getSearchTargetValueOne()
    {
        // ensure case-insensitivity
        return strtoupper($this->getValidSearchValueOne());
    }

    protected function getValidSearchValueTwo()
    {
        return 'bbb';
    }

    protected function getSearchTargetValueTwo()
    {
        // ensure case-insensitivity
        return strtoupper($this->getValidSearchValueTwo());
    }

    protected function getFullTextIndexedFieldData()
    {
        return [
            ['aaa', 'bbb'],
        ];
    }
}

class_alias(TextLineIntegrationTest::class, 'eZ\Publish\API\Repository\Tests\FieldType\TextLineIntegrationTest');