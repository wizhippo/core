<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace Ibexa\Tests\Core\FieldType;

use Ibexa\Core\Base\Exceptions\InvalidArgumentException;
use Ibexa\Core\FieldType\Float\Type as FloatType;
use Ibexa\Core\FieldType\Float\Value as FloatValue;
use Ibexa\Core\FieldType\ValidationError;

/**
 * @group fieldType
 * @group ezfloat
 */
class FloatTest extends FieldTypeTest
{
    /**
     * Returns the field type under test.
     *
     * This method is used by all test cases to retrieve the field type under
     * test. Just create the FieldType instance using mocks from the provided
     * get*Mock() methods and/or custom get*Mock() implementations. You MUST
     * NOT take care for test case wide caching of the field type, just return
     * a new instance from this method!
     *
     * @return \Ibexa\Core\FieldType\FieldType
     */
    protected function createFieldTypeUnderTest()
    {
        $fieldType = new FloatType();
        $fieldType->setTransformationProcessor($this->getTransformationProcessorMock());

        return $fieldType;
    }

    /**
     * Returns the validator configuration schema expected from the field type.
     *
     * @return array
     */
    protected function getValidatorConfigurationSchemaExpectation()
    {
        return [
            'FloatValueValidator' => [
                'minFloatValue' => [
                    'type' => 'float',
                    'default' => null,
                ],
                'maxFloatValue' => [
                    'type' => 'float',
                    'default' => null,
                ],
            ],
        ];
    }

    /**
     * Returns the settings schema expected from the field type.
     *
     * @return array
     */
    protected function getSettingsSchemaExpectation()
    {
        return [];
    }

    /**
     * Returns the empty value expected from the field type.
     *
     * @return \Ibexa\Core\FieldType\Float\Value
     */
    protected function getEmptyValueExpectation()
    {
        return new FloatValue();
    }

    public function provideInvalidInputForAcceptValue()
    {
        return [
            [
                'foo',
                InvalidArgumentException::class,
            ],
            [
                [],
                InvalidArgumentException::class,
            ],
            [
                new FloatValue('foo'),
                InvalidArgumentException::class,
            ],
        ];
    }

    /**
     * Data provider for valid input to acceptValue().
     *
     * Returns an array of data provider sets with 2 arguments: 1. The valid
     * input to acceptValue(), 2. The expected return value from acceptValue().
     * For example:
     *
     * <code>
     *  return array(
     *      array(
     *          null,
     *          null
     *      ),
     *      array(
     *          __FILE__,
     *          new BinaryFileValue( array(
     *              'path' => __FILE__,
     *              'fileName' => basename( __FILE__ ),
     *              'fileSize' => filesize( __FILE__ ),
     *              'downloadCount' => 0,
     *              'mimeType' => 'text/plain',
     *          ) )
     *      ),
     *      // ...
     *  );
     * </code>
     *
     * @return array
     */
    public function provideValidInputForAcceptValue()
    {
        return [
            [
                null,
                new FloatValue(),
            ],
            [
                42.23,
                new FloatValue(42.23),
            ],
            [
                23,
                new FloatValue(23.),
            ],
            [
                new FloatValue(23.42),
                new FloatValue(23.42),
            ],
            [
                '42.23',
                new FloatValue(42.23),
            ],
            [
                '23',
                new FloatValue(23.),
            ],
        ];
    }

    /**
     * Provide input for the toHash() method.
     *
     * Returns an array of data provider sets with 2 arguments: 1. The valid
     * input to toHash(), 2. The expected return value from toHash().
     * For example:
     *
     * <code>
     *  return array(
     *      array(
     *          null,
     *          null
     *      ),
     *      array(
     *          new BinaryFileValue( array(
     *              'path' => 'some/file/here',
     *              'fileName' => 'sindelfingen.jpg',
     *              'fileSize' => 2342,
     *              'downloadCount' => 0,
     *              'mimeType' => 'image/jpeg',
     *          ) ),
     *          array(
     *              'path' => 'some/file/here',
     *              'fileName' => 'sindelfingen.jpg',
     *              'fileSize' => 2342,
     *              'downloadCount' => 0,
     *              'mimeType' => 'image/jpeg',
     *          )
     *      ),
     *      // ...
     *  );
     * </code>
     *
     * @return array
     */
    public function provideInputForToHash()
    {
        return [
            [
                new FloatValue(),
                null,
            ],
            [
                new FloatValue(23.42),
                23.42,
            ],
        ];
    }

    /**
     * Provide input to fromHash() method.
     *
     * Returns an array of data provider sets with 2 arguments: 1. The valid
     * input to fromHash(), 2. The expected return value from fromHash().
     * For example:
     *
     * <code>
     *  return array(
     *      array(
     *          null,
     *          null
     *      ),
     *      array(
     *          array(
     *              'path' => 'some/file/here',
     *              'fileName' => 'sindelfingen.jpg',
     *              'fileSize' => 2342,
     *              'downloadCount' => 0,
     *              'mimeType' => 'image/jpeg',
     *          ),
     *          new BinaryFileValue( array(
     *              'path' => 'some/file/here',
     *              'fileName' => 'sindelfingen.jpg',
     *              'fileSize' => 2342,
     *              'downloadCount' => 0,
     *              'mimeType' => 'image/jpeg',
     *          ) )
     *      ),
     *      // ...
     *  );
     * </code>
     *
     * @return array
     */
    public function provideInputForFromHash()
    {
        return [
            [
                null,
                new FloatValue(),
            ],
            [
                23.42,
                new FloatValue(23.42),
            ],
        ];
    }

    /**
     * Provide data sets with validator configurations which are considered
     * valid by the {@link validateValidatorConfiguration()} method.
     *
     * Returns an array of data provider sets with a single argument: A valid
     * set of validator configurations.
     *
     * For example:
     *
     * <code>
     *  return array(
     *      array(
     *          array(),
     *      ),
     *      array(
     *          array(
     *              'IntegerValueValidator' => array(
     *                  'minIntegerValue' => 0,
     *                  'maxIntegerValue' => 23,
     *              )
     *          )
     *      ),
     *      // ...
     *  );
     * </code>
     *
     * @return array
     */
    public function provideValidValidatorConfiguration()
    {
        return [
            [
                [],
            ],
            [
                [
                    'FloatValueValidator' => [
                        'minFloatValue' => null,
                    ],
                ],
            ],
            [
                [
                    'FloatValueValidator' => [
                        'minFloatValue' => .23,
                    ],
                ],
            ],
            [
                [
                    'FloatValueValidator' => [
                        'maxFloatValue' => null,
                    ],
                ],
            ],
            [
                [
                    'FloatValueValidator' => [
                        'maxFloatValue' => .23,
                    ],
                ],
            ],
            [
                [
                    'FloatValueValidator' => [
                        'minFloatValue' => .23,
                        'maxFloatValue' => .42,
                    ],
                ],
            ],
        ];
    }

    /**
     * Provide data sets with validator configurations which are considered
     * invalid by the {@link validateValidatorConfiguration()} method. The
     * method must return a non-empty array of validation errors when receiving
     * one of the provided values.
     *
     * Returns an array of data provider sets with a single argument: A valid
     * set of validator configurations.
     *
     * For example:
     *
     * <code>
     *  return array(
     *      array(
     *          array(
     *              'NonExistentValidator' => array(),
     *          ),
     *      ),
     *      array(
     *          array(
     *              // Typos
     *              'InTEgervALUeVALIdator' => array(
     *                  'iinIntegerValue' => 0,
     *                  'maxIntegerValue' => 23,
     *              )
     *          )
     *      ),
     *      array(
     *          array(
     *              'IntegerValueValidator' => array(
     *                  // Incorrect value types
     *                  'minIntegerValue' => true,
     *                  'maxIntegerValue' => false,
     *              )
     *          )
     *      ),
     *      // ...
     *  );
     * </code>
     *
     * @return array
     */
    public function provideInvalidValidatorConfiguration()
    {
        return [
            [
                [
                    'NonExistentValidator' => [],
                ],
            ],
            [
                [
                    'FloatValueValidator' => [
                        'nonExistentValue' => .23,
                    ],
                ],
            ],
            [
                [
                    'FloatValueValidator' => [
                        'minFloatValue' => 'foo',
                    ],
                ],
            ],
            [
                [
                    'FloatValueValidator' => [
                        'maxFloatValue' => 'bar',
                    ],
                ],
            ],
        ];
    }

    protected function provideFieldTypeIdentifier()
    {
        return 'ezfloat';
    }

    public function provideDataForGetName(): array
    {
        return [
            [$this->getEmptyValueExpectation(), '', [], 'en_GB'],
            [new FloatValue(23.42), '23.42', [], 'en_GB'],
        ];
    }

    /**
     * Provides data sets with validator configuration and/or field settings and
     * field value which are considered valid by the {@link validate()} method.
     *
     * ATTENTION: This is a default implementation, which must be overwritten if
     * a FieldType supports validation!
     *
     * For example:
     *
     * <code>
     *  return array(
     *      array(
     *          array(
     *              "validatorConfiguration" => array(
     *                  "StringLengthValidator" => array(
     *                      "minStringLength" => 2,
     *                      "maxStringLength" => 10,
     *                  ),
     *              ),
     *          ),
     *          new TextLineValue( "lalalala" ),
     *      ),
     *      array(
     *          array(
     *              "fieldSettings" => array(
     *                  'isMultiple' => true
     *              ),
     *          ),
     *          new CountryValue(
     *              array(
     *                  "BE" => array(
     *                      "Name" => "Belgium",
     *                      "Alpha2" => "BE",
     *                      "Alpha3" => "BEL",
     *                      "IDC" => 32,
     *                  ),
     *              ),
     *          ),
     *      ),
     *      // ...
     *  );
     * </code>
     *
     * @return array
     */
    public function provideValidDataForValidate()
    {
        return [
            [
                [
                    'validatorConfiguration' => [
                        'FloatValueValidator' => [
                            'minFloatValue' => 5.1,
                            'maxFloatValue' => 10.5,
                        ],
                    ],
                ],
                new FloatValue(7.5),
            ],
        ];
    }

    /**
     * Provides data sets with validator configuration and/or field settings,
     * field value and corresponding validation errors returned by
     * the {@link validate()} method.
     *
     * ATTENTION: This is a default implementation, which must be overwritten
     * if a FieldType supports validation!
     *
     * For example:
     *
     * <code>
     *  return array(
     *      array(
     *          array(
     *              "validatorConfiguration" => array(
     *                  "IntegerValueValidator" => array(
     *                      "minIntegerValue" => 5,
     *                      "maxIntegerValue" => 10
     *                  ),
     *              ),
     *          ),
     *          new IntegerValue( 3 ),
     *          array(
     *              new ValidationError(
     *                  "The value can not be lower than %size%.",
     *                  null,
     *                  array(
     *                      "%size%" => 5
     *                  ),
     *              ),
     *          ),
     *      ),
     *      array(
     *          array(
     *              "fieldSettings" => array(
     *                  "isMultiple" => false
     *              ),
     *          ),
     *          new CountryValue(
     *              "BE" => array(
     *                  "Name" => "Belgium",
     *                  "Alpha2" => "BE",
     *                  "Alpha3" => "BEL",
     *                  "IDC" => 32,
     *              ),
     *              "FR" => array(
     *                  "Name" => "France",
     *                  "Alpha2" => "FR",
     *                  "Alpha3" => "FRA",
     *                  "IDC" => 33,
     *              ),
     *          )
     *      ),
     *      array(
     *          new ValidationError(
     *              "Field definition does not allow multiple countries to be selected."
     *          ),
     *      ),
     *      // ...
     *  );
     * </code>
     *
     * @return array
     */
    public function provideInvalidDataForValidate()
    {
        return [
            [
                [
                    'validatorConfiguration' => [
                        'FloatValueValidator' => [
                            'minFloatValue' => 5.1,
                            'maxFloatValue' => 10.5,
                        ],
                    ],
                ],
                new FloatValue(3.2),
                [
                    new ValidationError(
                        'The value can not be lower than %size%.',
                        null,
                        [
                            '%size%' => 5.1,
                        ],
                        'value'
                    ),
                ],
            ],
            [
                [
                    'validatorConfiguration' => [
                        'FloatValueValidator' => [
                            'minFloatValue' => 5.1,
                            'maxFloatValue' => 10.5,
                        ],
                    ],
                ],
                new FloatValue(13.2),
                [
                    new ValidationError(
                        'The value can not be higher than %size%.',
                        null,
                        [
                            '%size%' => 10.5,
                        ],
                        'value'
                    ),
                ],
            ],
            [
                [
                    'validatorConfiguration' => [
                        'FloatValueValidator' => [
                            'minFloatValue' => 10.5,
                            'maxFloatValue' => 5.1,
                        ],
                    ],
                ],
                new FloatValue(7.5),
                [
                    new ValidationError(
                        'The value can not be higher than %size%.',
                        null,
                        [
                            '%size%' => 5.1,
                        ],
                        'value'
                    ),
                    new ValidationError(
                        'The value can not be lower than %size%.',
                        null,
                        [
                            '%size%' => 10.5,
                        ],
                        'value'
                    ),
                ],
            ],
        ];
    }
}
