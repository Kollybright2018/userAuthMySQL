<?php
session_start();
require_once "../config.php";

//register users
function registerUser($fullnames, $email, $password, $gender, $country){
    //create a connection variable using the db function in config.php
    $conn = db(); 
   //check if user with this email already exist in the database

    $stmt=$conn->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    // set email param;
    $email = $_POST['email'];
    $stmt->execute();
     $count = $stmt->get_result();
     if ($count->num_rows > 0) {
        echo "<script> alert('User Already registered try with another Emaill') </script>";

     echo "User Already registered try with another Emaill";
     } else{
        $sql = "INSERT INTO students(`full_names`, `country`, `email`, `gender`, `password`) 
        VALUES(?,?,?,?,?)";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("sssss", $fullnames, $country,  $email, $gender, $password);
           
         if ($stmt->execute()) {
            echo "<script> alert('New Record Successfully Created') </script>";
            echo "Form Submitted Successfully.";
         }
        //  $stmt->execute();
         $stmt->close();
         $conn->close();
      
     }

//    }
 

}


//login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    //open connection to the database and check if username exist in the database
    //if it does, check if the password is the same with what is given
    //if true then set user session for the user and redirect to the dasbboard
    $stmt = $conn->prepare("SELECT * FROM students WHERE email =? AND password =?");
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $get = $result->fetch_assoc();
        $_SESSION['username'] = $get['full_names'];
        echo "<script> alert('Login Successful') </script>";
        header("Location: ../dashboard.php");
        die();
    }else{
        echo "<script> alert('Incorrect Details') </script>";
        header("Location: ../forms/login.html");
    }
    $conn->close();
}


function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and stmt$stmt if username exist in the database
    //if it does, replace the password with $password given
    $stmt = $conn->prepare( "SELECT * FROM students WHERE email =?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = mysqli_fetch_assoc($result);
        $update_sql = $conn->prepare("UPDATE students 
        SET password = ?
        WHERE email = ?");
        $update_sql->bind_param("ss", $password, $email);
        if ($update_sql->execute()) {
            echo "<script> alert('Password Reset Successful') </script>";
            die();
        }
    }else{
        echo "<script> alert('User Does not exist') </script>";
        header('location: ../forms/resetpassword.html');
    }
}

function getusers(){
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo"<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            //show data
            echo "<tr style='height: 30px'>".
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] . 
                "</td> <td style='width: 150px'>" . $data['country'] . 
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                 "value=" . $data['id'] . ">".
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>".
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

 function deleteaccount($id){
     $conn = db();
     //delete user with the given id from the database
     $query = $conn->prepare("DELETE FROM students WHERE id=?");
     $query->bind_param("i", $id);

     if($query->execute()){
         echo 'Record deleted successfully';
     }else{
        echo "Error deleting record: " . mysqli_error($conn);
     }
 }
