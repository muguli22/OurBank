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
class Economic_Model_economic  extends Zend_Db_Table {
    protected $_name = 'ob_member';
    //get the economic details with respective individual member...
    public function edit_economic($member_id)
    {
       $select=$this->select()
                        ->setIntegrityCheck(false)
                        ->join(array('a'=>'ourbank_familyeconomic'),array('a.id'))
                        ->where('a.member_id=?',$member_id);
       $result=$this->fetchAll($select);
       return $result->toArray();
       //die ($select->__toString($select));
    }

    // update the economic details with respective individual member...
    public function updateeconomic($memberId,$input = array()) {
    $where[] = "familymember_id = '".$memberId."'";
    $db = $this->getAdapter();
    $result = $db->update('ourbank_familyeconomic',$input,$where);
    }

}
