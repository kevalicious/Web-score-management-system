<?php 

class Category
{

     private $catname;
     private $tablename = "categories";

     public function setCategory($catname)
     {
       $this->catname = $catname;
     }

     public function getCategory()
     {
         return $this->catname;
     }

     public function getTablename()
     {
         return $this->tablename;
     }

     public function saveCategory()
     {
         //connect to db
         $db = new Server;
         $this->database = $db->dbConnect();

         //check if category already exist
         $table = $this->getTablename();
         $cat = $this->getCategory();
         $sql = "SELECT `category` FROM $table WHERE category = :category";
         $stmt = $this->database->prepare($sql);
         $stmt->bindParam(":category", $cat);

         if(!$stmt->execute())
         {
             $this->throwError(EXECUTION_ERROR, 'Error executing code. Check code @line33');
         }
         else{
             //if record exist
             $rownum = $stmt->rowCount();
             if($rownum > 0)
             {
               $this->throwResponse(RECORD_EXIST,   $cat.' already exist.');
             }

             //insert record
             $sql = "INSERT INTO $table(`id`, `category`)VALUES(NULL, :category)";
             $stmt = $this->database->prepare($sql);
             $stmt->bindParam(":category", $cat);

             if(!$stmt->execute())
             {
                 $this->throwError(EXECUTION_ERROR,   'Error saving '.$cat.'. Check your code @line50');
             }
             else{
                 //insert record
                 //success message
                 $this->throwResponse(RECORD_ADDED, $cat.' added successfully.');
             }

         }


     }


     public function throwResponse($code, $msg)
     {
         header("content-type: application/json");
         echo json_encode(["code"=>$code, "msg"=>$msg]);
         exit;

     }

     public function throwError($code, $msg)
     {
         header("content-type: application/json");
         echo json_encode(["code"=>$code, "msg"=>$msg]);
         exit;

     }

}