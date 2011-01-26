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

class Loandisbursereport_IndexController extends Zend_Controller_Action
{
	function init() { 
		$this->view->pageTitle = "Loan Disburse Report";
		$this->view->type = "loans";
	}
	
	function indexAction() {
		$path = $this->view->baseUrl();
		$searchForm = new Loandisbursereport_Form_Search($path);
		$this->view->form = $searchForm;
//Calling Graceperiod Module to load Creditline names need to considered
		$fetchcreditline=new Graceperiod_Model_Graceperiod();
		$fetchcreditlineName=$fetchcreditline->fetchCreditlineName();

		foreach($fetchcreditlineName as $fetchcreditlineName) {
			$searchForm->field1->addMultiOption($fetchcreditlineName['creditline_id'],$fetchcreditlineName['creditline_name']);
		}
//Calling Meeting Module to load Bank names need to considered
		$fetchBanknames = new Meeting_Model_Meeting();
		$Groupname = $fetchBanknames->fetchGroupnamesForMeetingReport();
		foreach($Groupname as $Groupname) {
			$searchForm->field2->addMultiOption($Groupname['group_id'],$Groupname['group_name']);
		}
//=================================================================
		if ($this->_request->isPost() && $this->_request->getPost('Search')) {
			$formData = $this->_request->getPost();
			$this->view->creditline_id=$creditline_id = $this->_request->getParam('field1'); 
			$this->view->group_id=$group_id = $this->_request->getParam('field2');
			
			if ($searchForm->isValid($formData)) {
// 			echo "<pre>"; print_r($formData);

				$this->view->check=10;
				$fetchLoanDisburse=new Loandisbursereport_Model_Loandisbursereport();
				$result=$fetchLoanDisburse->getLoanDisburseall($creditline_id); 
// 				echo "<pre>"; print_r($result);
				if($result){
				foreach($result as $result1){}
				 $this->view->creditline_name=$result1['creditline_name'];
				$this->view->creditline_portfolio=$result1['creditline_portfolio'];
				}
				$page = $this->_getParam('page',1);
				$paginator = Zend_Paginator::factory($result);
				$paginator->setItemCountPerPage(10);
				$paginator->setCurrentPageNumber($page);
				$this->view->paginator = $paginator;
				
			}
		}

	}

	public function fetchgroupsAction() {
		$this->_helper->layout->disableLayout();

		$path = $this->view->baseUrl();
		
		$meetingreportForm = new Meetingreport_Form_Search($path);
		$this->view->meetingreportForm = $meetingreportForm;
		
		$bank_id=$this->_request->getParam('bank_id');
		$meeting = new Meeting_Model_Meeting();
		$office=$meeting->fetchGroupnames($bank_id);

		foreach($office as $office) {
			$meetingreportForm->field2->addMultiOption($office['group_id'],$office['group_name']);
		}
	}

	function viewtransactionAction() {
		
	}

	function reportdisplayAction() {
		
	}

	function reportviewAction() {
	}

	function pdftransactionAction() {
	}
}
