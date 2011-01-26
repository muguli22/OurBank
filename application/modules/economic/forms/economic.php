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
class Economic_Form_economic extends Zend_Form {
    public function init()
    {} 

    public function __construct($number) 
    {
         parent::__construct($number);
        //$number = number of family members
        //create economic form elements
        //$fieldtype,$fieldname,$table,$columnname,$cssname,$labelname,$required,$validationtype,$min,$max,$rows,$cols,$decorator,$value
        $formfield = new App_Form_Field ();
	 for($i=1;$i<=$number;$i++) {
        $name = $formfield->field('Text','name'.$i,'','','','',true,'','','','','',0,0);
        $name->setAttrib('readonly','');
        $health = $formfield->field('Select','health'.$i,'','','','',true,'','','','','',0,0);
	$treatment = $formfield->field('Select','treatment'.$i,'','','','',true,'','','','','',0,0);
        $Accessibility = $formfield->field('Select','accessability'.$i,'','','','',true,'','','','','',0,0);
        $familymemberid = $formfield->field('Hidden','familymemberid'.$i,'','','','','','','','','','',0,0);
        $this->addElements(array($name,$health,$treatment,$Accessibility,$familymemberid));
	}
    }
}
