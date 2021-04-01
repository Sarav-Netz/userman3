<?php
    include('db.php');
    session_start();
    include('userHandlerClasses.php');
    if($_SESSION['userRole']=="manager"){
        #>>>>>>>>>>>>><<<<<<<<<<<<
        #Current User Info
        if(isset($_POST['showMyDetailClick'])){
            $userId=(int)$_SESSION['userId'];
            $dbObj=new dbConnection();
            $dbObj->connectDb();
            $queryObj=new createQuery();
            $queryObj->selectWithCond($userId);
            $userObj=new managerChange();
            $userObj->showUser($dbObj->con,$queryObj->myQuery);
            $dbObj->dissconnectDb();
        }
        #>>>>>>>>>>>>><<<<<<<<<<<<
        #Log Out Condition
        else if (isset($_POST['logoutClick'])) {
            session_destroy();
            echo '<script>alert("You clicked me! Now i\'m logging you out")</script>';
            header("Location:manager.php");
        }
        #>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<
        #Udate Information condition
        else if(isset($_POST['updateInfo'])){
            $userId=$_POST['userId'];
            $userName=$_POST['userName'];
            $userEmail=$_POST['userEmail'];
            $dbObj=new dbConnection();
            $queryObj=new createQuery();
            $managerObj=new managerChange();
            $dbObj->connectDb();
            $queryObj->selectWithCond($userId);
            $managerObj->allowToChange($dbObj->con,$queryObj->myQuery);
            if($managerObj->makeChange){
                $queryObj->updateInfoQuery($userId,$userName,$userEmail);
                $managerObj->updateUserInfo($dbObj->con,$queryObj->myQuery);
                $dbObj->dissconnectDb();
            }else{
                echo '<script>alert("You are not allowed to do this task")</script>';
            }
        }
        #--------->>>>>>>>>>>><<<<<<<<<<<<<--------
        # Password updation command
        elseif (isset($_POST['updatePassword'])) {
            $userId=$_POST['userId'];
            $userEnteredPassword=$_POST['userEnteredPassword'];
            $userConformationPassword=$_POST['userConformationPassword'];
            $dbObj=new dbConnection();
            $queryObj=new createQuery();
            $managerObj=new managerChange();
            $dbObj->connectDb();
            $queryObj->selectWithCond($userId);
            $managerObj->allowToChange($dbObj->con,$queryObj->myQuery);
            if($managerObj->makeChange){
                if($userEnteredPassword==$userConformationPassword){
                    $queryObj->updatePassword($userId,$userEnteredPassword);
                    $managerObj->updateUserPassword($dbObj->con,$queryObj->myQuery);
                    $dbObj->dissconnectDb();
                }else{
                    echo '<script>alert("please enter password carefully!")</script>';
                }
            }else{
                echo '<script>alert("You are not allowed to do this task")</script>';
            }
        }
        #>>>>>>>>>>>>><<<<<<<<<<<<
        #this condition will work to add new user
        else if(isset($_POST['addNewUser'])){
            $userName=$_POST['newUserName'];
            $userEmail=$_POST['newUserEmail'];
            $userRole=$_POST['newUserRole'];
            $userRole=strtolower($userRole);
            $userPassword=$_POST['newUserPassword'];
            $valid=$_POST['newUserValid'];
            $valid=strtolower($valid);
            if($userRole!='admin' && $userRole!="manager"){
                $dbObj=new dbConnection();
                $dbObj->connectDb();
                $queryObj=new createQuery();
                $queryObj->addUserQuery($userName,$userEmail,$userRole,$userPassword,$valid);
                $userObj=new managerChange();
                $userObj->addNewUser($dbObj->con,$queryObj->myQuery);
                $dbObj->dissconnectDb();
            }else{
                echo '<script>alert("You are allowed to add staff members only")</script>';
            }
        }
        #----------->>>>>>>>><<<<<<<<<<<<<<<<-------------
        # information of user other than the current User
        else if(isset($_POST['otherUserInfo'])){
            $userId=$_POST['randomQueryUserId'];
            $dbObj=new dbConnection();
            $queryObj=new createQuery();
            $managerObj=new managerChange();
            $dbObj->connectDb();
            $queryObj->selectWithCond($userId);
            $managerObj->allowToChange($dbObj->con,$queryObj->myQuery);
            if($managerObj->makeChange){
                $queryObj->selectWithCond($userId);
                $managerObj->showUser($dbObj->con,$queryObj->myQuery);
                $dbObj->dissconnectDb();
            }else{
                echo '<script>alert("You are not allowed to do this task")</script>';
            }
        }
        #--->>>>>>>>>>>>><<<<<<<<<<<<------
        #Deletion of the end users bu manager
        else if(isset($_POST['otherUserDeletion'])){
            $userId=$_POST['randomQueryUserId'];
            $dbObj=new dbConnection();
            $queryObj=new createQuery();
            $managerObj=new managerChange();
            $dbObj->connectDb();
            $queryObj->selectWithCond($userId);
            $managerObj->allowToChange($dbObj->con,$queryObj->myQuery);
            if($managerObj->makeChange){
                $queryObj->deleteQuery($userId);
                $managerObj->deleteAnyUser($dbObj->con,$queryObj->myQuery);
                $dbObj->dissconnectDb();
            }else{
                echo '<script>alert("You are not allowed to do this task")</script>';
            }
        }
        #>>>>>>>>>>>>><<<<<<<<<
        #condition to validate User
        else if(isset($_POST['approveStaff'])){
            $userId=$_POST['randomQueryUserId'];
            $dbObj=new dbConnection();
            $queryObj=new createQuery();
            $managerObj=new managerChange();
            $dbObj->connectDb();
            $queryObj->selectWithCond($userId);
            $managerObj->allowToChange($dbObj->con,$queryObj->myQuery);
            if($managerObj->makeChange){
                $queryObj->validateQuery($userId);
                $managerObj->validateStaff($dbObj->con,$queryObj->myQuery);
                $dbObj->dissconnectDb();
            }else{
                echo '<script>alert("You are not allowed to do this task")</script>';
            }
        }
        #>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<
        #devalidate any user
        else if(isset($_POST['disApproveStaff'])){
            $userId=$_POST['randomQueryUserId'];
            $dbObj=new dbConnection();
            $queryObj=new createQuery();
            $managerObj=new managerChange();
            $dbObj->connectDb();
            $queryObj->selectWithCond($userId);
            $managerObj->allowToChange($dbObj->con,$queryObj->myQuery);
            if($managerObj->makeChange){
                $queryObj->deValidateQuery($userId);
                $managerObj->deValidateStaff($dbObj->con,$queryObj->myQuery);
                $dbObj->dissconnectDb();
            }else{
                echo '<script>alert("You are not allowed to do this task")</script>';
            }
        }
        #>>>>>>>>>>>>>><<<<<<<<<<<<<
        # SHow all member accept admin and manager
        else if(isset($_POST['showAllMember'])){
            $dbObj=new dbConnection();
            $dbObj->connectDb();
            $queryObj=new createQuery();
            $queryObj->selectAllUserQuery();
            $userObj=new managerChange();
            $userObj->showAllUserToManager($dbObj->con,$queryObj->myQuery);
            $dbObj->dissconnectDb();
        }
        #<<<<<<<<<<<<>>>>>>>>>>>
        #showing your pending tasks
        else if(isset($_POST['myToDoList'])){
            echo '<script>alert("i am in the process!")</script>';
        }
    }else{
        echo '<script>alert("You are not logged in as a manager! go back and logged in")</script>';
        header("Location:manager.php");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>ManagerDashboard</title>
</head>
<body>
    <div class="jumbotron bg-dark">
        <h3 class="text-warning">This Dashboard is a simple page Whose purpose is just to Provide information about Staff member. to the Manager</h3>
    </div>
    <div class="container">
        <form action="" method="POST"></br>
        <button class="btn btn-danger" name="showMyDetailClick">Show my detail</button></br></br>
        <button class="btn btn-danger" name="logoutClick">Logout</button></br></br></br>
        <button name="showAllMember" class="btn bg-primary">Show all staff Members</button></br></br>
        <button name="myToDoList" class="btn bg-primary">Show my TO do list</button></br></br>
        </form>
        <div>
            <button name="updateInfoModal" data-toggle="modal" data-target="#updationInfoModal" class="btn btn-secondary">Update staff Information</button></br></br>
            <div class="modal" id="updationInfoModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Updation form for staff memeber.</h4>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <input type="text" class="form-control" name="userId" placeholder="Enter user ID.">
                                <input type="text"  name="userName" class="form-control" placeholder="enter user name">
                                <input type="text" class="form-control" name="userEmail" placeholder="enter user email" >
                                <button  class="btn btn-primary" name="updateInfo">update User</button>
                                <button  class="btn btn-default"  data-dismiss="modal">Close</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <button name="updatePasswordModal" data-toggle="modal" data-target="#updatePasswordModal" class="btn btn-secondary">Update Staff password</button></br></br>
            <div class="modal" id="updatePasswordModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Update staff member password form</h4>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <input type="text" class="form-control" name="userId" placeholder="Enter Your Id">
                                <input type="text"  name="userEnteredPassword" class="form-control" placeholder="enter new password">
                                <input type="text" class="form-control" name="userConformationPassword" placeholder="enter password again" >
                                <button  class="btn btn-primary" name="updatePassword">update User</button>
                                <button  class="btn btn-default"  data-dismiss="modal">Close</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <button name="addUserModal1" data-toggle="modal" data-target="#addUserModal" class="btn btn-secondary">Add New Staff Member</button></br></br>
            <div class="modal" id="addUserModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Add new Staff member Form</h4>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <label>Name of the user</label>
                                <input type="text" class="form-control" name="newUserName" placeholder="Enter Name for new user">
                                <label>Email of the User</label>
                                <input type="text"  name="newUserEmail" class="form-control" placeholder="enter email for new user">
                                <label>Password for the user</label>
                                <input type="text"  name="newUserPassword" class="form-control" placeholder="enter password for new user">
                                <label>Profile of the User</label>
                                <input type="text"  name="newUserRole" class="form-control" placeholder="enter Role for new user">
                                <label>make user valid or not</label>
                                <input type="text"  name="newUserValid" class="form-control" placeholder="yes/no">
                                <button  class="btn btn-primary" name="addNewUser">Add </button>
                                <button  class="btn btn-default"  data-dismiss="modal">Close</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <form action="" method="POST">
                <input type="text" name="randomQueryUserId" placeholder="enter user id" require>
                <button class="btn btn-primary bg-dark" name="otherUserInfo">Show Info of staff Memeber</button>
                <button class="btn btn-primary bg-dark" name="otherUserDeletion">Delete Staff member</button>
                <button class="btn btn-primary bg-dark" name="approveStaff">Approve Staff member</button>
                <button class="btn btn-primary bg-dark" name="disApproveStaff">Block Staff member</button>
            </form>
        </div>    
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>
</html>