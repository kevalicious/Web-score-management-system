<?php

class Login
{

  private $email;
  private $password;
  private $tablename = "auth";

  public function Ulogin()
  {
      //connect to server
      $db = new Server;
      $this->database = $db->dbConnect();

      //check if email exist
      $tbl = $this->getTablename();
      $eml = $this->getEmail();
      $sql = "SELECT * FROM $tbl WHERE email = :email";
      $stmt = $this->database->prepare($sql);
      $stmt->bindParam(":email", $eml);
      /* if(!$stmt->execute())
      {
          $this->throwError(EXECUTION_ERROR,    'failed to execute command @ line19');
      } */
      try {
          //code...
          $stmt->execute();
      } catch (\Exception $e) {
          $this->throwError(EXECUTION_ERROR,    'failed to execute command. '.$e->getMessage());
      }

      //proceed
      $email_row = $stmt->rowCount();
      if($email_row < 1)
      {
         $this->throwResponse(RECORD_DOESNT_EXIST, 'Email not registered.');
      }

      //check if passcode is valid
      $pswd = $this->getPassword();
      $sql_p = "SELECT * FROM $tbl WHERE passcode = :passcode";
      $stmt = $this->database->prepare($sql_p);
      $stmt->bindParam(":passcode", $pswd);

      try{
         $stmt->execute();
      }
      catch(\Exception $e)
      {
         $this->throwError(EXECUTION_ERROR,    'failed to execute command. '.$e->getMessage());
      }

      //if row exist 
      $p_row = $stmt->rowCount();
      if($p_row < 1)
      {
          $this->throwResponse(RECORD_DOESNT_EXIST, 'Invalid password.');
      }

      //throe success code
      $this->throwSuccess('900');


  }

  public function setEmail($email)
  {
      $this->email = $email;
  }

  public function setPassword($password)
  {
      $this->password = $password;
  }

  public function getEmail()
  {
      return $this->email;
  }

  public function getPassword()
  {
      return $this->password;
  }

  public function getTablename()
  {
      return $this->tablename;
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