<?php
class Employee{
    private $conn;
    public function __construct($conn){
        $this->conn=$conn;
    }
    public function applyforLeave($empname,$leave_type,$datefrom,$dateto,$reason,$leave_duration){
        $query="INSERT INTO `leaverequest`(`Empname`, `Leavetype`, 
        `Startdate`, `Enddate`, `Reason`,`Leaveduration`)
        VALUES ('$empname','$leave_type',
        '$datefrom','$dateto','$reason','$leave_duration')";
        $res=$this->conn->query($query);
        return $res;
    }
    public function viewProfile($userId){
        $query = "SELECT Firstname, Lastname, Userid, Bloodgroup, Gender, Tempadd,
        Tempcity, Tempcnty, Peradd, Percity, Percnty, Degree, Institutename,
        Grade, Passedyr, Expdesc, Bankname, Accname, Accnum, Pannum 
        FROM personalinfo WHERE Userid = '$userId'";
        $res = $this->conn->query($query);
        return $res;
    }
    public function updateProfile($userId) {
        $responses = [];

        // Update personal info
        if (isset($_POST['personal'])) {
            $sql = "UPDATE `personalinfo` SET 
                `Firstname`='{$_POST['fname']}',
                `Lastname`='{$_POST['lname']}',
                `Bloodgroup`='{$_POST['bldgrp']}',
                `Gender`='{$_POST['gender']}'
                WHERE Userid = '$userId'";

            $res = $this->conn->query($sql);
            $responses['personal'] = $res;
        }

        // Update address info
        if (isset($_POST['address'])) {
            $sql = "UPDATE `personalinfo` SET 
                `Tempadd`='{$_POST['taddress']}',
                `Tempcity`='{$_POST['tcity']}',
                `Tempcnty`='{$_POST['tcountry']}',
                `Peradd`='{$_POST['paddress']}',
                `Percity`='{$_POST['pcity']}',
                `Percnty`='{$_POST['pcountry']}'
                WHERE Userid = '$userId'";

            $res = $this->conn->query($sql);
            $responses['address'] = $res;
        }

        // Update education info
        if (isset($_POST['education'])) {
            $sql = "UPDATE `personalinfo` SET 
                `Degree`='{$_POST['degreename']}',
                `Institutename`='{$_POST['institutename']}',
                `Grade`='{$_POST['result']}',
                `Passedyr`='{$_POST['passingyear']}'
                WHERE Userid = '$userId'";

            $res = $this->conn->query($sql);
            $responses['education'] = $res;
        }

        // Update experience info
        if (isset($_POST['experience'])) {
            $sql = "UPDATE `personalinfo` SET 
                `Expdesc`='{$_POST['Experience']}'
                WHERE Userid = '$userId'";

            $res = $this->conn->query($sql);
            $responses['experience'] = $res;
        }

        // Update bank info
        if (isset($_POST['bank'])) {
            $sql = "UPDATE `personalinfo` SET 
                `Bankname`='{$_POST['Bankname']}',
                `Accname`='{$_POST['Accountname']}',
                `Accnum`='{$_POST['Accountnumber']}',
                `Pannum`='{$_POST['Pannumber']}' 
                WHERE Userid = '$userId'";

            $res = $this->conn->query($sql);
            $responses['bank'] = $res;
        }

        return $responses;
    }
}

class Leave {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function calcDuration() {
            // SQL query to fetch the necessary columns
            $sql = "SELECT LID, Empname, Leavetype, Startdate, Enddate,
             Reason, Status FROM leaverequest WHERE Status='pending'";
            
            // Execute the query
            $res = $this->conn->query($sql);
            
            // Check if the query was successful
            if ($res === false) {
                return false;
            }
            
            // Array to store results with durations
            $durations = [];
            
            // Loop through each record
            while ($row = $res->fetch_assoc()) {
                // Calculate the number of days between Startdate and Enddate
                $startDate = new DateTime($row['Startdate']);
                $endDate = new DateTime($row['Enddate']);
                $interval = $startDate->diff($endDate);
                $days = $interval->days + 1; // Add 1 to ensure at least one day duration
                
                // Add the duration to the result array
                $row['Leaveduration'] = $days;
                $durations[] = $row;
            }
            
            return $durations;
        }
}
    
class Notice {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function viewNotice() {
        $query = "SELECT NID, Issuedate, Details FROM notice ORDER BY NID DESC";
        $res = $this->conn->query($query);
        return $res;
    }

    public function manageNotice($delete_id) {
        $delete_id = intval($delete_id);
        $delete_sql = "DELETE FROM notice WHERE NID = $delete_id";
        $this->conn->query($delete_sql);
    }
}

class Attendance{
    private $conn;
    public function __construct($conn){
        $this->conn=$conn;
    }
    public function getAttendance(){
        $query="SELECT 
                    attendance.user_id, 
                    userdb.Username,
                    DATE_FORMAT(attendance.check_in, '%Y-%m') AS month, 
                    COUNT(DISTINCT DATE(attendance.check_in)) AS days_attended 
                FROM 
                    attendance 
                JOIN 
                    userdb 
                ON 
                    attendance.user_id = userdb.UserId 
                GROUP BY 
                    attendance.user_id, month 
                ORDER BY 
                    attendance.user_id, month";
        $res=$this->conn->query($query);
        return $res;
    }
   
    public function markAttendance($user_id) {
            $user_id = intval($user_id); // Ensure user_id is an integer
            $sql = "INSERT INTO attendance (user_id, check_in) VALUES ($user_id, NOW())";
            $res = $this->conn->query($sql);
            return $res;
        }
}
    
    // public updateAttendance(){

    // }


class Payroll{
    private $conn;
    public function __construct($conn){
        $this->conn=$conn;
    }
    // public function calculateSalary(){
    //     $query="";
    //     $res=this->conn->query($query);
    //     return $res;
    // }

}

class Admin {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function issueNotice($date, $details) {
        $date = $this->conn->real_escape_string($date);
        $details = $this->conn->real_escape_string($details);
        $query = "INSERT INTO `notice`(`Issuedate`, `Details`) VALUES ('$date', '$details')";
        $res = $this->conn->query($query);
        return $res;
    }

    public function updateLeaveStatus($lid, $status) {
        $lid = intval($lid);
        $status = $this->conn->real_escape_string($status);
        $query = "UPDATE leaverequest SET status = '$status' WHERE LID = $lid";
        $res = $this->conn->query($query);
        return $res;
    }
    

    public function viewProfile($userId){
        $query = "SELECT Firstname, Lastname, Userid, Bloodgroup, Gender, Tempadd,
        Tempcity, Tempcnty, Peradd, Percity, Percnty, Degree, Institutename,
        Grade, Passedyr, Expdesc, Bankname, Accname, Accnum, Pannum 
        FROM personalinfo WHERE Userid = '$userId'";
        $res = $this->conn->query($query);
        return $res;
    }
    public function updateProfile($userId) {
        $responses = [];

        // Update personal info
        if (isset($_POST['personal'])) {
            $sql = "UPDATE `personalinfo` SET 
                `Firstname`='{$_POST['fname']}',
                `Lastname`='{$_POST['lname']}',
                `Bloodgroup`='{$_POST['bldgrp']}',
                `Gender`='{$_POST['gender']}'
                WHERE Userid = '$userId'";

            $res = $this->conn->query($sql);
            $responses['personal'] = $res;
        }

        // Update address info
        if (isset($_POST['address'])) {
            $sql = "UPDATE `personalinfo` SET 
                `Tempadd`='{$_POST['taddress']}',
                `Tempcity`='{$_POST['tcity']}',
                `Tempcnty`='{$_POST['tcountry']}',
                `Peradd`='{$_POST['paddress']}',
                `Percity`='{$_POST['pcity']}',
                `Percnty`='{$_POST['pcountry']}'
                WHERE Userid = '$userId'";

            $res = $this->conn->query($sql);
            $responses['address'] = $res;
        }

        // Update education info
        if (isset($_POST['education'])) {
            $sql = "UPDATE `personalinfo` SET 
                `Degree`='{$_POST['degreename']}',
                `Institutename`='{$_POST['institutename']}',
                `Grade`='{$_POST['result']}',
                `Passedyr`='{$_POST['passingyear']}'
                WHERE Userid = '$userId'";

            $res = $this->conn->query($sql);
            $responses['education'] = $res;
        }

        // Update experience info
        if (isset($_POST['experience'])) {
            $sql = "UPDATE `personalinfo` SET 
                `Expdesc`='{$_POST['Experience']}'
                WHERE Userid = '$userId'";

            $res = $this->conn->query($sql);
            $responses['experience'] = $res;
        }

        // Update bank info
        if (isset($_POST['bank'])) {
            $sql = "UPDATE `personalinfo` SET 
                `Bankname`='{$_POST['Bankname']}',
                `Accname`='{$_POST['Accountname']}',
                `Accnum`='{$_POST['Accountnumber']}',
                `Pannum`='{$_POST['Pannumber']}' 
                WHERE Userid = '$userId'";

            $res = $this->conn->query($sql);
            $responses['bank'] = $res;
        }

        return $responses;
    }
}




?>
