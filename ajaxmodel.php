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

    public function getfilterdata($filterOption)
    {
      // echo $filterOption;die;
      if($filterOption==='All')
      {
        $query=$this->db->get('person');
        $rows= $query->result_array();
        //  echo"<pre>";
        // print_r( $query->result_array());die;
        return $rows;
      }
      

        $query=$this->db->where('country',$filterOption)
        
            ->get('person');
            $rows= $query->result_array();
            // echo"<pre>";
            // print_r($rows);


            // if(!empty($rows))
            // {
            //   foreach($rows as $key=>$value)
            //   {
            //     $rows[$key]=$value; 
            //     // print_r($rows);
            //   }
              
            // }
            
            return $rows;
           
          //   echo"<pre>";
          //  print_r($query->result_array());die;
      
      //  $items=array();
      //      foreach($rows as $row)
      //      {
      //       $items[]=$row['country'];
      //      }
      //     //  print_r($items);
      //      return $items;
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

      
      $y=$data['file_name'];
        // echo $y;die;
        $data1=array(
             'file_name'=>$y,
        );
        $this->db->insert('person',$data1);
        return $this->db->insert_id();


    }

    public function searchData($search,$start,$length)
    {
      
   
      $this->db->select('*');
      $this->db->from('person'); 

      if(!empty($search))
      {
      $this->db->group_start();
        $this->db->like('name',$search);
        $this->db->or_like('email',$search);
        $this->db->or_like('country',$search);
        $this->db->or_like('gender',$search);
        $this->db->or_like('file_path',$search);
      $this->db->group_end();
      }
      
      $this->db->limit($length,$start);
      $query= $this->db->get();
      // echo"<pre>";
      // print_r( $query->result());
      return $query->result();




    }

    public function searchDataCount($search)
    {
            $this->db->select('COUNT(*) as count');
            $this->db->from('person');

            if(!empty($search))
            {

              $this->db->group_start();

                $this->db->like('name',$search);
                $this->db->or_like('email',$search);
                $this->db->or_like('country',$search);
                $this->db->or_like('gender',$search);
                $this->db->or_like('file_path',$search);
            $this->db->group_end();
            }
          
            $query= $this->db->get();
            // echo"<pre>";
            $result=  $query->row();
            return $result->count;
    }

    public function getTotalCount()
    {
      return $this->db->count_all('person');
    }



  }