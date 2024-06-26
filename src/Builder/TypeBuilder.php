<?php declare(strict_types=1);

namespace DCarbone\PHPFHIR\Builder;

/*
 * Copyright 2016-2024 Daniel Carbone (daniel.p.carbone@gmail.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use DCarbone\PHPFHIR\Config\VersionConfig;
use DCarbone\PHPFHIR\Definition\Property;
use DCarbone\PHPFHIR\Definition\Type;
use SimpleXMLElement;

/**
 * Class TypeBuilder
 * @package DCarbone\PHPFHIR\Definition\Builder
 */
abstract class TypeBuilder
{
    /**
     * @param \DCarbone\PHPFHIR\Config\VersionConfig $config
     * @param string $fhirName
     * @param \SimpleXMLElement $sxe
     * @param string $sourceFilename
     * @return \DCarbone\PHPFHIR\Definition\Type
     */
    private static function buildDefaultType(
        VersionConfig $config,
        string $fhirName,
        SimpleXMLElement $sxe,
        string $sourceFilename
    ): Type {
        return new Type($config, $fhirName, $sxe, $sourceFilename);
    }

    /**
     * @param \DCarbone\PHPFHIR\Config\VersionConfig $config
     * @param string $fhirName
     * @param \SimpleXMLElement $sxe
     * @param string $sourceFilename
     * @return \DCarbone\PHPFHIR\Definition\Type
     */
    private static function buildPrimitiveType(
        VersionConfig $config,
        string $fhirName,
        SimpleXMLElement $sxe,
        string $sourceFilename
    ): Type {
        $type = self::buildDefaultType($config, $fhirName, $sxe, $sourceFilename);
        $value = new Property($type, $sxe, $sourceFilename);
        $value->setName(PHPFHIR_VALUE_PROPERTY_NAME);
        $type->getLocalProperties()->addProperty($value);
        return $type;
    }

    /**
     * @param \DCarbone\PHPFHIR\Config\VersionConfig $config
     * @param string $fhirName
     * @param \SimpleXMLElement $sxe
     * @param string $sourceFilename
     * @return \DCarbone\PHPFHIR\Definition\Type
     */
    public static function build(VersionConfig $config, string $fhirName, SimpleXMLElement $sxe, string $sourceFilename): Type
    {
        if (str_contains($fhirName, PHPFHIR_PRIMITIVE_SUFFIX) || str_contains($fhirName, PHPFHIR_LIST_SUFFIX)) {
            return self::buildPrimitiveType($config, $fhirName, $sxe, $sourceFilename);
        }
        return self::buildDefaultType($config, $fhirName, $sxe, $sourceFilename);
    }
}