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

class Summaryloan_Model_Summaryloan extends Zend_Db_Table
{
    protected $_name = 'ob_installmentdetails';


/*     
    public function accountloandetailsSearch($input)
    {
         $db = $this->getAdapter();
         $sql = "SELECT  
                count(C.account_id) as noofAcc, 
                D.activity_name as activity_name,
                B.loan_amount,
                C.account_id,
                A.installment_status,
                A.accountinstallment_amount
                FROM
                ourbank_installmentdetails A,
                ourbank_loanaccounts B,
		ourbank_accounts C,
		ob_activity_details D

                where
               (E.office_id = '".$input['office_id']."' OR
                A.accountinstallment_date = '".$input["accountinstallment_date1"]."' OR
                A.accountinstallment_date = '".$input["accountinstallment_date"]."') &&
                (D.memberbranch_id = E.office_id) && 
		(A.account_id = B.account_id) && 
                (B.account_id = C.account_id) && 
                (C.activity_id = D.activity_id) &&
                (C.accountstatus_id = 3 || C.accountstatus_id = 1) &&
                (D.recordstatus_id = 3 || D.recordstatus_id = 1) ";

         $result=$db->fetchAll($sql,array($input['office_id'],$input['accountinstallment_date1'],$input['accountinstallment_date']));
        return $result;
    }

    public function accountdetailsSearch($input = array())
    {
            $db = $this->getAdapter();
         $sql = "SELECT  
                C.account_number,
                F.memberfirstname as Name,
                G.memberaddressline1 as Address,
                B.loan_amount,
                C.account_id
                
                FROM
               
                ourbank_loanaccounts B,
		ourbank_accounts C,
		ourbank_members D,
                ourbank_officenames E,
		ourbank_membername F,
		ourbank_memberaddress G
                where
            
                (D.memberbranch_id = E.office_id) && 
		E.office_id = '".$input['office_id']."' &&
                (B.account_id = C.account_id) && 
                (C.member_id = D.member_id) &&
		(D.member_id = F.member_id) &&
		(G.member_id = F.member_id) &&
                (F.recordstatus_id = 3) &&
                (G.recordstatus_id = 3) &&
                (C.accountstatus_id = 3 || C.accountstatus_id = 1) &&
                (E.recordstatus_id = 3 || E.recordstatus_id = 1) ";

        $result = $db->fetchAll($sql,array($input['office_id']));
        return $result;
    }*/

    public function accountloandetails()
    {
         $db = $this->getAdapter();
         $sql = "SELECT  
                D.activity_name as activity_name,
                B.loan_amount,
                C.account_id,
                A.installment_status,
                A.accountinstallment_amount
                FROM
                ob_installmentdetails A,
                ob_loan_accounts B,
		ob_accounts C,
		ob_activity_details D
		where
                (A.account_id = B.account_id) && 
                (B.account_id = C.account_id) && 
                (C.activity_id = D.activity_id) &&
                (C.accountstatus_id = 3 || C.accountstatus_id = 1) &&
                (D.recordstatus_id = 3 || D.recordstatus_id = 1) 
                group by(C.activity_id) ";

         return $db->fetchAll($sql,array());
    }
      
    public function accountdetails()
    {
        $db = $this->getAdapter();
         $sql = "SELECT  
                D.activity_name as activity_name,
                B.loan_amount,
                C.account_id,
                A.installment_status,
                A.accountinstallment_amount
                FROM
                ob_installmentdetails A,
                ob_loan_accounts B,
		ob_accounts C,
		ob_activity_details D
		where
                (A.account_id = B.account_id) && 
                (B.account_id = C.account_id) && 
                (C.activity_id = D.activity_id) &&
                (C.accountstatus_id = 3 || C.accountstatus_id = 1) &&
                (D.recordstatus_id = 3 || D.recordstatus_id = 1) 
                group by(C.activity_id) ";

        $result = $db->fetchAll($sql);
        return $result;
    }


}
