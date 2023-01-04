<?php
namespace Employee\Model;

class Employee
{
    public $id;
    public $employee_id;
    public $employee_name;
    public $address;
    public $email_address;
    public $phone;
    public $dob;
    public $imageurl;
    public $created_date;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->employee_id = !empty($data['employee_id']) ? $data['employee_id'] : null;
        $this->employee_name  = !empty($data['employee_name']) ? $data['employee_name'] : null;
        $this->address  = !empty($data['address']) ? $data['address'] : null;
        $this->email_address  = !empty($data['email_address']) ? $data['email_address'] : null;
        $this->phone  = !empty($data['phone']) ? $data['phone'] : null;
        $this->dob  = !empty($data['dob']) ? $data['dob'] : null;
        $this->imageurl  = !empty($data['imageurl']) ? $data['imageurl'] : null;
        $this->created_date  = !empty($data['created_date']) ? $data['created_date'] : date("Y-m-d H:i:s");
    }
}