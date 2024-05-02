<?php declare(strict_types=1);

/*
 * Copyright 2018-2024 Daniel Carbone (daniel.p.carbone@gmail.com)
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

use DCarbone\PHPFHIR\Enum\TypeKind;

/** @var \DCarbone\PHPFHIR\Config\VersionConfig $config */
/** @var \DCarbone\PHPFHIR\Definition\Type $type */
/** @var \DCarbone\PHPFHIR\Definition\Type|null $parentType */
/** @var \DCarbone\PHPFHIR\Definition\Property[] $localProperties */

ob_start();
// TODO(@dcarbone): improve efficiency here a bit

// this logic is repeated as we must set attributes before defining child elements.

foreach ($localProperties as $property) {
    if ($property->isCollection()) {
        continue;
    }
    $pt = $property->getValueFHIRType();
    if (null === $pt) {
        echo require_with(
            __DIR__ . DIRECTORY_SEPARATOR . 'body_untyped.php',
            [
                'config' => $config,
            ]
        );
    } else if ($pt->hasPrimitiveParent() || $pt->getKind() === TypeKind::PRIMITIVE) {
        echo require_with(
            __DIR__ . DIRECTORY_SEPARATOR . 'body_typed.php',
            [
                'config' => $config,
                'property' => $property,
            ]
        );
    }
}

if ($type->hasParentWithLocalProperties()) : ?>
        parent::xmlSerialize($xw, $config);
<?php endif;

foreach ($localProperties as $property) {
    $pt = $property->getValueFHIRType();
    if (!$property->isCollection() && (null === $pt || $pt->hasPrimitiveParent() || $pt->getKind() === TypeKind::PRIMITIVE)) {
        continue;
    }
    echo require_with(
        __DIR__ . DIRECTORY_SEPARATOR . 'body_typed.php',
        [
            'config' => $config,
            'property' => $property,
        ]
    );
}
return ob_get_clean();
