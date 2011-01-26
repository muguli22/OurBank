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
class Fee_Model_Fee extends Zend_Db_Table 
{
    protected $_name = 'ob_fee';

    public function getFee($id) 
	{
        $select=$this->select()
                ->setIntegrityCheck(false)
                ->join(array('a'=>'ob_fee'),array('id'),array('a.description as feedescription','name','value','glsubcode_id','membertype_id'))
                ->where('a.id=?',$id)
				->join(array('b' => 'ourbank_membertypes'),'a.membertype_id = b.id')
->join(array('c' => 'ourbank_glsubcode'),'a.glsubcode_id = c.id');
	//	 die($select->__toString($select));
        $result=$this->fetchAll($select);
        return $result->toArray();
   	}

   	public function feeSearch($post) 
	{
		$select = $this->select()
					->setIntegrityCheck(false)  
					->join(array('a' => 'ob_fee'),array('id'))
                    ->where('a.name like "%" ? "%"',$post['name'])
                    ->where('a.value like "%" ? "%"',$post['value']);
			
        return $this->fetchAll($select);
	}

}
