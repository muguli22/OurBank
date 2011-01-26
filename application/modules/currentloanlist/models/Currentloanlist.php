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

class Currentloanlist_Model_Currentloanlist extends Zend_Db_Table
{
     protected $_name = 'ourbank_transaction';



	
    public function fetchloanDetails()
    {

        $select = $this->select()
		->setIntegrityCheck(false)  
				->join(array('a' => 'ourbank_loan_disbursement'),array('loandisbursement_id'))
                ->join(array('b' => 'ourbank_accounts'),'a.account_id = b.id')
				->join(array('c' => 'ourbank_productsoffer'),'b.product_id = c.id',array('name as offerproductname'))
                ->join(array('d' => 'ourbank_product'),'c.product_id = d.id',array('name as productname'))
                ->where('d.category_id = 2')
                ->join(array('e' => 'ourbank_member'),'e.id = b.member_id',array('name as memberfirstname'));
			
	return $this->fetchAll($select);
    }

}
