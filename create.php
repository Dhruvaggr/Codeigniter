<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class create extends CI_controller{


    public function index()
    {
        $this->load->view('form/form.php');
    }

    public function insert()
    {
        
       $this->load->library('form_validation');


         $uploadedfiles=$_FILES['userfile'];
      
     

        $this->form_validation->set_rules('fname','Full Name','required');
        $this->form_validation->set_rules('email','Email','required');
        $this->form_validation->set_rules('country','Country Name','required');
        $this->form_validation->set_rules('gen','Gender','required');
        $this->form_validation->set_rules('text','Bio','required');
        $this->form_validation->set_rules('userfile[]','file upload','callback_validate_files');
       //$files=$_FILES['userfile'];

        $fname = $this->input->post('fname');
        $email = $this->input->post('email');
        $country = $this->input->post('country');
        $gen = $this->input->post('gen');
        $text= $this->input->post('text');

        $data=array(
            'name'=>$fname,
            'email'=>$email,
            'country'=>$country,
            'gender' =>$gen,
            'bio'=>$text,
        );

        if($this->form_validation->run()===FALSE)
           {

                
                $response['status']='error';
                $response['message']=implode(',',$this->form_validation->error_array());
                echo json_encode($response);

           }
           else{
                    
                    $this->db->insert('person',$data);
                    $form_id= $this->db->insert_id();
                

                    $config['upload_path'] = 'pictures/uploads';
                    $config['allowed_types']='*';
                    $config['encrypt_name'] = true;

                    $this->load->library('upload',$config);
                        

                    // echo"<pre>";
                    // print_r($_FILES); // $_FILES is array which contains key  [userfile] which also is an array. userfile 'name' of input file in 
                    //HTML.
                    $uploadedfiles=$_FILES['userfile']; // We made the array of $_FILES to smaller that is $uploadedfiles.
                    // print_r($uploadedfiles);// $uploadedfiles also contain key=>value pair. In which key is [name],[type],[tmp_name],[error],[size].
                    $databasefiles=[];
                    // print_r($uploadedfiles['name']); // This gives output of [name] key as an array of values.      
                   foreach($uploadedfiles['name'] as $key=>$file_name){
                            $_FILES['file']=array(
                                'name' =>  $uploadedfiles['name'][$key],
                                'type' =>  $uploadedfiles['type'][$key],
                                'tmp_name' =>  $uploadedfiles['tmp_name'][$key],
                                'error' =>  $uploadedfiles['error'][$key],
                                'size' =>  $uploadedfiles['size'][$key]
                            );
                            if (!$this->upload->do_upload('file')) {
                                $error = $this->upload->display_errors();
                                echo $error;
                            } else {
                                $fileData = $this->upload->data();
                                // echo"<pre>";
                                // print_r($fileData);
                    
                                $databasefiles[]=$fileData['file_name'];
                            
                        }
                       }

                            // $fileLinks=array();
                            // foreach($databasefiles as $databasefile)
                            // {
                            //     $fileLinks[]='<a href="'.base_url('pictures/uploads/'.$databasefile) .'">'. $databasefile. '</a>';
                                
                            // }

                            // $fileLinksString=implode(',',$fileLinks);
                            // // $this->db->set('file_path', $fileLinksString);
                            // $this->db->where('id',$form_id);
                            // $this->db->update('person',array('file_path'=> $fileLinksString));
                            $databasefilesstring=implode(',',$databasefiles);
                            $this->db->where('id',$form_id);

                            $this->db->update('person', array('file_path'=>$databasefilesstring));
                            $response['status']='success';
                            $response['message']='Data Inserted with id: '.$form_id;


                            echo json_encode($response);
            }
        // if($form_id){
        //     $response['status']='success';
        //     $response['message']='Data Inserted with id: '.$form_id;
        // }
        // else{
        //     $response['status']='error';
        //     $resposne['message']='Data insertion failed';
        // }
 
     // FORM VALIDATION 
       
        //    if($this->form_validation->run()===FALSE)
        //    {

        //         echo"ERROR";
        //         $response['status']='error';
        //         $resposne['message']=$this->form_validation->error_array();

        //    }
        //    else{
        //     echo"No Error";die;
        //          $response['status']='success';
        //          $response['message']='Data Inserted with id: '.$form_id;

        //    }



            // echo json_encode($response);
    }

    public function validate_files($uploadedfiles)
    {                
        // $this->load->library('form_validation');

        //   print_r($files);die;
                if(empty($uploadedfiles['name'][0]))
                {
                    
                    $this->form_validation->set_message('validate_files_upload','please select atleast one file.');
                    return false;
                }
                

                return true;
    }

public function upd()
{
    $id= $this->input->post('id');
    $newname=$this->input->post('newname');
    $newemail=$this->input->post('newemail');
    $newcountry=$this->input->post('newcountry');
    $gender=$this->input->post('newgender');
    $newbio=$this->input->post('newbio');

    $data=array(
        'name'=>$newname,
        'email'=>$newemail,
        'gender'=>$gender,
        'country'=>$newcountry,
        'bio'=>$newbio,
        // 'file_path' => 'pictures/uploads/' . $this->upload->data('file_name')
     );
    $this->load->model('ajaxmodel');
    $updid= $this->ajaxmodel->upddata($id,$data);
    if($updid)
    {
        $response['status']='success';
        $response['message']='Data Updated succeffuly at id'.$updid;
    }
    else{
        $response['status']='error';
        $response['message']='Failed to update';
    }
   

    echo json_encode($response);

    // echo $newvalue;die;
}

    public function fetch()
    {
        $this->load->model('ajaxmodel');
        $data= $this->ajaxmodel->fetchData();
        //  echo"<pre>";
        //  print_r($data);
        // $this->load->view('form/form',$data);
         
         echo json_encode($data);

       


    }
    // public function myfunction($data){
    //   print_r($data);die;

    // }
    public function delete()
    
    {

        // echo $myid->sid ;die;
        //  print_r($myid); die;
        $id= $this->input->post('id');
        
        $this->load->model('ajaxmodel');
        $deleteid=$this->ajaxmodel->deleteData($id);
        
        if ($deleteid){
            $response['status']='success';
            $response['message']='Data Deleted succefully at id'.$deleteid;
        }
        else{
            $response['status']='error';
            $response['message']='Failed to delete data ';
        }

        echo json_encode($response);

    }

    public function update(){
       $id= $this->input->get('id');
       //  echo $id;
        
        $this->load->model('ajaxmodel');
        $data= $this->ajaxmodel->updateData($id);
        //  echo"<pre>";
        //  print_r($data);die;
        // $output['success'] = true;

        echo json_encode($data);
    }
 

 


}
?>