<?php 

class Activities
{
  
    private $activity;
    private $aid;
    private $category;
    private $gameplay;
    private $token;
    private $tablename = "activities";
    private $catTablename = "categories";

    public function saveActivity()
    {
        //check if selected category is valid
        //verify with db
        $db = new Server;
        $this->database = $db->dbConnect();

        $tbl = $this->getTablename();
        $cat_table = $this->getCattable();
        
        //check if category resonates in category table
        $cat = $this->getCategory();
        $sql = "SELECT * FROM $cat_table WHERE category = :category";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(":category", $cat);

        if(!$stmt->execute())
        {
            $this->throwError(EXECUTION_ERROR,  'Error executing command @line24.');
        }

        $row = $stmt->rowCount();
        if($row < 1)
        {
           //not valid category
           $this->throwResponse(RECORD_DOESNT_EXIST,    $cat . ' is invalid.');
        }

        //prevent duplicate activity names
        $act = $this->getActivity();
        $pick = "SELECT * FROM $tbl WHERE activity = :activity";
        $stmt = $this->database->prepare($pick);
        $stmt->bindParam(":activity", $act);

        if(!$stmt->execute())
        {
            $this->throwError(EXECUTION_ERROR,  'Error executing command @line42.');
        }

        //does activity exist?
        $rowcount = $stmt->rowCount();
        if($rowcount > 0)
        {
            $this->throwResponse(RECORD_EXIST,  $act . ' is already recorded.');
        }

        //save
        $activity = $this->getActivity();
        $category = $this->getCategory();
        $gameplay = $this->getGameplay();
        $insertdata = "INSERT INTO $tbl(`id`, `activity`, `category`, `gameplay`)VALUES(NULL, :activity, :category, :gameplay)";
        $stmt = $this->database->prepare($insertdata);
        $stmt->bindParam(":activity", $activity);
        $stmt->bindParam(":category", $category);
        $stmt->bindParam(":gameplay", $gameplay);

        if(!$stmt->execute())
        {
            $this->throwError(EXECUTION_ERROR,  'Error executing command @line62.');
        }

        //success
        $this->throwResponse(RECORD_ADDED,  $activity . ' saved successfully.');



    }

    //view activity
    public function Activities()
    {
        //decode token 
        //if token is valid, proceed.
        //else throw token error token


        $db = new Server;
        $this->database = $db->dbConnect();

        $tbl = $this->getTablename();

        $sql = "SELECT * FROM $tbl";
        $stmt = $this->database->prepare($sql);
        if(!$stmt->execute())
        {
            $this->throwError(EXECUTION_ERROR,  'Error executing command @line94.');
        }

        $row = $stmt->rowCount();
        if($row < 1)
        {
            $this->throwResponse(NO_DATA, 'There are no activities yet.');
        }
        else{

        //show all
         $read = $stmt->fetchAll();
         
         header("content-type: application/json");
        // echo json_encode(["response"=>$read]);

        }
       

    }

    public function myActivity()
    {
        //connect to db 
        $db = new Server;
        $this->database = $db->dbConnect();
        
        //access db
        $id = $this->getAid();
        $tbl = $this->getTablename();
        $sql = "SELECT * FROM $tbl WHERE id = :id ";
        $stmt = $this->database->prepare($sql);

        $stmt->bindParam(":id", $id);

        if(!$stmt->execute())
        {
            $this->throwError(EXECUTION_ERROR,  'Error executing command @line124.');
        }

        //fetch assoc data
        $rowcount = $stmt->rowCount();
        if($rowcount < 1)
        {
            $this->throwResponse(NO_DATA,   'No such activity in the database.');
        }

        else{
            $act_data = $stmt->fetch(PDO::FETCH_ASSOC);
            header('content-type: application/json');
            echo json_encode(["response"=>$act_data]);
        }





    }

    public function getTablename()
    {
        return $this->tablename;
    }

    public function getCattable()
    {
        return $this->catTablename;
    }

    public function setActivity($activity)
    {
        $this->activity = $activity;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setId($aid)
    {
        $this->aid = $aid;
    }

    public function getAid()
    {
        return $this->aid;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function setGameplay($gameplay)
    {
        $this->gameplay = $gameplay;
    }

    public function getActivity()
    {
        return $this->activity;
    }

    public function getToken()
    {
        return $this->token;
    }

   

    public function getCategory()
    {
        return $this->category;
    }

    public function getGameplay()
    {
        return $this->gameplay;
    }

    //error handling
    public function throwError($code, $msg)
    {
        header("content-type: application/json");
        echo json_encode(["code"=>$code, "msg"=>$msg]);
        exit;
    }

    //responses
    public function throwResponse($code, $msg)
    {
        header("content-type: application/json");
        echo json_encode(["code"=>$code, "msg"=>$msg]);
        exit;
    }



}