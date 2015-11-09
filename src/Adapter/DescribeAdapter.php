<?php
/**
 * Vtiger Web Services PHP Client Library (vtwsphpclib)
 *
 * Inspired by vtwsclib – vtiger CRM Web Services Client Library version 1.4
 * Build with Guzzle. Thanks!
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015, Valentin Despa <info@vdespa.de>. All rights reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Valentin Despa <info@vdespa.de>
 * @copyright 2015 Valentin Despa <info@vdespa.de>
 * @license   The MIT License (MIT)
 */

namespace Vdespa\Vtiger\Adapter;

use Vdespa\Vtiger\Domain\Model\Field\ModuleField;
use Vdespa\Vtiger\Domain\Model\Field\ModuleFieldCollection;
use Vdespa\Vtiger\Domain\Model\Module;

class DescribeAdapter extends AbstractVtigerAdapter implements AdapterInterface {

    /**
     * @param $vTigerResponse
     * @return Module
     */
    public function transformFromVtiger($vTigerResponse)
    {
        $vTigerDescribe = $this->decodeResponse($vTigerResponse);

        $module = new Module($vTigerDescribe->name);
        $module
            ->setLabel($vTigerDescribe->label)
            ->setCreateable($vTigerDescribe->createable)
            ->setUpdateable($vTigerDescribe->updateable)
            ->setDeleteable($vTigerDescribe->deleteable)
            ->setRetrieveable($vTigerDescribe->retrieveable)
            ->setIdPrefix($vTigerDescribe->idPrefix)
            ->setEntity($vTigerDescribe->isEntity)
            ->setLabelFields($vTigerDescribe->labelFields)
            ->setFields($this->createModuleFields($vTigerDescribe->fields));

        return $module;
    }

    /**
     * @param array $fields
     * @return ModuleFieldCollection
     */
    private function createModuleFields(array $fields)
    {
        $moduleFields = new ModuleFieldCollection();

        foreach ($fields as $field)
        {
            $moduleField = new ModuleField($field->name, $field->type->name, $field->mandatory);
            $moduleField
                ->setLabel($field->label)
                ->setDefault($field->default)
                ->setNullable($field->nullable);
            $moduleFields->addField($moduleField);
        }

        return $moduleFields;
    }
}