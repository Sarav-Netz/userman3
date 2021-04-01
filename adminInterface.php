<?php
    #This Page is just for the Admin Use not any other member of the website;
    include('db.php'); #this will include the data of the Database and queries;
    include('userHandlerClasses.php');
    session_start();  #this will start the session
    $validMember=FALSE;
    #-->>>>>>>>>>>>>>>>>-------<<<<<<<<<<<<--
    #Check whether the current user is admin or not;
    if($_SESSION['userRole']=="admin"){
        $validMember=TRUE;
        
        #--->>>>>>>>>>>>>>>>><<<<<<<<<<<<----
        # Log Out Current User;
        if (isset($_POST['logoutClick'])) {
            session_destroy();
            echo '<script>alert("You clicked me! Now i\'m logging you out")</script>';
            header("Location:admin.php");
        }
        #---------->>>>>>>>>>>>>>>>><<<<<<<<<<<<---------
        #User information updation Command or Query;
        else if(isset($_POST['updateInfo'])){
            $userId=$_POST['userId'];
            $userName=$_POST['userName'];
            $userEmail=$_POST['userEmail'];
            $dbObj=new dbConnection();
            $dbObj->connectDb();
            $queryObj=new createQuery();
            $queryObj->updateInfoQuery($userId,$userName,$userEmail);
            $userObj=new adminChange();
            $userObj->updateUserInfo($dbObj->con,$queryObj->myQuery);
            $dbObj->dissconnectDb();
        }
        #---------->>>>>>>>>>>>>>>>><<<<<<<<<<<<---------
        #Password Updation Condition;
        elseif (isset($_POST['updatePassword'])) {
            $userId=$_POST['userId'];
            $userEnteredPassword=$_POST['userEnteredPassword'];
            $userConformationPassword=$_POST['userConformationPassword'];
            if($userEnteredPassword==$userConformationPassword){
                $dbObj=new dbConnection();
                $dbObj->connectDb();
                $queryObj=new createQuery();
                $queryObj->updatePassword($userId,$userEnteredPassword);
                $userObj=new adminChange();
                $userObj->updateUserPassword($dbObj->con,$queryObj->myQuery);
                $dbObj->dissconnectDb();
            }else{
                echo '<script>alert("please enter password carefully!")</script>';
            }
        }
        #---------->>>>>>>>>>>>>>>>><<<<<<<<<<<<---------
        # Add new end User;
        else if(isset($_POST['addNewUser'])){
            $userName=$_POST['newUserName'];
            $userEmail=$_POST['newUserEmail'];
            $userRole=$_POST['newUserRole'];
            $userRole=strtolower($userRole);
            $userPassword=$_POST['newUserPassword'];
            $newUservalid=$_POST['newUservalid'];
            // $userPassword=sha1($userPassword);
            $dbObj=new dbConnection();
            $dbObj->connectDb();
            $queryObj=new createQuery();
            $queryObj->addUserQuery($userName,$userEmail,$userRole,$userPassword,$newUservalid);
            $userObj=new adminChange();
            $userObj->addNewUser($dbObj->con,$queryObj->myQuery);
            $dbObj->dissconnectDb();
        }
        #---------->>>>>>>>>>>>>>>>><<<<<<<<<<<<---------
        #End User Information;
        else if(isset($_POST['otherUserInfo'])){
            $userId=$_POST['randomQueryUserId'];
            $dbObj=new dbConnection();
            $dbObj->connectDb();
            $queryObj=new createQuery();
            $queryObj->selectWithCond($userId);
            $userObj=new adminChange();
            $userObj->showUser($dbObj->con,$queryObj->myQuery);
            $dbObj->dissconnectDb();
        }
        #---------->>>>>>>>>>>>>>>>><<<<<<<<<<<<---------
        #Delete any End User;
        else if(isset($_POST['otherUserDeletion'])){
            $userId=$_POST['randomQueryUserId'];
            $dbObj=new dbConnection();
            $dbObj->connectDb();
            $queryObj=new createQuery();
            $queryObj->deleteQuery($userId);
            $userObj=new adminChange();
            $userObj->deleteAnyUser($dbObj->con,$queryObj->myQuery);
            $dbObj->dissconnectDb();
        }
        #---------->>>>>>>>>>>>>>>>><<<<<<<<<<<<---------
        #approve End user;
        else if(isset($_POST['approveUserInfo'])){
            $userId=$_POST['randomQueryUserId'];
            $dbObj=new dbConnection();
            $queryObj=new createQuery();
            $userObj=new adminChange();
            $dbObj->connectDb();
            $queryObj->validateQuery($userId);
            $userObj->approveUser($dbObj->con,$queryObj->myQuery);
            $dbObj->dissconnectDb();
        }
        #---------->>>>>>>>>>>>>>>>><<<<<<<<<<<<---------
        #Block End User;
        else if(isset($_POST['blockUserInfo'])){
            $userId=$_POST['randomQueryUserId'];
            $dbObj=new dbConnection();
            $queryObj=new createQuery();
            $userObj=new adminChange();
            $dbObj->connectDb();
            $queryObj->deValidateQuery($userId);
            $userObj->disApproveUser($dbObj->con,$queryObj->myQuery);
            $dbObj->dissconnectDb();
        }
        
        #<<<<<<<<<<<<>>>>>>>>>>>
        #showing your pending tasks;
        else if(isset($_POST['myToDoList'])){
            $dbObj=new dbConnection();
            $dbObj->connectDb();
            $myQuery="SELECT * FROM `usertodo` WHERE `userId`=1 ";
            $table=mysqli_query($dbObj->con,$myQuery);
            $srNo=0;
            while($row=$table->fetch_assoc()){
                $srNo+=1;
                echo "($srNo)".$row['userTodo']."<button class=\"btn btn-danger\" name='todoDelete'>Delete </button>"."</br>";
            }
            // echo '<script>alert("i am in the process!")</script>';

        }
        #<<<<<<<<<<<<>>>>>>>>>>>
        #delete tasks;
        else if(isset($_POST['todoDelete1'])){
            $dbObj=new dbConnection();
            $dbObj->connectDb();
            $myQuery="DELETE FROM `usertodo` WHERE `userId`=1 ";
            $table=mysqli_query($dbObj->con,$myQuery);
            $srNo=0;
            while($row=$table->fetch_assoc()){
                $srNo+=1;
                echo "($srNo)".$row['userTodo']."<button class=\"btn btn-danger\" name=\"todoDelete\">Delete </button>"."</br>";
            }
            // echo '<script>alert("i am in the process!")</script>';

        }
        #<<<<<<<<<<<<>>>>>>>>>>>
        #ADD New Task;dbConnection
        else if(isset($_POST['newToDo'])){
            $dbObj=new dbConnection();
            $dbObj->connectDb();
            $userId=$_POST['userId'];
            // $userId=(int)$userId;
            $newTask=$_POST['newTask'];
            $myQuery="INSERT INTO `usertodo` (`userId`, `userTodo`) VALUES (`$userId` , `$newTask`);";
            if(mysqli_query($dbObj->con,$myQuery)){
                echo '<script>alert("We did this!")</script>'; 
            }else{
                echo '<script>alert("unable to do this task!")</script>';
            }
            // echo '<script>alert("i am in the process!")</script>';

        }
    }else{
        echo '<script>alert("You are not logged in as an admin! go back and logged in")</script>';
        header("Location:admin.php");
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
    <link rel="stylesheet" >
    <title>UserDashboard</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-success">
        <a class="navbar-brand font-weight-bold text-light" href="admin.html">Groot</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon text-dark"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                <a class="nav-link font-weight-bold text-warning" href="adminInterface.php">Show my Detail <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                <a class="nav-link font-weight-bold text-light" href="./manager.php">Manager</a>
                </li>
                <li class="nav-item">
                <a class="nav-link font-weight-bold text-warning" href="#">End User</a>
                </li>
            </ul>
            <?php
                if($validMember){
                    $userId=(int)$_SESSION['userId'];
                    $dbObj=new dbConnection();
                    $dbObj->connectDb();
                    $queryObj=new createQuery();
                    $queryObj->selectWithCond($userId);
                    $userObj=new adminChange();
                    $table = $userObj->showUser($dbObj->con,$queryObj->myQuery);
                    $dbObj->dissconnectDb();
                    $row=$table->fetch_assoc();
                    echo "<ul class='nav navbar-nav navbar-right'>
                            <li class='nav-item'>
                                <a class='nav-link font-weight-bold text-white float-right ' href='userInfo.php'>$row[userName]</a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link font-weight-bold text-warning' href='#'>Log Out</a>
                            </li>
                        " ;
                }
            ?>
          
        </div>
    </nav>
    

    <div class="container">
        <form action="" method="POST"></br>
        <!-- <button class="btn btn-danger" name="showMyDetailClick">Showdetail</button> -->
        <button class="btn btn-danger" name="logoutClick">Logout</button>
        <button name="showAllMember" class="btn bg-primary">Show all Members</button>
        <button name="myToDoList" class="btn bg-primary">My ToDo List.</button>
        <!-- <button name="newToDo" class="btn bg-primary">Add new Task.</button></br></br> -->
        </form>
        <div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">SR. no.</th>
                    <th scope="col">USER Id</th>
                    <th scope="col">User Name</th>
                    <th scope="col">User Email</th>
                    <th scope="col">User Role</th>
                    <th scope="col">User Approval</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if($validMember){
                #---------->>>>>>>>>>>>>>>>><<<<<<<<<<<<---------
                # Condition to show members;
                if(isset($_POST['showAllMember'])){
                    $dbObj=new dbConnection();
                    $queryObj=new createQuery();
                    $userObj=new adminChange();
                    $dbObj->connectDb();
                    $queryObj->selectAllUserQuery();                    
                    $table=$userObj->showAllUserToAdmin($dbObj->con,$queryObj->myQuery);
                    $dbObj->dissconnectDb();
                    if($table){
                        $srNo=0;
                        while($row=$table->fetch_assoc()){
                            $srNo+=1;
                            echo "<tr><td>".$srNo."</td><td>".$row['userId']."</td><td>".$row['userName']."</td><td>".$row['userEmail']."</td><td>".$row['userRole']."</td><td>".$row['valid']."</td>";
                        }
                    }
                }
            }
            ?>
            </tbody>
        </table>
            
        </div>
        <div>
            <button name="updateInfoModal" data-toggle="modal" data-target="#updationInfoModal" class="btn btn-secondary">Update User Information</button></br></br>
            <div class="modal" id="updationInfoModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Updation form</h4>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <input type="text" class="form-control" name="userId" placeholder="Enter user ID.">
                                <input type="text"  name="userName" class="form-control" placeholder="enter user name">
                                <input type="text" class="form-control" name="userEmail" placeholder="enter email" >
                                <button  class="btn btn-primary" name="updateInfo">update User</button>
                                <button  class="btn btn-default"  data-dismiss="modal">Close</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <button name="updatePasswordModal" data-toggle="modal" data-target="#updatePasswordModal" class="btn btn-secondary">Update password</button></br></br>
            <div class="modal" id="updatePasswordModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Update password form</h4>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <label >Enter Id</label>
                                <input type="text" class="form-control" name="userId" placeholder="Enter Id">
                                <label >Enter New Password</label>
                                <input type="text"  name="userEnteredPassword" class="form-control" placeholder="enter new password">
                                <label >Confirm Password</label>
                                <input type="text" class="form-control" name="userConformationPassword" placeholder="enter password again" >
                                <button  class="btn btn-primary" name="updatePassword">update Password</button>
                                <button  class="btn btn-default"  data-dismiss="modal">Close</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <button name="addUserModal1" data-toggle="modal" data-target="#addUserModal" class="btn btn-secondary">Add New User</button></br></br>
            <div class="modal" id="addUserModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Add new user Form</h4>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <label >Enter User Name</label>
                                <input type="text" class="form-control" name="newUserName" placeholder="Enter Name for new user">
                                <label >Enter User Email</label>
                                <input type="text"  name="newUserEmail" class="form-control" placeholder="enter email for new user">
                                <label >Enter Role for the User</label>
                                <input type="text" class="form-control" name="newUserRole" placeholder="enter role for new user" >
                                <label >Enter Password</label>
                                <input type="text"  name="newUserPassword" class="form-control" placeholder="enter password for new user">
                                <label >Validate User</label>
                                <input type="text"  name="newUservalid" class="form-control" placeholder="yes/no">
                                <button  class="btn btn-primary" name="addNewUser">Add User</button>
                                <button  class="btn btn-default"  data-dismiss="modal">Close</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <button name="updatePasswordModal" data-toggle="modal" data-target="#taskEditor" class="btn btn-secondary">Add new Task</button></br></br>
            <div class="modal" id="taskEditor" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Task Editor</h4>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <label >Enter Id</label>
                                <input type="text" class="form-control" name="userId" placeholder="Enter Id">
                                <label >Enter New Task</label>
                                <input type="text"  name="newTask" class="form-control" placeholder="enter new Task">
                                <button  class="btn btn-primary" name="newToDo">Add Task</button>
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
                <button class="btn btn-primary bg-dark" name="otherUserInfo">Show Info</button>
                <button class="btn btn-primary bg-dark" name="otherUserDeletion">DeleteUser</button>
                <button class="btn btn-primary bg-dark" name="approveUserInfo">Approve User</button>
                <button class="btn btn-primary bg-dark" name="blockUserInfo">Block User</button>
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