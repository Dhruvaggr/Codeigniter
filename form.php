<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/css/styles.css?">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>



<div class="top">
<form id="createform" method="post" enctype="multipart/form-data">
<a href="index"><input id="ref" class='btn' type='button' value='Refresh Page'></input></a>

<!-- <input id="ref" class='btn' type='button' value='Refresh Page'></input><br> -->
      <h1> Create user </h1>
            <label> <strong>Name</strong> </label><br>
            <input class='input' name='fname' id="nameid" value="" type='text' placeholder='Please write your name'><br>
            <!-- <span id="name_error"></span><br> -->

            <label> <strong>Email</strong> </label><br>
            <input  class='input' name='email'  id="emailid" value="" type='email' placeholder='Please write your email'><br>
            <!-- <span id="email_error"></span><br> -->

            <label> <strong>Country</strong></label> <br>
            <select class='drop' name='country' id='country'>
                <option value=''>Please Select </option>
                <option value='India' > India</option>
                <option value='Canada' > Canada</option>
                <option value='UK' >UK</option>
                <option value='USA' >USA</option>
            </select><br>
            <label><strong> Gender</strong> </label><br>
            <input type='radio' name='gen' value='male'> Male</input>
            <input type='radio' name='gen' value='Female'>Female </input><br>
            <label><strong> Write your Bio</strong> </label><br>
            <textarea name='text' id="textar"> </textarea><br>
            <input type="file" name="userfile[]" id="userfile" multiple><br>
            <!-- <button type="submit" class="btn btn-primary" id="sub">Submit</button> -->
            <input id="buttn" class='btn' name="submit" type='button' value='Insert'> </input>
            <input id="bnn" class='btn' type='button' value='Update'> </input>
           
            <div id="uploaded-image">
             </div>
           
            <!-- <a href="#" class='btn' >BACK</a> -->
</form>
                <div class="tableContainer">
                    
                <h1 class="app"> Applicants </h1> 
                <div id="join">
                   <label id='filter_label'> Country </label>

                    <select  id='filter_dropdown'>
                    <option value="All">Select</option>

                    <?php foreach($country as $val) {?>
                    
                        <option value="<?=$val->country?>"><?= $val->country?></option>
                    
                    
                    <?php }?>
                    
                    </select>
                    </div>
  
                            <table class="tb" id="tble">

                            <thead> 
                                
                                    <tr class="row"> 
                                           
                                            <th> Name </th>
                                            <th>Email </th>
                                            <th>Country </th>
                                            <th> Gender </th>
                                            <th> File Path</th> 
                                            <th> Bio </th>
                                         
                                            <th> Update </th>
                                            <th> Delete </th>
                                        </tr> 
                            </thead> 
                                <tbody id='tablebody'>
                                    

                                </tbody>
                                    
                            </table>
                </div>
</div>




<script>

   


function dataTable(data1)
      {
    console.log(data1);
     var data=[];
    //    var data =array();
    //data1 is in object.
    for(var key=0; key< data1.length; key++ )
    {
       var obj ={
        "name":data1[key].name,
        "email":data1[key].email,
        "country":data1[key].country,
        "gender":data1[key]. gender,
        "file_path":data1[key].file_path,
        "bio":data1[key].bio
       }
    //     var obj = data1[key];
    //  console.log(obj.key);
        data.push(obj);
    // //    data.push(obj.name);
    // //    console.log(data);
       
    }
     //console.log(data);
       

         
        //    $('#tble').DataTable().destroy();
            $('#tble').DataTable({
                'pageLength':10,
                'order':[],
                "processing":true,
                "serverSide":true,
                "ajax":{
                    "url":"<?php echo site_url('create/getData')?>",
                    "type":"POST"
                    
            },
            bDestroy:true
                
            

                

            });
            
        
        }
</script>


<script>
    function myfunc(selcountry)
    {
         console.log(selcountry);
     $("#tble").DataTable({
   
        "ajax":{
              "url":"<?php echo site_url('create/filterList')?>",
              "type":"POST",
              "data":{
                country:selcountry
            }

          },
          bDestroy:true
          

          



     });


    }
function change()
{
    // myfunc();
    $("#filter_dropdown").change(function(){
        var selcountry = $(this).val();
        // console.log(selcountry);
        myfunc(selcountry);

    });
}
</script>

<script>
   

            $("#tble").DataTable({
                // "dom": '<"select"f>rtip',
                "initComplete":function(){

                //   $('#filter_dropdown').addClass('drop2');
                //   $('#filter_label').addClass('lab');
                       
                  $('#join').addClass('drop2');
                 

                   }

            });
    // }
     change();
    </script>

<!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->
<!--     
<script>
function dataTable1(data)
{
    console.log(data);
   $('#tble').DataTable().destroy();
    $('#tble').DataTable({
        //    'pageLength':10,
        //   'order':[],
        //   "processing":true, 
        //   "serverSide":true,
        //   "ajax":{
        //       "url":"<?php echo site_url('create/filterList')?>",
        //       "type":"POST"
        //     //   "data":{country:$("#filter_dropdown").val()}
        //   },
          
         
           "columns":
           [
    

              
              {"data":"name"},
               {"data":"email"},
               {"data":"country"},
               {"data":"gender"},
               {"data":"file_path"},
               {"data":"bio"}
            //    {"data":"update"    , "title":"Update"},
            //    {"data":"delete"    ,  "title":"Delete"}
               
  
           ],
           "data":data
     
        });
        // datatable.rows.add(data).draw();     
  
    }




             $('#filter_dropdown').on('change',function(){

                 var selectedOption=$(this).val();
                // // console.log(selectedOption);
                $.ajax({

                    url: "<?php echo site_url("create/filterList");?>",
                    method:'POST',
                    data:{filterOption: selectedOption},
                    success:function(data)
                    {
                        // var data1=JSON.parse(data);
                        // //console.log(data1);
                        // $('#tble').DataTable().destroy();
                         dataTable1(data);
                        // // $('#tablebody').html(response);
                        // loadDataTable();
                    }





                });


                // datatable1();

             });



    </script>
  -->
<!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->



<script type="text/javascript">

        $(document).ready(function(){ 
            
            // This function ensures that once the DOM (Document ) is ready to execute javascript code to execute.


        $('#buttn').click(function(){


       
        var formdata = new FormData($('#createform')[0]);
        mythis=this;

       $('#name_error').text('');
       $('#email_error').text('');  

      
        $.ajax({
            url : "<?php echo site_url('create/insert');?>", //We can also use bas_url
            data : formdata,
            type:'POST',
            processData:false,
            contentType:false, // It will move forward only when previous steps are followed
            dataType:'json', //We are sending json data to database
            // dataType:'', result is showing but alert is undefined.
     
            success:function(response) //Response is there because we have to get the data which can be accessed by response.
            {
                // var data =JSON.parse(response);
                console.log(response);
                //  console.log(data);
               if(response.status=='success')
               {

                alert(response.message);
                
                 newdata();
              
                //$("#createform")[0].reset();
               
               }
               
                else{
                    
                    alert(response.message);
                }
            }
  
        }); 



    });
});
</script>


 <script type="text/javascript">

        $(document).ready(function(){
       
        // $('#bnn').click(function()
        
        $.ajax({
        type:'GET',
        url:"<?php echo site_url('create/fetch');?>",
        dataType:'json',
        success:function(data)
        {
            // alert('Fetched');
            // console.log(data);
            populate(data);


        }

         

       });



    });

    </script> 

    <!-- This is to dynamically remove or fetch data instatntly -->
    <script type="text/javascript">
         
             
            function newdata()
            {
                    $.ajax({
                    type:'GET',
                    url:"<?php echo site_url('create/fetch');?>",
                    dataType:'json',
                    success:function(data)
                    {
                             alert('Fetched');
                           
                        // console.log(data);
                        populate(data);

                    }

         

                  });

            }
    </script>





    <script type="text/javascript">
        function populate(data1){
   

            
    //           console.log(data1);
    //       var tablebody= document.getElementById('tablebody');
    //       tablebody.innerHTML="";
    //        output="";
        
    //        for (var i=0; i<data1.length; i++)
    //        {  
    //          var filePath= data1[i].file_path;
    
        //   output+="<tr><td><a href='http://localhost/codeigniter3/pictures/uploads/"+filePath+"'>Image/Document</a></td>" +
      
    //      "</td><td> <button class='update-button' data-id=" + data1[i].id +">Update</button></td>"+"<td><button class='delete-button' data-id=" + data1[i].id + ">Delete</button></td></tr>"

    //       } 
    //    $("#tablebody").html(output);    
      dataTable(data1);

       


        }

        </script>


<!-- Deleting using AJAX -->
    <script>
       $('#tablebody').on("click",".delete-button",function()
       {
            //    $(".top").fadeOut("slow");
                //  console.log("Delete Button");
                // var data3=$("#tablebody").serialize();
                // console.log(data3);
                // let id= $(this).attr("data-sid");
                var id= $(this).data('id');
                console.log(id);
                console.log({id:id});
                // console.log(id);
                
                // $myid={sid: id};
                    //  console.log($myid);
                // $intid= $myid.sid;
                //  console.log($intid);
                mythis= this;
                $.ajax({
                    

                    url:  "<?php echo site_url('create/delete'); ?>",
                    data : {id: id},
                    method:'POST',
                    dataType: 'json',
                    

                success:function(response) //Response is there because we have to get the data which can be accessed by response.
                        {
                        if(response.status=='success')
                            {
                               var confirmation= confirm('Are you sure want to delete.');
                               if (confirmation){
                                alert(response.message);
                                //  newdata();
                                $(mythis).closest("tr").fadeOut();
                              
                               }
                               
                            
                            }
                            
                        else
                            {
                                alert(response.message);
                            }
                        }

                });



       });
    </script>

    <!-- Updating Using AJAX  -->
    <script type="text/javascript">
          

     $('#tablebody').on("click",".update-button",function()
     {
             $("#buttn").hide();
      

         
      // console.log("DGGGG");
          var id= $(this).data('id');
            console.log(id);

        

        
          $.ajax({
             url: "<?php echo site_url("create/update");?>",
             data: {id:id},
             type:'GET',
             dataType:'json', 
            //  It will convert data from 'json' string to javascript object. 
            // alert("Hey");
             success:function(data)
             {

            //   It is 2-d array and wheneve we click on update 0th index will be shown because every time it will behave as new data.
               console.log(data);
            
            // Populating the form again.
                      $firstelement= data[0]; 
                      $("#nameid").val($firstelement['name']);
                      $('#emailid').val($firstelement['email']);
                      $('#country').val($firstelement['country']);
                      $('#textar').val($firstelement['bio']);
                      $('#bnn').text("Update");
                      $('#bnn').val("Update");
              }
             });

             $('#bnn').click(function(){
              var newname=$('#nameid').val();
              var newemail=$('#emailid').val();
              var newcountry=$('#country').val();
              var newgender=$('input[name="gen"]:checked').val();
            //   var newfilename=$('#userfile').val();
              var newbio=$('#textar').val();

              
              $.ajax({
              url: "<?php echo site_url("create/upd");?>",
              type: 'POST',
              data: {
                id: id,
                newname: newname,
                newemail:newemail,
                newcountry:newcountry,
                newgender: newgender,
                // newfilename:newfilename,
                newbio: newbio
                
              },

              success:function(response)
              {
                var data =JSON.parse(response);

                if (data.status==='success')
                {

                    alert("Data is coming");
                       alert(data.message);
                       newdata();
                       $("#createform")[0].reset();
                }
                else{
                    alert(data.message);
                }

              }

          });

        });


     });

    </script>

       


</body>
</html>