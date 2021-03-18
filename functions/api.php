<?php 
require_once("rest.php");
require_once("includes/login.php");
require_once("includes/register.php");
require_once("includes/participants.php");
require_once("includes/category.php");
require_once("includes/team.php");
require_once("includes/activities.php");
require_once("includes/challenge.php");


class Api extends Rest
{
    public $database;

    public function __construct()
    {
        parent::__construct();
        //$this->database;
    }

    //login
    public function userLogin()
    {
      $email = $this->validateParameters('email', $this->param['email'], 'STRING');
      $password = $this->validateParameters('password', $this->param['password'], 'STRING');
    

    }

    //registration
    public function userRegistration()
    {
      $fullnames = $this->validateParameters('fullnames', $this->param['fullnames'], 'STRING');
      $email = $this->validateParameters('email', $this->param['email'], 'STRING');
      $password = $this->validateParameters('password', $this->param['password'], 'STRING');

    }

    //define methods(apinames)
   public function addParticipants()
   {
       $fname = $this->validateParameters('fname', $this->param['fname'], 'STRING');
       $lname = $this->validateParameters('lname', $this->param['lname'], 'STRING');
       $phone = $this->validateParameters('phone', $this->param['phone'], 'INTEGER');
       
       //call participants class 
       $participant = new Participants;

       //assign values
       $participant->setFname($fname);
       $participant->setLname($lname);
       $participant->setPhone($phone);

       //save
       $participant->saveParticipant();
       
       
   }

   public function addCategory()
   {
       $a_cat = $this->validateParameters('category', $this->param['category'], 'STRING'); 

       $cat = new Category;
       $cat->setCategory($a_cat);

       $cat->saveCategory();

   }

   public function addTeam()
   {
      $p_team = $this->validateParameters('team', $this->param['team'], 'STRING');
      $p_image = $this->validateParameters('image', $this->param['image'], 'STRING');

      
      $team = new Team;

      $team->setTeam($p_team);
      $team->setImage($p_image);

      //save
      $team->saveTeam();

   }

   public function addActivity()
   {
       $activity = $this->validateParameters('activity', $this->param['activity'], 'STRING');
       $category = $this->validateParameters('category', $this->param['category'], 'STRING');
       $gameplay = $this->validateParameters('gameplay', $this->param['gameplay'], 'STRING');

       $t_activities = new Activities;

       $t_activities->setActivity($activity);
       $t_activities->setCategory($category);
       $t_activities->setGameplay($gameplay);

       //verify and save
       $t_activities->saveActivity();


   }

   public function recordActivity()
   {
      $activity = $this->validateParameters('activity', $this->param['activity'], 'STRING');
      $team = $this->validateParameters('team', $this->param['team'], 'STRING');
      $score = $this->validateParameters('score', $this->param['score'], 'INTEGER');
      
      $challenge = new Challenge;

      $challenge->setActivity($activity);
      $challenge->setTeam($team);
      $challenge->setScore($score);

      $challenge->gamePlay();

   }

   //view activities
   public function viewallActivities()
   {
       //token id
       
       $activity = new Activities;
      
       $activity->Activities();


   }

   public function viewActivity()
   {
        $id = $this->validateParameters('id', $this->param['id'], 'INTEGER');

        $activity = new Activities;
        $activity->setId($id);

        //call it
        $activity->myActivity();

   }

}