<?php
namespace Employee\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
class EmployeeTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($data)
    {
        $numofrecordsperpage=3;
        $select = new Select('employee');
        if($data['search']!=''){
            $select->where->like('employee_name',"%".$data['search']."%");
        }
        $employeedatas = $this->tableGateway->selectWith($select);
        $totalrecords=$employeedatas->count();
        $totalpages=ceil($totalrecords/$numofrecordsperpage);
        $select->order($data['orderby']." ".$data['ordertype'])
            ->limit($numofrecordsperpage)
            ->offset(($data['pageno']-1)*$numofrecordsperpage);
        $employeedatas = $this->tableGateway->selectWith($select);
        $arr=array();
        foreach ($employeedatas as $projectRow) {
            $arr[]=$projectRow;
        }
        
        return [
            'datas'=>$arr,
            'totalpages'=>$totalpages,
        ];
    }

    public function getEmployee($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }
    
    public function checkEmployeeid($employeeid,$id=0)
    {
        $select = new Select('employee');
        if($id==0){
            $select->where->nest()
                    ->equalTo('employee_id', $employeeid);
        }else{
            $select->where->nest()
                    ->equalTo('employee_id', $employeeid)
                    ->notequalTo('id', $id);
        }
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        if (! $row) {
            return array();
        }

        return $row;
    }
    
    public function checkEmployeeemailid($employeeemailid,$id=0)
    {
        $select = new Select('employee');
        if($id==0){
            $select->where->nest()
                    ->equalTo('email_address', $employeeemailid);
        }else{
            $select->where->nest()
                    ->equalTo('email_address', $employeeemailid)
                    ->notequalTo('id', $id);
        }
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        if (! $row) {
            return array();
        }

        return $row;
    }

    public function saveEmployee(Employee $employee)
    {
        $data = [
            'employee_id' => $employee->employee_id,
            'employee_name'  => $employee->employee_name,
            'address'  => $employee->address,
            'email_address'  => $employee->email_address,
            'phone'  => $employee->phone,
            'dob'  => $employee->dob,
            'imageurl'  => $employee->imageurl,
            'created_date'  => $employee->created_date,
        ];

        $id = (int) $employee->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getEmployee($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update employee with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteEmployee($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}