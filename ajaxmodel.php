<?php
  class ajaxmodel extends CI_Model
  {
    public function createData($data,$fileData)
    {  

   $data1=array(
    'name'=>$data['name'],
    'email'=>$data['email'],
    'country'=>$data['country'],
    'gender' =>$data['gender'],
    'bio'=>$data['bio'],
    'file_path'=>$fileData['file_name']

   );
     
      // print_r($data);die;
        $this->db->insert('person',$data1);
        return $this->db->insert_id(); //insert_id() is the inbulit fxn which which gives id and returns it to the fxn call.
    }

    public function fetchData()
    {
     $query= $this->db->get('person');

      // return $query->result_array();
      $arr= $query->result_array();
      return $arr;
    // echo"<pre>";
    // print_r ($query->result_array());die;
        // $arr['indexes']= $query->result_array();

       
      //  echo"<pre>";
        // print_r($arr[0]['file_path']);die;



    }

    public function deleteData($id){
      // echo $id;die;
      $this->db->where('id',$id);
      $this->db->delete('person');
     
     return $id;
    }

    public function updateData($id)
    {
     $query=$this->db->get_where('person',array('id'=>$id));
     return $query->result_array();

    }
    public function upddata($id,$data){
      // echo $newvalue;die;
      // $data=array('name'=>$newvalue);
      // print_r($data);die;
      $this->db->where('id',$id);
      $this->db->update('person',$data);
      return $id;

    }


    public function upload($data)
    {

      echo "Dhruv Gupta ";
      $y=$data['file_name'];
        // echo $y;die;
        $data1=array(
             'file_name'=>$y,
        );
        $this->db->insert('person',$data1);
        return $this->db->insert_id();


    }
  }