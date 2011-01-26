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
class Attendance_IndexController extends Zend_Controller_Action
{
    public function init() 
    {
        $this->view->pageTitle = $this->view->translate("Attendance");

        $globalsession = new App_Model_Users();
        $this->view->globalvalue = $globalsession->getSession();
        $this->view->username = $this->view->globalvalue[0]['username'];
        $this->view->createdby = $this->view->globalvalue[0]['id'];
// 		if (($this->view->globalvalue[0]['id'] == 0)) {
// 			$this->_redirect('index/logout');
// 		}
        $this->view->adm = new App_Model_Adm();
        $this->view->dateconvert = new Creditline_Model_dateConvertor();

        $test = new DH_ClassInfo(APPLICATION_PATH . '/modules/attendanceindex/controllers/');
        $module = $test->getControllerClassNames();
        $modulename = explode("_", $module[0]);
        $this->view->modulename = strtolower($modulename[0]);
    }

    public function indexAction() 
    {
    }

    public function attendanceaddAction() 
    {
		//Acl
// 		$access = new App_Model_Access();
// 		$checkaccess = $access->accessRights('Attendance',$this->view->globalvalue[0]['name'],'attendanceaddAction');
// 		if (($checkaccess != NULL)) {

		//add
        $path = $this->view->baseUrl();
        $attendanceform=new Attendance_Form_Attendance($path);
        $this->view->attendanceform=$attendanceform;
        $flag=1;

        $meeting = $this->view->adm->viewRecord("ob_meeting","id","DESC");

        foreach($meeting as $meeting1){
            $attendanceform->meeting_name->addMultiOption($meeting1['id'],$meeting1['name']);
        }

        $insertattendance=new Attendance_Model_Attendance();
        $Meeting_id_From_attendance=$insertattendance->fetchMeetingIDforComparision();
        if ($this->_request->isPost() && $this->_request->getPost('Submit')) {
            $formData = $this->_request->getPost();
            if ($attendanceform->isValid($formData)) {
                $insertattendance=new Attendance_Model_Attendance();
                $member_id=array();
                $formData = $this->_request->getPost();
                $member_id=$this->_request->getPost('member_id');
                $lastid=$this->view->adm->lastinsertedID('ob_attendance');
                foreach($lastid as $lastid1) { $lastid2=$lastid1['max(id)'];}
                    if($lastid2){ $lastid2++; }
                    else { $lastid2=1;}

                    if($member_id) {
                            for($aa=0;$aa<sizeof($member_id);$aa++) {
                                    $insertattendance->insertattendance($formData,$member_id[$aa],$lastid2,$this->view->createdby);
                            }
                    } else {
                    $insertattendance->insertattendance($formData,'0',$lastid2,$this->view->createdby);
                    }
                $this->_redirect('attendanceindex/index');
            }
        }
// 		} else {
// 		$this->_redirect('index/index');
// 		}
    }

    public function attendanceeditAction() 
    {
		//Acl
// 		$acl = new App_Model_Acl();
// 		$access = new App_Model_Access();
// 		$role = $access->getRole($this->view->id);
// 		
// 		$accessid = $access->accessRights('Attendance',$role,"attendanceeditAction");
// 		$checkaccess = $acl->isAllowed($role,'Attendance',$accessid);
// 		if(($role) && ($checkaccess != NULL)) {

		//edit
        $path = $this->view->baseUrl();
        $attendanceform=new Attendance_Form_Attendance($path);
        $this->view->attendanceform=$attendanceform;

        $this->view->attendance_id=$attendance_id=$this->_request->getParam('attendance_id');

        $meeting = new Meeting_Model_Meeting();
        $result = $meeting->fetchAllmeetingdetails();

        $convertdate = new Creditline_Model_dateConvertor();//Object for date convertor

        $fetchattendance=new Attendance_Model_Attendance();
        $fetchattendance1=$fetchattendance->fetchattendancedetailsforID($attendance_id);

        foreach($fetchattendance1 as $fetchattendance2) { $getMemberID[]=$fetchattendance2['member_id'];}

        foreach($fetchattendance1 as $fetchattendance1) {
            foreach($result as $result) {
                if($result['id'] == $fetchattendance1['meeting_id']){
            $attendanceform->meeting_name->addMultiOption($result['id'],$result['name']);}
        }
            $this->view->attendanceform->meeting_name->setValue($fetchattendance1['meeting_id']);
            $this->view->attendanceform->meeting_date->setValue($convertdate->phpnormalformat($fetchattendance1['meeting_date']));
        }
        //Fetches all the memberes in that group of particular meeting ID
        echo "<script>getMembers(".$fetchattendance1['meeting_id'].",'".$path."',".$attendance_id.");</script>";

        if($this->_request->isPost() && $this->_request->getPost('Update')) {
            $attendance_id=$this->_request->getParam('attendance_id');

            $updateAttendance=new Attendance_Model_Attendance();

            $member_id=array();
            $formData = $this->_request->getPost();
            $member_id=$this->_request->getPost('member_id');

            $fetch_attendance=$this->view->adm->editrecord('ob_attendance',$attendance_id);

            foreach($fetch_attendance as $fetch_attendance1) {
                $this->view->adm->addRecord('ob_attendance_log',$fetch_attendance1);
            }
            $this->view->adm->deleteRecord('ob_attendance',$attendance_id);

            if($member_id) {
                for($aa=0;$aa<sizeof($member_id);$aa++) {
                        $updateAttendance->insertattendance($formData,$member_id[$aa],$attendance_id,$this->view->createdby);
                    }
            } else {
            $updateAttendance->insertattendance($formData,'0',$attendance_id,$this->view->createdby);
            }
            $this->_redirect('attendanceindex/index');
        }

// 		} else {
// 		$this->_redirect('index/index');
// 		}
	}

	public function attendanceviewAction() {
	}

    public function attendancedeleteAction() 
    {
		//Acl
// 		$acl = new App_Model_Acl();
// 		$access = new App_Model_Access();
// 		$role = $access->getRole($this->view->id);
// 		
// 		$accessid = $access->accessRights('Attendance',$role,"attendanceeditAction");
// 		$checkaccess = $acl->isAllowed($role,'Attendance',$accessid);
// 		if(($role) && ($checkaccess != NULL)) {

		//edit
        $this->view->attendance_id=$attendance_id=$this->_request->getParam('attendance_id');
        $deleteForm=new App_Form_Delete();
        $this->view->deleteForm=$deleteForm;

        if($this->_request->getPost('Delete')) {
                $formData = $this->_request->getPost();
                $attendance_details1=$this->view->adm->editrecord('ob_attendance',$attendance_id);
                echo "<pre>";print_r($attendance_details1);
                foreach($attendance_details1 as $attendance_details) {
                    $this->view->adm->addRecord('ob_attendance_log',$attendance_details);
                }
                $this->view->adm->deleteRecord('ob_attendance',$attendance_id);
                $this->_redirect('attendanceindex/index');
        }
// 		} else {
// 		$this->_redirect('index/index');
// 		}
    }

    public function fetchmembersAction() 
    {
        $this->_helper->layout->disableLayout();
        $path = $this->view->baseUrl();
        $meeting_ID=$this->_request->getParam('meeting_ID');
        $fetchMembers=new Attendance_Model_Attend();
        $this->view->members=$aa=$fetchMembers->fetchMembers($meeting_ID);
    }

    public function fetchmemberseditAction() 
    {
        $this->_helper->layout->disableLayout();
        $path = $this->view->baseUrl();
        $meeting_ID=$this->_request->getParam('meeting_ID');
        $attendance_id=$this->_request->getParam('attendance_id');

        $fetchMembers=new Attendance_Model_Attend();
        $this->view->members=$aa=$fetchMembers->fetchMembers($meeting_ID);
        $check=array();
        $fetchMembersForAttendance=new Attendance_Model_Attendance();
        $fetchMembersForAttendance1=$fetchMembersForAttendance->assignMembers($attendance_id);
        foreach($fetchMembersForAttendance1 as $fetchMembersForAttendance2){
            $check[]=$fetchMembersForAttendance2['member_id'];
        }
        $this->view->val=$check;
    }

}
