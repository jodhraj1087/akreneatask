<?php

namespace Employee\Controller;

use Employee\Model\EmployeeTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Employee\Model\Employee;

class EmployeeController extends AbstractActionController
{
    // Add this property:
    private $table;

    // Add this constructor:
    public function __construct(EmployeeTable $table)
    {
        $this->table = $table;
    }
    
    public function indexAction()
    {
        $msg='';
        $request = $this->getRequest();
        $data=(array)$request->getPost();
        if(!isset($data['search'])){
            $data['search']='';
        }
        if(!isset($data['pageno'])){
            $data['pageno']=1;
        }
        if(!isset($data['orderby'])){
            $data['orderby']='employee_id';
        }
        if(!isset($data['ordertype'])){
            $data['ordertype']='asc';
        }
        $employeedata=$this->table->fetchAll($data);
        return new ViewModel([
            'employees' => $employeedata['datas'],'msg'=>$msg,'search'=>$data['search'],'pageno'=>$data['pageno'],'orderby'=>$data['orderby'],'ordertype'=>$data['ordertype'],'totalpages'=>$employeedata['totalpages'],
        ]);
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $data=(array)$request->getPost();
        $msg="";
        if(isset($data) && count($data)>0){
            $filename="";
            $employeeiddata=$this->table->checkEmployeeid($data['employee_id'],0);
            if(!empty($employeeiddata) && $employeeiddata->id>0){
                $msg="Employee ID already associated with other Employee. Please enter employee unique ID";
            }else{
                $employeeiddata=$this->table->checkEmployeeemailid($data['email_address'],0);
                if(!empty($employeeiddata) && $employeeiddata->id>0){
                    $msg="Email Address already associated with other Employee. Please enter employee unique Email ID";
                }else{
                    if(isset($_FILES["imageurl"]) && $_FILES["imageurl"]['name']!=''){
                        $target_dir=__DIR__."/../../../../public/img/uploads/";
                        $filename=preg_replace('/\s+/', '',basename($_FILES["imageurl"]["name"]));
                        $target_file = $target_dir . $filename;
                        
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                        $check = getimagesize($_FILES["imageurl"]["tmp_name"]);
                        if($check !== false) {
                            $uploadOk = 1;
                        } else {
                            $msg= "File is not an image.";
                            $uploadOk = 0;
                        }
                        if($uploadOk==1){
                            if (move_uploaded_file($_FILES["imageurl"]["tmp_name"], $target_file)) {
                                $msg = "success";
                            }else{            
                                $msg = "Failed to upload image";            
                            }
                        }
                    }
                }    
            }
            if($msg=="success"){
                $data['imageurl']="img/uploads/".$filename;
                $employee = new Employee();
                $employee->exchangeArray($data);
                $this->table->saveEmployee($employee);
                return $this->redirect()->toRoute('employee');
            }
        }
        return new ViewModel([
            'msg' => $msg,
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('employee', ['action' => 'add']);
        }
        try {
            $employee = $this->table->getEmployee($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('employee', ['action' => 'index']);
        }

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'data' => $employee];

        if (! $request->isPost()) {
            return $viewData;
        }

        $data=(array)$request->getPost();

        $msg="success";
        if(isset($data) && count($data)>0){
            $filename="";
            $employeeiddata=$this->table->checkEmployeeid($data['employee_id'],$data['id']);
            if(!empty($employeeiddata) && $employeeiddata->id>0){
                $msg="Employee ID already associated with other Employee. Please enter employee unique ID";
            }else{
                $employeeiddata=$this->table->checkEmployeeemailid($data['email_address'],$data['id']);
                if(!empty($employeeiddata) && $employeeiddata->id>0){
                    $msg="Email Address already associated with other Employee. Please enter employee unique Email ID";
                }else{
                    if(isset($_FILES["image"]) && $_FILES["image"]['name']!=''){
                        $target_dir=__DIR__."/../../../../public/img/uploads/";
                        $filename=preg_replace('/\s+/', '',basename($_FILES["image"]["name"]));
                        $target_file = $target_dir . $filename;
                        
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                        $check = getimagesize($_FILES["image"]["tmp_name"]);
                        if($check !== false) {
                            $uploadOk = 1;
                        } else {
                            $msg= "File is not an image.";
                            $uploadOk = 0;
                        }
                        if($uploadOk==1){
                            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                $msg = "success";
                            }else{            
                                $msg = "Failed to upload image";            
                            }
                        }
                        $data['imageurl']="img/uploads/".$filename;
                    }   
                } 
            }
            if($msg=="success"){
                $employee = new Employee();
                $employee->exchangeArray($data);
                $this->table->saveEmployee($employee);
                return $this->redirect()->toRoute('employee');
            }
            return new ViewModel([
                'msg' => $msg,'id' => $id, 'data' => $employee
            ]);
        }else{
            return $this->redirect()->toRoute('employee', ['action' => 'index']);
        }
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('employee');
        }
        $this->table->deleteEmployee($id);
        return $this->redirect()->toRoute('employee', ['action' => 'index']);
    }
}