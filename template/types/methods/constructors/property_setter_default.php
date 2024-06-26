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

/** @var \DCarbone\PHPFHIR\Definition\Property $property */

$propertyTypeClassName = $property->getValueFHIRType()->getClassName();
$propertyFieldConst = $property->getFieldConstantName();
$propertyFieldConstExt = $property->getFieldConstantExtensionName();
$setter = $property->getSetterName();

ob_start(); ?>
        if (array_key_exists(self::<?php echo $propertyFieldConst; ?>, $data)) {
<?php if ($property->isCollection()) : ?>
            if (is_array($data[self::<?php echo $propertyFieldConst; ?>])) {
                foreach($data[self::<?php echo $propertyFieldConst; ?>] as $v) {
                    if ($v instanceof <?php echo $propertyTypeClassName; ?>) {
                        $this-><?php echo $setter; ?>($v);
                    } else {
                        $this-><?php echo $setter; ?>(new <?php echo $propertyTypeClassName; ?>($v));
                    }
                }
            } elseif ($data[self::<?php echo $propertyFieldConst; ?>] instanceof <?php echo $propertyTypeClassName; ?>) {
                $this-><?php echo $setter; ?>($data[self::<?php echo $propertyFieldConst; ?>]);
            } else {
                $this-><?php echo $setter; ?>(new <?php echo $propertyTypeClassName; ?>($data[self::<?php echo $propertyFieldConst; ?>]));
            }
<?php else : ?>
            if ($data[self::<?php echo $propertyFieldConst; ?>] instanceof <?php echo $propertyTypeClassName; ?>) {
                $this-><?php echo $setter; ?>($data[self::<?php echo $propertyFieldConst; ?>]);
            } else {
                $this-><?php echo $setter; ?>(new <?php echo $propertyTypeClassName; ?>($data[self::<?php echo $propertyFieldConst; ?>]));
            }
<?php endif; ?>
        }
<?php
return ob_get_clean(); ?>
