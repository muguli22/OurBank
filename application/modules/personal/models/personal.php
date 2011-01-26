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
class Personal_Model_personal extends Zend_Db_Table {
    protected $_name = 'ourbank_membername';

    public function getGender() {

         $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_gender');
         return $result = $this->fetchAll($select);
    }

    public function getMaritalStatus() {

         $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_membermaritalstatus');
         return $result = $this->fetchAll($select);
    }

     public function getPhysicalStatus() {

         $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_memberphysicalstatus');
         return $result = $this->fetchAll($select);
    }
    

}