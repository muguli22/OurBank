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
class Fee_Indexcontroller extends Zend_Controller_Action
{
	public function init() 
	{
		$this->view->pageTitle='Fee';

       $storage = new Zend_Auth_Storage_Session();
       $data = $storage->read();
       if(!$data){
               $this->_redirect('index/login'); // once session get expired it will redirect to Login page
       }

       $sessionName = new Zend_Session_Namespace('ourbank');
       $userid=$this->view->createdby = $sessionName->primaryuserid; // get the stored session id

       $login=new App_Model_Users();
       $loginname=$login->username($userid);
       foreach($loginname as $loginname) {
           $this->view->username=$loginname['username']; // get the user name
       }
 
//         if (($this->view->globalvalue[0]['id'] == 0)) {
//              $this->_redirect('index/logout');
//         }
		$this->view->adm = new App_Model_Adm();   	

        $ledger = new Ledger_Model_Ledger();
        $ledger1 = $ledger->fetchAllLedger1();
        $flag = 0;
        $date=date("y/m/d H:i:s");

        foreach($ledger1 as $ledger2) { if((($ledger2->header) == 'Fee'))  $flag = 1; }
        if($flag == 0) {
            $glInsert = $ledger->insertGlcode(array('id' => '',
                        'glcode' => 'I01000', 'ledgertype_id' => 1,
                        'header' => 'Fee', 'description' => 'Fee in income',
                        'created_date' =>$date, 'created_by'=>1));
         
        }

	}
	 public function getcategoryAction()
    {
        $this->_helper->layout->disableLayout();

        $path = $this->view->baseUrl();
        $feeForm = new Fee_Form_Fee();
        $this->view->Form = $feeForm;
        $catID=$this->_request->getParam('selectedval');

        $fee = new Feecommon_Model_Feecommon();
        $cats=$fee->getcategory($catID);
       
         foreach($cats as $cats) {
           $feeForm->feefor_id->addMultiOption($cats['id'],$cats['name']);
        }
    }
	public function indexAction() 
	{
               
	$searchForm = new Fee_Form_Search();
		$this->view->form = $searchForm;
		$individual = new Fee_Model_Fee();
 $id=$this->_getParam('id');
			$this->view->id=$id;
		$page = $this->_getParam('page',1);
		$paginator = Zend_Paginator::factory($this->view->adm->viewRecord("ourbank_fee","id","DESC"));

	if ($this->_request->isPost() && $this->_request->getPost('Search')) {
			$formData = $this->_request->getPost();
			if ($this->_request->isPost()) {
				$formData = $this->_request->getPost();
				if ($searchForm->isValid($formData)) {
					$result = $individual->feeSearch($searchForm->getValues());
					$page = $this->_getParam('page',1);
					$paginator = Zend_Paginator::factory($result);
		$this->view->paginator = $paginator;
				} 
				if (!$result){
					echo "<font color='RED'>Records Not Found Try Again...</font>";
				}
			}
		}
		$paginator->setItemCountPerPage($this->view->adm->paginator());
		$paginator->setCurrentPageNumber($page);
		$this->view->paginator = $paginator;

	}
public function viewAction() 
	{
		//Acl
//         $access = new App_Model_Access();
//         $checkaccess = $access->accessRights('Fee',$this->view->globalvalue[0]['name'],'viewAction');
// 		if (($checkaccess != NULL)) {
			$id=$this->_request->getParam('id');
		$this->view->id = $id;
			//$form = new Commonviewfee_Form_Feedetails();
			//$this->view->form=$form;
			$fee = new Fee_Model_Fee;
			$this->view->feedetails=$fee->getFee($id);
			$this->view->fetchfee=$fee->fetchfee($id);
// 		} else {
//             $this->_redirect('index/error');
// 		}
	}	

	public function addAction() 
	{
//Acl
//         $access = new App_Model_Access();
//         $checkaccess = $access->accessRights('Fee',$this->view->globalvalue[0]['name'],'addAction');
//        	if (($checkaccess != NULL)) {
			$form = new Fee_Form_Fee();
			$this->view->form=$form;

			//$appliesTo = new Feecommon_Model_Feecommon();
$subOffice=new Fee_Model_Fee();
			$hierarchy_id = $this->view->adm->viewRecord("ourbank_officehierarchy","id","DESC");
			foreach($hierarchy_id as $hierarchy_id){
				$form->hierarchy_id->addMultiOption($hierarchy_id['id'],$hierarchy_id['type']);
			}
			
			$glcode = $this->view->adm->viewRecord("ourbank_feeamounttypes","id","DESC");
			foreach($glcode as $glcode){
				$form->amountype_id->addMultiOption($glcode['id'],$glcode['name']);
			}

			$glcode = $this->view->adm->viewRecord("ourbank_category","id","DESC");
			foreach($glcode as $glcode){
				$form->category_id->addMultiOption($glcode['id'],$glcode['name']);
			}
			$glcode = $this->view->adm->viewRecord("ourbank_feetype","id","DESC");
			foreach($glcode as $glcode){
				$form->feetype_id->addMultiOption($glcode['id'],$glcode['name']);
			}
 
      

// 			$this->view->categorydetails=$appliesTo->getcategory();
			//$this->view->memberdetails=$appliesTo->getmembertypes();



			if ($this->_request->isPost() && $this->_request->getPost('Submit')) {
				$formData = $this->_request->getPost();
				if ($this->_request->isPost()) {
					if ($form->isValid($formData)) {  
$maxid=$subOffice->findlastlevel();
        foreach($maxid as $maxid1) {
         $lastid=$maxid1->lastid;}

 for($j=1;$j<=1;$j++){
             $fetchglcodedetails=$this->view->adm->editRecord('ourbank_glcode',$lastid);

      $ledgertype_id = $fetchglcodedetails[0]['ledgertype_id'];
        $glcode = $fetchglcodedetails[0]['glcode'];
      $header = $fetchglcodedetails[0]['header'];
  $lastid;
           $ledger = new Officedefault_Model_officedefault();
           $genarateGlsub = $subOffice->genarateGlsubCode1($ledgertype_id,$lastid);
        $glsubcode=$genarateGlsub->id;

           if($glsubcode) {
               $ini=substr($glsubcode,0,1);
               $last=substr($glsubcode,1,5);
               $last+=1;
               $last = str_pad($last,5,0,STR_PAD_LEFT);
               $glsubcode=$ini.$last;
               $glsubcode;
           } else {
               $glcode1=$ledger->fetchGlcode($j);
               $glcode=$glcode1->glcode;
               $ini=substr($glcode,0,1);
               $last=substr($glcode,1,5);
               $last+=1;
               $last = str_pad($last,5,0,STR_PAD_LEFT);
               $glsubcode=$ini.$last;
               $glsubcode;
           }
                $createdate=$this->_request->getParam('createddate');


           $headername="Fee";
           $gInsert = $ledger->insertGlsubcode(array('id' => '',
                           'office_id'=>$formData['hierarchy_id'],
                           'glsubcode' => $glsubcode,
                           'glcode_id' => $lastid,
                           'subledger_id' => $ledgertype_id,
                           'header' =>$formData['name'],
                           'description' => $formData['description'],
                           'created_by'=>$this->view->createdby));
           }



$glsubcode_id=$subOffice->findmaxlevel();
        foreach($glsubcode_id as $glsubcode_id) {
         $glsubcode=$glsubcode_id->lastid;}




										$formdata1=array('name'=>$formData['name'],
                                    					'description'=>$formData['description'],
                                    					'hierarchy_id'=>$formData['hierarchy_id'],
                                    					'feetype_id'=> $formData['feetype_id'],
                                    					'glsubcode_id'=> $glsubcode,

                                    					'category_id'=>$formData['category_id'],
                                    					'amountype_id'=>$formData['amountype_id'],
                                    					'created_by'=>$this->view->createdby,
														'value'=>$formData['value']);						
						$id = $this->view->adm->addRecord("ourbank_fee",$formdata1);



			$this->_redirect("/fee");
   					}
				}
			}
// 		} else {
// 				$this->_redirect('index/error');
// 		}	
	}
	
	public function editfeedetailAction() 
	{
//Acl
//         $access = new App_Model_Access();
//         $checkaccess = $access->accessRights('Fee',$this->view->globalvalue[0]['name'],'viewAction');
// 		if (($checkaccess != NULL)) {
			$form = new Fee_Form_Fee();
			$this->view->form=$form;

			//$appliesTo = new Feecommon_Model_Feecommon();

			$hierarchy_id = $this->view->adm->viewRecord("ourbank_officehierarchy","id","DESC");
			foreach($hierarchy_id as $hierarchy_id){
				$form->hierarchy_id->addMultiOption($hierarchy_id['id'],$hierarchy_id['type']);
			}
			$glcode = $this->view->adm->viewRecord("ourbank_feeamounttypes","id","DESC");
			foreach($glcode as $glcode){
				$form->amountype_id->addMultiOption($glcode['id'],$glcode['name']);
			}
			$glcode = $this->view->adm->viewRecord("ourbank_category","id","DESC");
			foreach($glcode as $glcode){
				$form->category_id->addMultiOption($glcode['id'],$glcode['name']);
			}
			$glcode = $this->view->adm->viewRecord("ourbank_feetype","id","DESC");
			foreach($glcode as $glcode){
				$form->feetype_id->addMultiOption($glcode['id'],$glcode['name']);
			}
 

// 			$this->view->categorydetails=$appliesTo->getcategory();
//			$this->view->memberdetails=$appliesTo->getmembertypes();


			$id=$this->_getParam('id');
			$this->view->id=$id;
			$fee = new Fee_Model_Fee;

			$feedetails = $fee->getffee($id);
//print_r($feedetails);
			$this->view->feedetails=$feedetails;


			$details = $fee->fetchfee($id);
foreach($details as $fedetails) {
							$this->view->form->name->setValue($fedetails['name']);
								$this->view->form->description->setValue($fedetails['description']);
								$this->view->form->hierarchy_id->setValue($fedetails['hierarchy_id']);
								$this->view->form->feetype_id->setValue($fedetails['feetype_id']);
								$this->view->form->category_id->setValue($fedetails['category_id']);
								$this->view->form->amountype_id->setValue($fedetails['amountype_id']);
								$this->view->form->value->setValue($fedetails['value']);

						}
			if ($this->_request->isPost() && $this->_request->getPost('Update')) { 
if ($this->_request->isPost()) {
		$formData = $this->_request->getPost();
		if ($form->isValid($formData)) {

														$formdata1=array('name'=>$formData['name'],
                                    					'description'=>$formData['description'],
                                    					'hierarchy_id'=>$formData['hierarchy_id'],
                                    					'feetype_id'=> $formData['feetype_id'],
                                    					'category_id'=>$formData['category_id'],
                                    					'amountype_id'=>$formData['amountype_id'],
                                    					'created_by'=>$this->view->createdby,
														'value'=>$formData['value']);	
		
		            $editfee = $this->view->adm->editRecord("ourbank_fee",$id);
					$this->view->adm->updateLog("ourbank_fee_log",$editfee[0],$this->view->createdby);
					$this->view->adm->updateRecord("ourbank_fee",$id,$formdata1);
 			$this->_redirect("/fee");
	
			}}}
// 		} else {
//             $this->_redirect('index/error');
    }		

	public function deleteAction() 
	{
//  	$acl = new App_Model_Acl();
//     	$access = new App_Model_Access();
//      $role = $access->getRole($this->view->id);
// 
//      $accessid = $access->accessRights('Fee',$role,"deleteAction");
//      $checkaccess = $acl->isAllowed($role,'Fee',$accessid);
//      if(($role) && ($checkaccess != NULL)) {
 		$id=$this->_request->getParam('id');
// 		$modId=$this->_request->getParam('mod_id');
// 		$subId=$this->_request->getParam('sub_id');
 		$this->view->id=$id;
// 		$this->view->mod_id=$modId;
// 		$this->view->sub_id=$subId;
		//$individualcommon=new Feecommon_Model_Feecommon;
		$fee = new Fee_Model_Fee;
			$this->view->feedetails=$fee->getFee($id);
			$this->view->fetchfee=$fee->fetchfee($id);

 		$delform=new Fee_Form_Delete();
		$this->view->deleteform = $delform;
		if ($this->_request->isPost() && $this->_request->getPost('Delete')){
 $redirect = $this->view->adm->deleteRecord("ourbank_fee",$id);
					//update
            $this->_redirect('/fee');		}
	}
}
