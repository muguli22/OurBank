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

class Amounttransfer_Model_Amounttransfer extends Zend_Db_Table
{
    protected $_name = 'ourbank_fee';

  
//         $sql = "Select A.name as name, 
//                        D.name as offerproductname,    
//                        B.code as familycode,
//                        C.account_number,
//                        E.name as office_name,
//                        C.id as account_id
//                        from 
//                        ourbank_familymember A,
// 						ourbank_family B,
//                        ourbank_accounts C,
//                        ourbank_productsoffer D,
//                        ourbank_office E
//                     WHERE
//                         (C.account_number = '$accountNo') AND
//                         (C.status_id = '3' OR C.status_id = '1') AND
//                         (C.product_id = D.id) AND
//                         (A.id = C.member_id) AND
//                         (E.id = A.village_id) 
// 
//                     UNION
//                 Select A.name as groupname, 
//                        D.name as offerproductname,    
//                        B.familycode as membercode,
//                        C.account_number,
//                        E.name as office_name,
//                        C.id as account_id
//                        from 
//                        ourbank_group A,
//                        ourbank_familymember B,
//                        ourbank_accounts C,
//                        ourbank_productsoffer D,
//                        ourbank_office E
// 
//                 WHERE
//                 (C.account_number = '$accountNo') AND
//                         (C.status_id = '3' OR C.status_id = '1') AND
//                         (C.product_id = D.id) AND
//                         (B.id  = C.member_id) AND
//                         (A.id  = B.id) AND
//                         (E.id = B.village_id ) ";
// 
//         $result = $db->fetchAll($sql);
//         return $result;
  public function searchpersnolsavings($accountNo) 
    {
$select=$this->select()
                ->setIntegrityCheck(false)
			->join(array('a' => 'ourbank_accounts'),array('a.id'),array('account_number','id as account_id','product_id'))
			                ->where('a.account_number=?',$accountNo)
			->join(array('b' => 'ourbank_familymember'),'a.member_id = b.id','name as membername');

 		//die($select->__toString($select));
// 
		$result = $this->fetchAll($select);
		return $result->toArray();


    }
    
    public function balance($accountNo) 
    {
        $db = $this->getAdapter();
        $sql = "SELECT 
                (sum(amount_to_bank)-sum(amount_from_bank)) balance
                FROM 
                ourbank_transaction
                WHERE
                (account_id = '$accountNo') ";
        $result = $db->fetchAll($sql);
        return $result;
    }




    public function accidGlsubcode($accountNo) 
    {
        $db = $this->getAdapter();
        $sql = "SELECT 
                *
                FROM 
                ourbank_accounts A,
                ourbank_productsoffer B,
                ourbank_product C,
                ourbank_familymember D
                WHERE
                (A.account_number = '$accountNo') AND
                A.product_id = B.id AND
                C.id = B.product_id AND
                A.member_id = D.id";

        $result = $db->fetchAll($sql);
        return $result;
    }
   public function getaccount($id) {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_accounts'),array('id'))
			->where('a.account_number = ?',$id);
 		//die($select->__toString($select));

		$result = $this->fetchAll($select);
		return $result->toArray();
	}
}
