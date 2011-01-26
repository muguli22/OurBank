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
class Loanrepayment_Model_loanrepayment extends Zend_Db_Table {
	protected $_name = 'ob_accounts';

	public function fetchLoanDisbursementDetails($account_id) {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ob_loan_disbursement'),array('transaction_id'))
			->where('a.account_id = ?',$account_id)
			->where('a.recordstatus_id = 3 or a.recordstatus_id = 1');;
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function noOfInstalmentPaied($accountId) {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ob_installmentdetails'),array('Installmentserial_id'))
			->where('a.account_id = ?',$accountId)
			->where('a.installment_status = 2');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function loanstilltopay($accountId) {
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$sql = "SELECT SUM(accountinstallment_amount) stilltopayamount FROM ob_installmentdetails where account_id=$accountId AND (installment_status = 4 or installment_status=3 or installment_status=5 or installment_status=6) AND recordstatus_id=3";
		$result = $this->db->fetchAll($sql);
		return $result;
	}

	public function fetchAll_paymenttype_idforloans() {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ob_paymenttypes'),array('paymenttype_id'))
			->where('a.paymenttype_id != 5');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function updatemainaccountstatus($account_id,$input = array()) {
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$where[] = "account_id = '".$account_id."'";
		$result = $this->db->update('ob_accounts',$input,$where);
	}

	public function updateloanaccountstatus($account_id,$input = array()) {
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$where[] = "account_id = '".$account_id."'";
		$result = $this->db->update('ob_loan_accounts',$input,$where);
	}

	public function loanInstalmentDetailsOfInstalmentNo($accountId,$InstalmentNumber) {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ob_installmentdetails'),array('Installmentserial_id'))
			->where('a.account_id = ?',$accountId)
			->where('a.accountinstallment_id = ?',$InstalmentNumber);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function loanInstalmentPaid($accountId,$InstalmentNumber) {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ob_loan_repayment'),array('loanrepayment_id'))
			->where('a.account_id = ?',$accountId)
			->where('a.loaninstallment_number = ?',$InstalmentNumber);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function loanInstalmentDetails($accountId) {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('A' => 'ob_installmentdetails'),array('Installmentserial_id'))
			->where('A.account_id = ?',$accountId)
			->where('A.installment_status = 4 or A.installment_status=3 or A.installment_status=5 or A.installment_status=2 or A.installment_status=8')
			->join(array('B' => 'ob_loan_accounts'),'A.account_id=B.account_id')
			->join(array('D' => 'ob_loan_disbursement'),'B.account_id=D.account_id')
			->join(array('S' => 'ob_installmentstatus'),'A.installment_status=S.installmentstatus_id');
		$result = $this->fetchAll($select);
		return $result->toArray();
// 		die($select->__toString($select));
	}

	public function instalmentStatus($accountId,$InstalmentNumber,$status) {
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$where1[] = "account_id = '".$accountId."'";
		$where1[] = "accountinstallment_id = '".$InstalmentNumber."'";
		$where1[] = "installment_status != 2";
		$input3=  array('installment_status' =>$status);
		$result1 = $this->db->update('ob_installmentdetails',$input3,$where1);
		return $result1;
	}

	public function overDueInstalment($accountId) {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ob_installmentdetails'),array('Installmentserial_id'))
			->where('a.account_id = ?',$accountId)
			->where('a.installment_status = 5')
			->where('a.recordstatus_id = 3 OR a.recordstatus_id = 1');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function penaltydetails($creditlineId) {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ob_penalty'),array('id'))
			->where('a.creditline_id = ?',$creditlineId)
			->where('a.status = 1');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}


	public function loanNextInstalmentDetails($accountId) {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('A' => 'ob_installmentdetails'),array('Installmentserial_id'))
			->where('A.account_id = ?',$accountId)
			->where('A.installment_status = 4 OR A.installment_status = 8 OR A.installment_status = 5')
			->join(array('B' => 'ob_loan_accounts'),'A.account_id=B.account_id')
			->join(array('D' => 'ob_loan_disbursement'),'B.account_id=D.account_id')
			->join(array('S' => 'ob_installmentstatus'),'A.installment_status=S.installmentstatus_id');
		$result = $this->fetchAll($select);
		return $result->toArray();
// 		die($select->__toString($select));
	}

	public function transactionInsert($input = array())
	{
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$result = $this->db->insert('ob_transaction',$input);
		return $this->db->lastInsertId('ob_transaction');
        }

	public function loanRepaymentInsert($input = array(),$installmentstatus,$loanrepaymentdate,$currentdue) {
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$result = $this->db->insert('ob_loan_repayment',$input);
		$result=$this->db->lastInsertId('ob_loan_repayment');

		$where[] = "account_id = '".$input['account_id']."'";
		$where[] = "recordstatus_id = '3' AND installment_status = '".$installmentstatus."'";


                if(($currentdue=='0') || ($installmentstatus=='5' && $currentdue=='0')) {
		  $input2=  array('installment_status' => '2','current_due' =>$currentdue,'paid_date' =>$loanrepaymentdate);
                } elseif($installmentstatus=='5' && $currentdue > '0') {
		  $input2=  array('installment_status' => '5','current_due' =>$currentdue,'paid_date' =>$loanrepaymentdate);
                }  elseif($currentdue > '0' && $installmentstatus!='5') {
		  $input2=  array('installment_status' => '8','current_due' =>$currentdue,'paid_date' =>$loanrepaymentdate);
                }
		$result1 = $this->db->update('ob_installmentdetails',$input2,$where);
		return $result;
	}

	public function nextdue($accountid) {
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$sql = "SELECT * FROM ob_installmentdetails where account_id=$accountid AND (recordstatus_id=3 or recordstatus_id=1) AND (installment_status=2 or installment_status=8 or installment_status=5)";
		$result = $this->db->fetchAll($sql);
		return $result;
	}

	public function nextdueinsert($account_id,$accountinstallment_id,$Installmentstatusid) {
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$where1[] = "account_id = '".$account_id."'";
		$where1[] = "accountinstallment_id  = '".$accountinstallment_id."' AND (recordstatus_id = '3' OR recordstatus_id = '1')";
                if($Installmentstatusid =='2') {
		  $input3=  array('installment_status' => '4');
                } else {
		  $input3=  array('installment_status' => '3');
                }
		$result = $this->db->update('ob_installmentdetails',$input3,$where1);
		return $result;
	}

	public function grouploanrepayment($input = array()) {
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$result = $this->db->insert('ob_groupmemberloan_repayment',$input);
		return $result;
	}

	public function updategroupmemberloanaccountstatus($account,$input = array()) {
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$where[] = "groupaccount_id = '".$account."'";
		$result = $this->db->update('ob_groupmembers_accounts',$input,$where);
	}
}
