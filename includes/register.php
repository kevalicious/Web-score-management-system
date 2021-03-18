<?php 

class Register 
{

    private $fullnames;
    private $email;
    private $password;

    public function saveUser()
    {
        //server
        $db = new Server;
        $this->database = $db->dbConnect();

        //get tablename from login class
        $login = new Login;
        $table = $login->getTablename();
        $u_email = $this->getEmail();
        $u_pass = $this->getPassword();

        //check if user already xst 
        $sql = "SELECT * FROM $table WHERE email = :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(":email", $u_email);

        try {
            $stmt->execute();

        } catch (\Exception $e) {
            $this->throwError(EXECUTION_ERROR,  $e->getMessage());
        }

        $row = $stmt->rowCount();
        if($row > 0)
        {
            $this->throwError(RECORD_EXIST, 'User already registered.');
        }

        //proceed with registration
        $u_fullnames = $this->getFullnames();
        $insert = "INSERT INTO $table(`id`, `fullnames`, `email`, `passcode`) VALUES(NULL, :fullnames, :email, :passcode)";
        $stmt = $this->database->prepare($insert);

        $stmt->bindParam(":fullnames", $u_fullnames);
        $stmt->bindParam(":email", $u_email);
        $stmt->bindParam(":passcode", $u_pass);

        try {
            $stmt->execute();

        } catch (\Exception $e) {
            $this->throwError(EXECUTION_ERROR,  $e->getMessage());
        }

        //throw success response
        $this->throwSuccess(902);



    }
    

    public function setFullnames($fullnames){ $this->fullnames = $fullnames; }
    public function setEmail($email){ $this->email = $email; }
    public function setPassword($password){ $this->password = $password; }

    public function getFullnames()
    {
        return $this->fullnames;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    //define errors thrown
    public function throwError($code, $msg)
    {
        header("content-type: application/json");
        echo json_encode(["error"=>["code"=>$code, "message"=>$msg]]);
        exit;
    }

    public function throwResponse($code, $msg)
    {
        header("content-type: application/json");
        echo json_encode(["response"=>["code"=>$code, "message"=>$msg]]);
        exit;
    }

    //define success 
    public function throwSuccess($code){
        header("content-type: application/json");
        echo json_encode(["response"=>["code"=>$code]]);
        exit;
    }

}