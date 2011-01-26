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

class Summarymfi_IndexController extends Zend_Controller_Action
{
	function init() { 
		$this->view->pageTitle = "Summary of MFI";
	$sessionName = new Zend_Session_Namespace('ourbank');
	$this->view->type = "others";
	$userid=$this->view->createdby = $sessionName->primaryuserid;
	$login=new App_Model_Users();
	$loginname=$login->username($userid);
	foreach($loginname as $loginname) {
	$this->view->username=$loginname['username'];
}
		$this->view->adm = new App_Model_Adm();

	}

	function indexAction() {
		 $savings= new Summarymfi_Model_Summary();
	$members = $savings->inactiveMemberDetails();
        
        $savings1= new Summarymfi_Model_Summary();
	$allmembers = $savings1->allMembers();
        
        $activeMembers = new Summarymfi_Model_Summary();
        $activemember = $activeMembers->activeMembers();
        
        $inactiveMembers = new Summarymfi_Model_Summary();
        $inactivemembers = $inactiveMembers->inactiveMembers();

        $savingAccounts = new Summarymfi_Model_Summary();
        $savingaccounts = $savingAccounts->savingAccounts();
        


        $loanAccounts = new Summarymfi_Model_Summary();
        $loanaccounts = $loanAccounts->loanAccounts();

        $totalLoans = new Summarymfi_Model_Summary();
        $totalloans = $totalLoans->totalLoans();

        $loanDisburse = new Summarymfi_Model_Summary();
        $loandisburse = $loanDisburse->loanDisburse();

        $loanRepay = new Summarymfi_Model_Summary();
        $loanrepay = $loanRepay->loanRepay();
        
        $rateLoan = new Summarymfi_Model_Summary();
        $rateloan = $rateLoan->rateLoan();

        $funders = new Summarymfi_Model_Summary();
        $Funders = $funders->funders();

        $fundings = new Summarymfi_Model_Summary();
        $Fundings = $fundings->fundings();
// 
        $totalFundings = new Summarymfi_Model_Summary();
        $totalfundings = $totalFundings->totalFundings();

         $query1 = new Summarymfi_Model_Summary();
// 
//         //reworked before queries
        $this->view->sql=$query1->query1();
        $this->view->sql1=$query1->query2();
        $this->view->sql2=$query1->query3();

        $this->view->savings = $members;
        $this->view->savings1 = $allmembers;
        $this->view->activeMembers = $activemember;
        $this->view->inactiveMembers = $inactivemembers;
        $this->view->savingAccounts = $savingaccounts;
        $this->view->loanAccounts = $loanaccounts;
        $this->view->totalLoans = $totalloans;
        $this->view->loanDisburse = $loandisburse;
         $this->view->rateLoan = $rateloan;
         $this->view->funders = $Funders;
        $this->view->fundings = $Fundings;
         $this->view->totalFundings = $totalfundings;
         $this->view->loanRepay = $loanrepay;

}

}
