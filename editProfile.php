<?php
 
 session_start();
 include "php/config.php";
 if(isset($_POST['edit']))
 {
    $unique_id=$_SESSION['unique_id'];

    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = strip_tags($_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $userImage    =   $_FILES['userImage'];
    
    if(!empty($fname) && !empty($email)){
    if(filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL)){
    $imageName = $userImage ['name'];
            $fileType  = $userImage['type'];
            $fileSize  = $userImage['size'];
            $fileTmpName = $userImage['tmp_name'];
            $fileError = $userImage['error'];

            $fileImageData = explode('/',$fileType);
            $fileExtension = $fileImageData[count($fileImageData)-1];

            
            if($fileExtension == 'jpg' || $fileExtension == 'png' || $fileExtension == 'jpeg' || $fileExtension == 'gif'){
                //Process Image
                
                if($fileSize < 5000000){
                    //var_dump(basename($imageName));

                    $fileNewName = "php/images/".$imageName;
                    
                    $uploaded = move_uploaded_file($fileTmpName,$fileNewName);

    $select= "select * from users where unique_id='$unique_id'";

    $sql = mysqli_query($conn,$select);
    $row = mysqli_fetch_assoc($sql);
    $res= $row['unique_id'];
    if($res === $unique_id)
    {
   
        $update = "update users set fname='$fname',lname='$lname',email='$email' ,img='$imageName' where unique_id='$unique_id'";
        $sql2=mysqli_query($conn,$update);
 if($sql2)
       { 
           /*Successful*/
           header('Location:userPro.php?success=userUpdated');
                    exit;
                    }
       else
       {
           /*sorry your profile is not update*/
           header('location:editPro.php?error=ProNotUpdate');
       }

    }
    else
    {
        /*sorry your id is not match*/
        header('location:editPro.php?error=IdNotMatch');
    }

  }else{
          /*Maximum 5mb Image size allowed.*/
        header('Location:userPro.php?error=invalidFileSize');
                    exit;
        }

    }else{
            /*Please upload image/gif file*/
         header('Location:userPro.php?error=invalidFileType');
                exit;
    }
    }else{
        /*Sorry, email is invalid*/
    header('Location:userPro.php?error=notValidEmail');
                exit;
    }
    }else{
        /*Some required input fields are empty*/
    header('Location:userPro.php?error=inputFieldareRequired');
                exit;
    }
 }
