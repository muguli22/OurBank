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
class Activityoutstanding_IndexController extends Zend_Controller_Action
{
    public function init()
    { 
        $this->view->pageTitle =  $this->view->translate("Activity wise outstanding");
        $this->view->title =  $this->view->translate("Activity wise outstanding");
        $this->view->type='loans';
        $sessionName = new Zend_Session_Namespace('ourbank');
        $userid=$this->view->createdby = $sessionName->primaryuserid;
        $login=new App_Model_Users();
        $loginname=$login->username($userid);
        foreach($loginname as $loginname) 
        {$this->view->username=$loginname['username'];}
    }

    public function indexAction()
    {
        $searchForm = new Activityoutstanding_Form_Search();
        $this->view->form = $searchForm;
        $activityoutstanding = new Activityoutstanding_Model_activityoutstanding();
        $convertdate = new Creditline_Model_dateConvertor(); 
        $subBranch = $activityoutstanding->getBranchOffice();
        foreach($subBranch as $subBranch) 
        {$searchForm->bank->addMultiOption($subBranch->id,$subBranch->name);}
        if ($this->_request->isPost() && $this->_request->getPost('Search'))
        {
            $this->view->val=10;
            $today=date('Y-m-d');
	    $todaynormal=date('d-m-Y');
            $formData=$this->_request->getPost();
            if ($searchForm->isValid($formData))
            {
                if($this->_request->getParam('fromdate')){
                $fromDate = $convertdate->phpmysqlformat($this->_request->getParam('fromdate'));$this->view->field1 = $fromDate;
                }
                if($this->_request->getParam('todate')){
                $toDate = $convertdate->phpmysqlformat($this->_request->getParam('todate'));$this->view->field2 = $toDate;
                }
                $brancId = $this->_request->getParam('bank');
                $this->view->field3=$brancId;
                if($fromDate > $today)
                {
                $this->view->fromerr="<font color='red'>From Date is less than or equal to today date:<b>".$todaynormal."</b></font>";
                }
                else if($toDate > $today)
                {
                $this->view->toerr="<font color='red'>To date less is than or equal to today date:<b>".$todaynormal."</b></font>";
                }
                else if($fromDate>$toDate)
                {
                $this->view->dateerr="<font color='red'>From date is less than to to-date date</font>";
                }
                else
                {
                $this->view->outstanding=$outstanding=$activityoutstanding->outstanding($brancId,$fromDate,$toDate);
                $this->view->disbursed=$disbursed=$activityoutstanding->disbursed($brancId,$fromDate,$toDate);
                $this->view->paid=$paid=$activityoutstanding->paid($brancId,$fromDate,$toDate);
                }
            }
        }
    }

    public function chartAction(){
        $fromDate=$this->_request->getParam('fdate');
        $toDate=$this->_request->getParam('todate');
        $brancId=$this->_request->getParam('bank_id');
        $activityoutstanding = new Activityoutstanding_Model_activityoutstanding();
        $this->view->outstanding=$outstanding=$activityoutstanding->outstanding($brancId,$fromDate,$toDate);
        $this->view->disbursed=$disbursed=$activityoutstanding->disbursed($brancId,$fromDate,$toDate);
        $this->view->paid=$paid=$activityoutstanding->paid($brancId,$fromDate,$toDate);
    }
	function reportdisplayAction() {
		$app = $this->view->baseUrl();
		$word=explode('/',$app);
		$projname='';
		for($i=0; $i<count($word); $i++){
			if($i>0 && $i<(count($word)-1)) { $projname.='/'.$word[$i]; }
		}
		$this->_helper->layout->disableLayout();
		$file1 = $this->_request->getParam('file'); 
		$this->view->filename = $projname."/reports/".$file1;
	}

	function pdftransactionAction()
	{	
	$pdf = new Zend_Pdf();
	$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
	$pdf->pages[] = $page;	
	$baseURl=$this->view->baseUrl();
	$word=explode('/',$baseURl);
	$projname='';
	for($i=0; $i<count($word); $i++){
		if($i>0 && $i<(count($word)-1)) { $projname.='/'.$word[$i]; }
	}
	$fromDate = $this->_request->getParam('field1');
	$toDate = $this->_request->getParam('field2');
	$branch=$this->_request->getParam('field3');
	$activityoutstanding = new Activityoutstanding_Model_activityoutstanding();
        $convertdate = new Creditline_Model_dateConvertor();
        $date1=$convertdate->phpnormalformat($fromDate);
        $date2=$convertdate->phpnormalformat($toDate);
	$result=$activityoutstanding->getbank($branch);
	foreach($result as $name)
	{$officename=$name['name'];}
	$image_name = "/var/www".$baseURl."/images/logo.jpg";
	$image = Zend_Pdf_Image::imageWithPath($image_name);
	//$page->drawImage($image, 25, 770, 570, 820);

	$page->drawImage($image, 30, 770, 130, 820);
	$page->setLineWidth(1)->drawLine(25, 25, 570, 25); //bottom horizontal
	$page->setLineWidth(1)->drawLine(25, 25, 25, 820); //left vertical
	$page->setLineWidth(1)->drawLine(570, 25, 570, 820); //right vertical
	$page->setLineWidth(1)->drawLine(570, 820, 25, 820); //top horizontal
	//set the font
	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 8);
	$text = array($this->view->translate("Activity wise outstanding"),
		$this->view->translate("Activity name"),
		$this->view->translate("Disbursed amount"),$this->view->translate("Paid amount"),
		$this->view->translate("Outstanding Balance"));
        $x1 = 80; 
        $x2=250;
        $x3 = 200;
        $x4 = 330;
        $x6 = 450;
        $y1= 700;

        $page->drawLine(50, 740, 550, 740);
        $page->drawLine(50, 720, 550, 720);
        $page->drawText($text[0], $x2, 770);
        $page->drawText($text[1], $x1, 725);
        $page->drawText($text[2], $x3, 725);
        $page->drawText($text[3], $x4, 725);
        $page->drawText($text[4], $x6, 725);
        $page->drawText($this->view->translate("From: ").$date1, $x1, 760);
        $page->drawText($this->view->translate("Bank: ").$officename, $x6, 760);
        $page->drawText($this->view->translate("To:     ").$date2 ,$x1, 745);
        $page->drawText($this->view->translate("Amount in R$") ,$x6, 745);
	$activityoutstanding = new Activityoutstanding_Model_activityoutstanding();
        $this->view->outstanding=$outstanding=$activityoutstanding->outstanding($branch,$fromDate,$toDate);
        $this->view->disbursed=$disbursed=$activityoutstanding->disbursed($branch,$fromDate,$toDate);
        $this->view->paid=$paid=$activityoutstanding->paid($branch,$fromDate,$toDate);
        foreach($disbursed as $disbursed1)
        {
            $page->drawText($disbursed1['name'],$x1,$y1);
            $page->drawText($disbursed1['disbursed'],$x3,$y1);
            foreach($paid as $paid1){
                    if($disbursed1['name']==$paid1['name']){ 
                        $page->drawText($paid1['paid'],$x4,$y1); 
                        }
            }
            foreach($outstanding as $outstanding1){
                    if($disbursed1['name']==$outstanding1['name']){ 
                        $page->drawText($outstanding1['outstanding'],$x6,$y1); 
                        }
            }

        $y1 -= 15; 
        }
 		$page->drawLine(50, $y1, 550, $y1);
		$pdfData = $pdf->render();	
		$pdf->save('/var/www/'.$projname.'/reports/activityoutstanding.pdf');
		$path = '/var/www/'.$projname.'/reports/activityoutstanding.pdf';
		chmod($path,0777);
		$this->_redirect('/activityoutstanding/index/reportdisplay/file/activityoutstanding.pdf');	
	}
}
