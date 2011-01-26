<?php
/*
############################################################################
#  This file is part of OurBank.
############################################################################
#  OurBank is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as
#  published by the Free Software Foundation, either version 3 of the
#  License, or (at your option) any later version.
############################################################################
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
############################################################################
#  You should have received a copy of the GNU Affero General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
############################################################################
*/
?>

<?php
/*
 *  create aearch form elements
 */
class Meetingreport_Form_Search extends Zend_Form {
   public function init() {
        Zend_Dojo::enableForm($this);
	}

        public function __construct($path) {
        Zend_Dojo::enableForm($this);
        parent::__construct($path);

	$field1 = new Zend_Form_Element_Select('field1');
	$field1->addMultiOption('','Select');
	$field1->setAttrib('class', 'txt_put');
	$field1->setRequired(true);
	$field1->setAttrib('onchange', 'getGroups(this.value,"'.$path.'")');
	$field1->setRegisterInArrayValidator(false);

	$field2 = new Zend_Form_Element_Select('field2');
	$field2->addMultiOption('','Select');
	$field2->setAttrib('class', 'txt_put');
	$field2->setRegisterInArrayValidator(false);

	$field3 = new Zend_Form_Element_Select('field3');
	$field3->addMultiOption('','Select');
	$field3->setAttrib('class', 'txt_put');
	$field3->setRegisterInArrayValidator(false);
	//add submit button
	$submit = new Zend_Form_Element_Submit('Search');
	//add element to form
	$this->addElements(array($field1,$field2,$field3,$submit));

    }
}
