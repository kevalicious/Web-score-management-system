<?php 

class Participants
{

    private $fname;
    private $lname;
    private $phone;
    private $team;
    public $tablename = "participants";

    public function saveParticipant()
    {
        //connect to db
        $db = new Server;
        $this->database = $db->dbConnect();
        
        //VERIFY if user already registered
        $phonenumber = $this->getPhone();
        $sql = "SELECT `phone` FROM  $this->tablename WHERE phone = :phonenum";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(":phonenum", $phonenumber);
        if(!$stmt->execute())
        {
            header("content-type: application/json");
            echo json_encode("Error executing command. Check your code @line20");
        }
        else{
            $row = $stmt->rowCount();
            if($row > 0)
            {
                //record found
                $this->throwResponse(RECORD_EXIST,  'Already registered.');
            }
            
            //insert record
            $sql = "INSERT INTO $this->tablename(`id`, `fname`, `lname`, `phone`, `team`)
                    VALUES(NULL, :fname, :lname, :phone, :team)";

            $firstname = $this->getFname();
            $lastname = $this->getLname();
            $phone = $this->getPhone();
            $team = $this->getTeam();

            $stmt = $this->database->prepare($sql);
            $stmt->bindParam(":fname", $firstname);
            $stmt->bindParam(":lname", $lastname);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":team", $team);

            if(!$stmt->execute())
            {
                echo "Error inserting records. Check code @line36";
            }
            else{
               
                $this->throwResponse(RECORD_ADDED,  'Recorded successfully.');

            }

        }


    }

    public function setFname($fname){ $this->fname = $fname; }
    
    public function setLname($lname){ $this->lname = $lname;  }

    public function setPhone($phone){ $this->phone = $phone;  }

    public function setTeam($team){ $this->team = $team;  }


    public function getFname()
    {
        return $this->fname;
    }

    public function getLname()
    {
        return $this->lname;
    }

    public function getPhone()
    {
        return $this->phone;

    }

    public function getTeam()
    {
        return $this->team;

    }

    public function throwResponse($code, $msg)
    {
        header("content-type: application/json");
        echo json_encode(["code"=>$code, "msg"=>$msg]);
        exit;
    }

}