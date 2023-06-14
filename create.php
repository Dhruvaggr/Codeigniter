<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class create extends CI_controller{


    public function index()
    {
        $data['country']= $this->db->group_by('country')->get('person')->result();
        $this->load->view('form/form.php',$data);
        // $this->load->view('form/form.php');
    }

    public function getData()


    
    {
        
        $draw = intval($this->input->post('draw'));
        $start= intval($this->input->post('start'));
        $length= intval($this->input->post('length'));
        $order= $this->input->post('order');
        $search= $this->input->post('search'); 
        $search=$search['value'];

        //Sorting//
        $col=0;
        $dir='';
        if(!empty($order))
        {

              foreach ($order as $o)
              {
                $col=$o['column'];
                $dir=$o['dir'];
              }
        }
        if($dir!='asc' && $dir!='desc')
        {
            $dir='desc';
        }
        $valid_columns=array(
            0=>'name',
            1=>'email',
            2=>'country',
            3=>'gender',
            4=>'file_path',
            5=>'bio',


        );

        if(!isset($valid_columns[$col]))
        {
            $order=null;
        }
        else{
            $order=$valid_columns[$col];
        }


        if($order!=null)
         {
            $this->db->order_by($order,$dir);
         }



        // Searching
        if(!empty($search))
        {
            $x=0;
            foreach($valid_columns as $sterm)
            {
                 if($x=0){
                        $this->db->like($sterm,$search);

                  }

                  else{
                    $this->db->or_like($sterm,$search);
                  }


                  $x++;
            }
        }
            $this->db->limit($length,$start);
            $query= $this->db->get('person');
            if($query->num_rows()>0)
            {
                foreach($query->result() as $value)
                {
                        $json[]=array(
                              $value->name,
                              $value->email,
                              $value->country,
                              $value->gender,
                              '<a href="http://localhost/codeigniter3/pictures/uploads/'.$value->file_path.'">Image/Document</a>',
                            //   $value->file_path,
                              $value->bio,
                              '<button class="update-button" data-id='.$value->id.'>Update</button>',
                               '<button class="delete-button" data-id='.$value->id.'>Delete</button>'
                            //    '<a href="#" class="update-button">Update</a>',
                            //    '<a href="#" class="delete-button">Delete</a>',
                        );
                }

                     $response=array(
                             'draw'=>$draw,
                             'data'=>$json,
                             'recordsTotal'=>$this->db->count_all_results('person'),
                             'recordsFiltered'=>$this->db->count_all_results('person')
                     );


                     echo json_encode($response);   
            }

            else{
                $response=array();
                $response['sEcho']=0;
                $response['iTotalRecords']=0;
                $response['iTotalDisplayRecords']=0;
                $response['aaData']=[];
                echo json_encode($response);


            }




        //  $this->load->model('ajaxmodel');
        //  $filteredData= $this->ajaxmodel->searchData($start,$length,$search);
        //  $filteredCount=$this->ajaxmodel->searchDataCount($search);
       
        // print_r($filteredData);
        
         
        //  foreach($filteredData as $key=>$value){
        //     $arr=array();
        //     $arr[]=$value->name;
        //     $arr[]=$value->email;
        //     $arr[]=$value->country;
        //     $arr[]=$value->gender;
        //     $arr[]=$value->bio;
        //     $arr[]=$value->file_path;
        //     $arr[]='';
        //     $arr[]='';
        //     $data [] = $arr;
        //  }
        // //   print_r($data);die;




        


        //  var_dump($start);die;


        // $data=array(
        //      'draw'=>$draw,
        //      'data'=>$data,
        //      'recordsTotal'=>$this->ajaxmodel->getTotalCount(),
        //      'recordsFiltered'=>$filteredCount



        // );
        // // var_dump($data); die;
        //  header('Content-Type: application/json');
        //   echo json_encode($data);
}
    public function filterList()
    {

         
           if(!empty($_POST['country'])){
            // echo "dg";die;
            // echo($_POST['country']);die;
            if($_POST['country']==='All')
            {
              $query=$this->db->get('person');
              $result= $query->result();
              //  echo"<pre>";
              // print_r( $query->result_array());die;
           
            }
            else{
            $this->db->where('country',$_POST['country']);
            $result=  $this->db->get('person')->result();
            }
           }
            $data=array();
            $i=0;
            foreach($result as $val){
                $data[]=array(

                    $val->name,
                    $val->email,
                    $val->country,
                    $val->gender,
                    '<a href="http://localhost/codeigniter3/pictures/uploads/'.$val->file_path.'">Image/Document</a>',
                    // $val->file_path,
                    $val->bio,
                    '<button class="update-button" data-id='.$val->id.'>Update</button>',
                    '<button class="delete-button" data-id='.$val->id.'>Delete</button>'

                );

                $i++;
            }

            $output=array("data"=>$data);
                    //   $filterOption= $this->input->post('country');
                    //   echo "dg";die;
                 
                    // $this->load->model('ajaxmodel');
                    // $filteredrow=$this->ajaxmodel->getfilterdata($filterOption);

                    //    echo"<pre>";
                    //   print_r($filteredrow);
                    
                  
                     echo json_encode($output);


    }
    public function insert()
    {
        
       $this->load->library('form_validation');


         $uploadedfiles=$_FILES['userfile'];
      
     

        $this->form_validation->set_rules('fname','Name','required|min_length[2]|max_length[10]',
    
                                                                 array(
                                                                         'required'=>'Name is required.'
                                                                 
                                                                      )
    
    
    
    );
        $this->form_validation->set_rules('email','Email','required|is_unique[person.email]|valid_email',
    
                                            array(
                                                'is_unique'=>'This %s already exists.Try using different ID.',
                                                'required'=>'%s is Required.'
                                                 )
    
    
    );
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
                $response['message']=implode($this->form_validation->error_array(),"\n");
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
        
    }

    public function validate_files()
    {                
        $uploadedfiles=$_FILES['userfile'];
      
        // $this->load->library('form_validation');

        //   print_r($files);die;
        // echo"<pre>";
        // print_r( $uploadedfiles);
        // print_r($uploadedfiles['name']);
        $file_count=count($uploadedfiles['name'][0]);
        // echo $file_count;
                if(empty($uploadedfiles['name'][0]))
                {
                    // echo "Empty";
                    $this->form_validation->set_message('validate_files','please select atleast one file.');
                    return false;
                }
                else{
                    // echo"have";
                    return true;
                }
                

               
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