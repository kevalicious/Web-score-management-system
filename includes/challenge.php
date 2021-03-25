<?php 

class Challenge
{

    private $activity;
    private $team;
    private $score;
    private $complete = 1;
    private $table = "rawscores";
    private $overscoreTb = "overallscores";

    public function setActivity($activity){ $this->activity = $activity; }
    public function setTeam($team){ $this->team = $team; }
    public function setScore($score){ $this->score = $score; }
    
    

    public function getActivity()
    {
        return $this->activity;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function getComplete()
    {
        return $this->complete;
    }

    public function getTablename()
    {
        return $this->table;
    }

    public function getOvrTb()
    {
        return $this->overscoreTb;
    }

    public function gamePlay()
    {
        //connect to db
        $db = new Server;
        $this->database = $db->dbConnect();

        $act = $this->getActivity();
        $tm = $this->getTeam();
        $score = $this->getScore();
        $complete = $this->getComplete();
        $tbl = $this->getTablename();

        //verify activity and team names
        $act_table = "activities";
        $verify = "SELECT * FROM $act_table WHERE activity = :activity ";
        $stmt = $this->database->prepare($verify);
        $stmt->bindParam(":activity", $act);
        if(!$stmt->execute())
        {
            $this->throwError(EXECUTION_ERROR,  'Error executing command @line57');
        }

        $rows = $stmt->rowCount();
        if($rows < 1)
        {
            $this->throwResponse(RECORD_DOESNT_EXIST,   $act . ' is invalid.');
        }

        //team verification
        $tm_table = "teams";
        $verify = "SELECT * FROM $tm_table WHERE team = :team ";
        $stmt = $this->database->prepare($verify);
        $stmt->bindParam(":team", $tm);
        if(!$stmt->execute())
        {
            $this->throwError(EXECUTION_ERROR,  'Error executing command @line73');
        }

        $rows = $stmt->rowCount();
        if($rows < 1)
        {
            $this->throwResponse(RECORD_DOESNT_EXIST,   $tm . ' is invalid.');
        }


        //check if team already played
        $check = "SELECT * FROM $tbl WHERE activity = :activity AND team = :team ";
        $stmt = $this->database->prepare($check);
        $stmt->bindParam(":activity", $act);
        $stmt->bindParam(":team", $tm);
        if(!$stmt->execute())
        {
            $this->throwError(EXECUTION_ERROR,  'Error executing command @line89');
        }

        $numrow = $stmt->rowCount();
        if($numrow > 0)
        {
            $this->throwResponse(RECORD_EXIST,  $act . ' has been played by ' . $tm . '.');
        }


        //save record into raw scores table
        $sql = "INSERT INTO $tbl(`id`, `activity`, `team`, `score`, `complete`)VALUES(NULL, :act, :tm, :scr, :complete)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(":act", $act);
        $stmt->bindParam(":tm", $tm);
        $stmt->bindParam(":scr", $score);
        $stmt->bindParam(":complete", $complete);
        if(!$stmt->execute())
        {
           $this->throwError(EXECUTION_ERROR,   'Error executing command @line106');
        }

        //update overall scores tb
        //update score where team = team. formula: score = newscore + oldscore
       $ovrTb = $this->getOvrTb();
       $current = "SELECT `score` FROM $ovrTb WHERE team = :cteam";
       $stmt = $this->database->prepare($current);
       $stmt->bindParam(":cteam",  $tm);
       $stmt->execute();
       $row = $stmt->fetch();
       $ovscore = $row['score'] + $score;
      
       
       $upte = "UPDATE $ovrTb SET `score` = :score WHERE team = :team";
       $stmt = $this->database->prepare($upte);
       $stmt->bindParam(":score", $ovscore);
       $stmt->bindParam(":team", $tm);

       try {
           $stmt->execute();
       } catch (\Exception $e) {
           $this->throwError(EXECUTION_ERROR,   $e->getMessage());
       }


        //success
        $this->throwResponse(RECORD_ADDED,  'Recorded');

        

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