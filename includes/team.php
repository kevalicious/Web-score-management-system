<?php 

class Team
{
    private $team;
    private $image;
    private $tablename = "teams";
    private $overallScoresTb = "overallscores";

    public function setTeam($team){ $this->team = $team; }
    public function setImage($image){ $this->image = $image; }

    public function getTeam()
    {
        return $this->team;
    }

    public function getImage()
    {
        return $this->image;

    }

    public function getTablename()
    {
        return $this->tablename;
    }

    public function getOverallScoresTb()
    {
        return $this->overallScoresTb;
    }

    public function saveTeam()
    {

        //connect to db
        $db = new Server;
        $this->database = $db->dbConnect();
        
        //check team existance
        $tbl = $this->getTablename();
        $team = $this->getTeam();
        $sql = "SELECT `team` FROM $tbl WHERE team = :team";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(":team", $team);

        try {
            $stmt->execute();
        } catch (\Exception $e) {
            
             $this->throwError(EXECUTION_ERROR,  $e->getMessage());
        }
        

        //save team
        $row = $stmt->rowCount();
        if($row > 0)
        {
            //team exist
            $this->throwResponse(RECORD_EXIST,  $team. ' already registered.');
        }

        //writing code for two teams max.
        //if two teams have been recorded, it will not save more records
        $checksum = "SELECT * FROM $tbl";
        $stmt = $this->database->query($checksum);
        $sum = $stmt->rowCount();
        if($sum >= 2)
        {
            //throw response for full record
            $this->throwResponse(FULL_RECORD,   'Default teams are set to 2 max.');
        }
        

        //save
        $s_team = $this->getTeam();
        $s_image = $this->getImage();
        $sql = "INSERT INTO $tbl(`id`, `team`, `image`)VALUES(NULL, :team, :imagefile)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(":team", $s_team);
        $stmt->bindParam(":imagefile", $s_image);

        try {
            $stmt->execute();
        } catch (\Exception $e) {
            $this->throwError(EXECUTION_ERROR,  $e->getMessage());
        }

         //add overallscore tb data
        $overTb = $this->getOverallScoresTb();
        $ovrteam = $this->getTeam();
        $score = 0;

        $inputdta = "INSERT INTO $overTb(`id`,`team`,`score`)VALUES(NULL, :team, :score)";
        $stmt = $this->database->prepare($inputdta);
        $stmt->bindParam(":team", $ovrteam);
        $stmt->bindParam(":score", $score);

        try {
            $stmt->execute();
        } catch (\Exception $e) {
            $this->throwError(EXECUTION_ERROR,  $e->getMessage());
        }

        //success response
        $this->throwResponse(RECORD_ADDED,  $team . ' registered successfully.');

       


    }

    public function throwError($code, $msg)
    {
        header("content-type: application/json");
        echo json_encode(["code"=>$code, "msg"=>$msg]);
        exit;
    }


    public function throwResponse($code, $msg)
    {
        header("content-type: application/json");
        echo json_encode(["code"=>$code, "msg"=>$msg]);
        exit;
    }


}