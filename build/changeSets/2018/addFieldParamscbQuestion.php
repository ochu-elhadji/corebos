<?php
/*************************************************************************************************
 * Copyright 2018 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
* Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
* file except in compliance with the License. You can redistribute it and/or modify it
* under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
* granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
* the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
* applicable law or agreed to in writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing
* permissions and limitations under the License. You may obtain a copy of the License
* at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
*************************************************************************************************/

class addFieldParamscbQuestion extends cbupdaterWorker {

	public function applyChange() {
		if ($this->hasError()) {
			$this->sendError();
		}
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			$moduleInstance = Vtiger_Module::getInstance('cbQuestion');
			if ($moduleInstance) {
				$this->sendMsg('This changeset add new blocks and fields to cbQuestion module');
				// Fields preparations
				$fieldLayout = array(
					'cbQuestion' => array(
						'Type Information' => array(
							'typeprops' => array(
								'columntype'=>'TEXT',
								'typeofdata'=>'V~O',
								'uitype'=>19,
								'label' => 'Type Properties',
								'displaytype'=>'1',
							),
						),
					),
				);
				$this->massCreateFields($fieldLayout);
				$this->ExecuteQuery('update vtiger_blocks set sequence=12 where tabid=? and blocklabel=?', array(getTabId('cbQuestion'), 'LBL_DESCRIPTION_INFORMATION'));
				$field = Vtiger_Field::getInstance('qtype', $moduleInstance);
				if ($field) {
					$field->delPicklistValues(array('BusinessMap' => 'Table'));
				}
				$this->sendMsg('Changeset '.get_class($this).' applied!');
				$this->markApplied(false);
			} else {
				$this->sendMsgError('Changeset '.get_class($this).' could not be applied yet. Please launch again.');
			}
		}
		$this->finishExecution();
	}
}