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
class Familydetails_IndexController extends Zend_Controller_Action 
{
    public function init() 
    {
        $this->view->pageTitle=$this->view->translate('Individual member');
        $globalsession = new App_Model_Users();
        $this->view->globalvalue = $globalsession->getSession();
        $this->view->createdby = $this->view->globalvalue[0]['id'];
        $this->view->username = $this->view->globalvalue[0]['username'];
//         if (($this->view->globalvalue[0]['id'] == 0)) {
//             $this->_redirect('index/logout');
//         }
        $this->view->adm = new App_Model_Adm();
    }
    
    public function indexAction() 
    {
    }
    
//add family member action
    public function addAction() 
    {
        $this->view->title = $this->view->translate("Add family information");
        $this->view->id=$member_id=$this->_getParam('id');
//loan family form
        $addForm = new Familydetails_Form_familydetails(1);
        $this->view->form=$addForm;

        if ($this->_request->isPost() && $this->_request->getPost('submit')) 
        {
            $formData = $this->_request->getPost();
            $totalmember=$this->_request->getParam('totalnumber');
//insert number of family member in the loop
            for($i=1;$i<=$totalmember;$i++)
            {   $name=$this->_request->getParam('name'.$i);
                $gender=$this->_request->getParam('gender'.$i);
                $age=$this->_request->getParam('age'.$i);
                $relationship=$this->_request->getParam('relationship'.$i);
                $physical=$this->_request->getParam('physicalstatus'.$i);
                $marital=$this->_request->getParam('maritalstatus'.$i);
                $this->view->adm->addRecord("ourbank_family",array('id' => '',
                                            'member_id'=>$member_id,
                                            'name' => $name,
                                            'gender'=>$gender,
                                            'age' => $age,
                                            'relationship'=>$relationship,
                                            'physicalstatus'=>$physical,
                                            'maritalstatus'=>$marital));
           
            }
           $this->_redirect('/individualmcommonview/index/commonview/id/'.$member_id);
        }
    }

//edit family member action
    public function editAction() 
    {
        $this->view->title = $this->view->translate("Edit family information");
        $this->view->memberid=$member_id=$this->_getParam('id');

//find count of family member
        $family_model=new Familydetails_Model_familydetails();
        $this->view->famil_details=$count_member = $family_model->edit_family($member_id);
        $this->view->family_number=$number=count($count_member);

//load family details form
        $addForm = new Familydetails_Form_familydetails($number);
        $this->view->form=$addForm;
        $gender = $this->view->adm->viewRecord("gender","id","DESC");
        $relationship = $this->view->adm->viewRecord("ourbank_familyrelationship","id","DESC");
        $physical= $this->view->adm->viewRecord("ourbank_physicalstatus","id","DESC");
        $marital = $this->view->adm->viewRecord("ourbank_maritalstatus","id","DESC");

//load gender,relationship, physical and marital status in the drop down list box
        for($i=1;$i<=$number;$i++){
        foreach($gender as $gender1){ 
        $gender_id = "gender".$i;
        $addForm->$gender_id->addMultiOption($gender1['id'],$gender1['sex']);
        }
        foreach($relationship as $relationship1){ 
        $relation_id = "relationship".$i;
        $addForm->$relation_id->addMultiOption($relationship1['id'],$relationship1['relationship']);
        }
        foreach($physical as $physical1){ 
        $physical_id = "physicalstatus".$i;
        $addForm->$physical_id->addMultiOption($physical1['id'],$physical1['physicalstatus']);
        }
        foreach($marital as $marital1){ 
        $marital_id = "maritalstatus".$i;
        $addForm->$marital_id->addMultiOption($marital1['id'],$marital1['maritalstatus']);
        }
        }

// Set the family details in the family details form.
        $famid =  array();
        $i=1;
        foreach($count_member as $family_details){
            $a='name'.$i;
            $b='age'.$i;
            $c='gender'.$i;
            $d='relationship'.$i;
            $e='physicalstatus'.$i;
            $f='maritalstatus'.$i;
            $g='familymember_id'.$i;
            $addForm->$a->setValue($family_details['name']);
            $addForm->$b->setValue($family_details['age']);
            $addForm->$c->setValue($family_details['gender']);
            $addForm->$d->setValue($family_details['relationship']);
            $addForm->$e->setValue($family_details['physicalstatus']);
            $addForm->$f->setValue($family_details['maritalstatus']);
            $addForm->$g->setValue($family_details['id']);
	    $value[]=$family_details['id'];
            $i++;
         }
         $this->view->value=$value;

        if ($this->_request->isPost() && $this->_request->getPost('submit')) 
        {
            $formData = $this->_request->getPost(); 

//update exiting family details
            for($i=1;$i<=$number;$i++)
            {   
		$familymember_id=$this->_request->getParam('familymember_id'.$i);
		$name=$this->_request->getParam('name'.$i);
                $gender=$this->_request->getParam('gender'.$i);
                $age=$this->_request->getParam('age'.$i);
                $relationship=$this->_request->getParam('relationship'.$i);
                $physical=$this->_request->getParam('physicalstatus'.$i);
                $marital=$this->_request->getParam('maritalstatus'.$i);
                $family_model->updatefamily($familymember_id,array(
                                            'member_id'=>$member_id,
                                            'name' => $name,
                                            'gender'=>$gender,
                                            'age' => $age,
                                            'relationship'=>$relationship,
                                            'physicalstatus'=>$physical,
                                            'maritalstatus'=>$marital));
            }

//adding additional family details.
	    $k=$number+1;
	    $new=$this->_request->getParam('number');
	    $j=$number+$new;
	    for($k;$k<=$j;$k++)
            {   $name1=$this->_request->getParam('name'.$k);
                $gender1=$this->_request->getParam('gender'.$k);
                $age1=$this->_request->getParam('age'.$k);
                $relationship1=$this->_request->getParam('relationship'.$k);
                $physical1=$this->_request->getParam('physicalstatus'.$k);
                $marital1=$this->_request->getParam('maritalstatus'.$k);
                $this->view->adm->addRecord("ourbank_family",array('id' => '',
                                            'member_id'=>$member_id,
                                            'name' => $name1,
                                            'gender'=>$gender1,
                                            'age' => $age1,
                                            'relationship'=>$relationship1,
                                            'physicalstatus'=>$physical1,
                                            'maritalstatus'=>$marital1));
            }
           $this->_redirect('/individualmcommonview/index/commonview/id/'.$member_id);
        }}

//This getfamilymemberAction is used to loan family form when calling the ajax function from the add action
    public function getfamilymembersAction()
    {
        $this->_helper->layout()->disableLayout();
        $number=$this->_request->getParam('id');
	$this->view->number=$number;
        $addForm = new Familydetails_Form_familydetails($number);
        $this->view->form=$addForm;
        $gender = $this->view->adm->viewRecord("gender","id","DESC");
        $relationship = $this->view->adm->viewRecord("ourbank_familyrelationship","id","DESC");
        $physical= $this->view->adm->viewRecord("ourbank_physicalstatus","id","DESC");
        $marital = $this->view->adm->viewRecord("ourbank_maritalstatus","id","DESC");

//load gender,relationship, physical and marital status in the drop down list box
        for($i=1;$i<=$number;$i++){
        foreach($gender as $gender1){ 
        $gender_id = "gender".$i;
        $addForm->$gender_id->addMultiOption($gender1['id'],$gender1['sex']);
        }
        foreach($relationship as $relationship1){ 
        $relation_id = "relationship".$i;
        $addForm->$relation_id->addMultiOption($relationship1['id'],$relationship1['relationship']);
        }
        foreach($physical as $physical1){ 
        $physical_id = "physicalstatus".$i;
        $addForm->$physical_id->addMultiOption($physical1['id'],$physical1['physicalstatus']);
        }
        foreach($marital as $marital1){ 
        $marital_id = "maritalstatus".$i;
        $addForm->$marital_id->addMultiOption($marital1['id'],$marital1['maritalstatus']);
        }
        }
        $this->view->memberid=$member_id=$this->_request->getParam('memberid');
        $member = $this->view->adm->editRecord("ourbank_member",$member_id);
        $addForm->name1->setValue($member['0']['name']);
        $addForm->name1->setAttrib('readonly','');
        $addForm->gender1->setValue($member[0]['gender']);
        $addForm->gender1->setAttrib('readonly','');
    }

//This getfamilymemberAction is used to loan family form when calling the ajax function from the edit action
    public function getfamilymemberseditAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->view->newvalue=$number=$this->_request->getParam('id');
	$this->view->oldcount=$count=$this->_request->getParam('count');
	$this->view->number=$number;
        $addForm = new Familydetails_Form_familydetailsedit($count,$number);
        $this->view->form=$addForm;
        $gender = $this->view->adm->viewRecord("gender","id","DESC");
        $relationship = $this->view->adm->viewRecord("ourbank_familyrelationship","id","DESC");
        $physical= $this->view->adm->viewRecord("ourbank_physicalstatus","id","DESC");
        $marital = $this->view->adm->viewRecord("ourbank_maritalstatus","id","DESC");
        $i=$count+1;
        $j=$count+$number;
//load gender,relationship, physical and marital status in the drop down list box
        for($i;$i<=$j;$i++){
        foreach($gender as $gender1){ 
        $gender_id = "gender".$i;
        $addForm->$gender_id->addMultiOption($gender1['id'],$gender1['sex']);
        }
        foreach($relationship as $relationship1){ 
        $relation_id = "relationship".$i;
        $addForm->$relation_id->addMultiOption($relationship1['id'],$relationship1['relationship']);
        }
        foreach($physical as $physical1){ 
        $physical_id = "physicalstatus".$i;
        $addForm->$physical_id->addMultiOption($physical1['id'],$physical1['physicalstatus']);
        }
        foreach($marital as $marital1){ 
        $marital_id = "maritalstatus".$i;
        $addForm->$marital_id->addMultiOption($marital1['id'],$marital1['maritalstatus']);
        }
        }
    }

//delete individual family member details from the family
    public function deletememberAction()
    {
        $delete_id=$this->_request->getParam('id');
        $member_id=$this->_request->getParam('member_id');
        $family_model=new Familydetails_Model_familydetails();
        $family_model->deleteRecord('ourbank_family',$delete_id);
        $this->_redirect('/individualmcommonview/index/commonview/id/'.$member_id);
    }
}
