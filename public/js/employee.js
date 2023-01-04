function trim(str) {
  return str.replace(/^\s+|\s+$/g, '');
}
function is_validEmail(mail) 
{
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/.test(mail))
  {
    return (true)
  }
  return (false)
}
var phonereg = /^\d+$/;
function is_validphoneno(no,noofdigits) 
{
  if (!phonereg.test(no))
  {
    return false
  }
  else if(no.length!=10){
    return false;
  }
  return (true)
}
function saveemployee()
{
    if(trim(document.getElementById('employee_id').value)==''){
        alert("Please enter employee unique ID");
        return false;
    }
    else if(trim(document.getElementById('employee_name').value)==''){
        alert("Please enter employee name");
        return false;
    }
    else if(trim(document.getElementById('address').value)==''){
        alert("Please enter address");
        return false;
    }
    else if(trim(document.getElementById('email_address').value)==''){
        alert("Please enter email id");
        return false;
    }
    else if(!is_validEmail(trim(document.getElementById('email_address').value))){
        alert("Please enter valid email ID");
        return false;
    }
    else if(trim(document.getElementById('dob').value)==''){
        alert("Please enter date of birth");
        return false;
    }
    else if(!is_validphoneno(trim(document.getElementById('phone').value))){
        alert("Please enter 10 digits phone no");
        return false;
    }
    else if(trim(document.getElementById('imageurl').value)==''){
        alert("Please select image");
        return false;
    }  
    return true; 
}

function deleteConfirm(url){
    if(confirm("Are you sure, You want to delete this record?")){
        document.employeeform.action=url;
        document.employeeform.submit();
    }
}

function sortData(field){
    if(document.getElementById('orderby').value==field){
        if(document.getElementById('ordertype').value=="asc"){
            document.getElementById('ordertype').value="desc"
        }else{
            document.getElementById('ordertype').value="asc"
        }
    }else{
        document.getElementById('ordertype').value="asc";    
    }
    document.getElementById('orderby').value=field;
    document.employeeform.submit();
}

function gotopage(pageno){
    if(document.getElementById('pageno').value!=pageno){
        document.getElementById('pageno').value=pageno;    
        document.employeeform.submit();
    }
}

function filterData()
{
    if(trim(document.getElementById('search').value)!=''){
        document.employeeform.submit();
    }
}

function updateemployee()
{
    if(trim(document.getElementById('employee_id').value)==''){
        alert("Please enter employee unique ID");
        return false;
    }
    else if(trim(document.getElementById('employee_name').value)==''){
        alert("Please enter employee name");
        return false;
    }
    else if(trim(document.getElementById('address').value)==''){
        alert("Please enter address");
        return false;
    }
    else if(trim(document.getElementById('email_address').value)==''){
        alert("Please enter email id");
        return false;
    }
    else if(!is_validEmail(trim(document.getElementById('email_address').value))){
        alert("Please enter valid email ID");
        return false;
    }
    else if(trim(document.getElementById('dob').value)==''){
        alert("Please enter date of birth");
        return false;
    }
    else if(!is_validphoneno(trim(document.getElementById('phone').value))){
        alert("Please enter 10 digits phone no");
        return false;
    }  
    return true; 
}