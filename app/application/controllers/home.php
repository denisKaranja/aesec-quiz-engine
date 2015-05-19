<?php
#helper gateway class
require_once('AfricasTalkingGateway.php');
error_reporting(E_ALL);


class Home extends CI_Controller
{
	private $data;


	function __construct()
	{
		parent::__construct();

    #default timezone
    date_default_timezone_set("Africa/Nairobi");

    # initialize quiz_model
    $this->load->model("quiz_model");

	}

	public function index()
	{
		# credentials
		$username = "aisec";
		$apikey = "63bea0c464119b566dbc6f93246abffdae17216ebbac2e6ef770e58f446a9cb9";

		#details from the user
		$phone_number = $this->input->post('from');
		$sender = $this->input->post('to');//shot code(sender)
		$user_message = trim(strtolower($this->input->post('text')));

    #$keyword = substr($user_message, 0, 5);
    #$succeeding_msg = substr($user_message, 5);

    $current_date_time = date("Y-m-d H:i:s");

    # tester
    $this->verify_user_answer($phone_number, $user_message, $current_date_time, $sender);	
	}
  
  public function verify_user_answer($phone_number, $succeeding_msg, $current_date_time, $sender)
  {

    # @params -> gets message from the user i.e 'flit message comes here'
    # @return -> sends feedback to the user

    //testers
    /*$phone_number = "+25472537383";
    $succeeding_msg = "Maureen Njambi";*/

    $welcome_msg = "Welcome to the University of Nairobi’s AIESEC WEEK Treasure Hunt. We want to challenge the AIESEC knowledge you have acquired over the week and over the years, if you are an AIESECer! Are you ready? Get your thinking cap on and let’s do this! All the luck buddy!
    \n\nProudly powered by Africa's Talking(www.africastalking.com)\n\n   
    ";

    $reply_format = "\n\n[reply with: {flit}{space}{your answer}]";


    # check if user is registered
    if($this->quiz_model->is_user_registered($phone_number))
    {
      # registered user
      $quiz_id = $this->quiz_model->get_quiz_count($phone_number);
      $question = $this->quiz_model->get_question($quiz_id);
      $pb_status = $this->quiz_model->get_db_field("phone_number", $phone_number, "probation_count", "members");

      if($pb_status <= 2)
      {
        # user answering for the actual quiz

        echo "You're already registered...<br>";
        echo "Q-> ".$question.$reply_format."<br>";

        $is_correct = $this->quiz_model->is_answer_correct("quiz_id", $quiz_id, $phone_number, $succeeding_msg, "answer", "quest_answer");

        if($is_correct)
        {
          $response = $this->quiz_model->get_db_field("quiz_id", $quiz_id, "right_response", "quest_answer");
          echo "A->> ".$response.$reply_format."<br>";

          # update quiz_count in members table
          $this->quiz_model->update_quiz_count($phone_number, $quiz_id);

          # send next question
          $next_question = $this->quiz_model->get_question($quiz_id + 1);

          echo $next_question.$reply_format;
          $this->send_sms($phone_number, $next_question.$reply_format, $sender);
        }
        else
        {
          # update the probation status
          $pb_count = $this->quiz_model->get_db_field("phone_number", $phone_number, "probation_count", "members");

          if($pb_count == 3)
          {
            # send user to probatio
            # send probation question

            $pb_question = "You are on probation!\n";
            $pb_question .= $this->quiz_model->get_db_field("quiz_id", $quiz_id, "probation_quiz", "quest_answer");

            echo "A->> ".$pb_question.$reply_format."<br>";

            # send user the message
            $this->send_sms($phone_number, $pb_question.$reply_format, $sender);
          }
          else
          {
            # update probation count for the member
            $this->quiz_model->update_probation_count($phone_number, $pb_count);

             $response = $this->quiz_model->get_db_field("quiz_id", $quiz_id, "wrong_response", "quest_answer");
             echo "A->> ".$response.$reply_format."<br>";

             #  re-send the question failed
             $this->send_sms($phone_number, $question.$reply_format, $sender);
             echo "A->> ".$question."<br>";
          }

        }
      }
      else
      {
        # user answering for the redemption island quiz

        # update first_time_redemption code
        $pb_id = $this->quiz_model->get_db_field("phone_number", $phone_number, "first_time_probation", "members");

        $this->quiz_model->update_probation_status($phone_number, $pb_id);


        # check if user just got into redepmtion island
        $pb_checker = $this->quiz_model->get_db_field("phone_number", $phone_number, "first_time_probation", "members");

        if($pb_checker == 1)
        {
          # user just got into probation
          # send user the same question
          $pb_question = "You gave 3 wrong answers. You've been put on probation. Sorry!\n\n";
          $pb_question .= $this->quiz_model->get_db_field("quiz_id", $quiz_id, "probation_quiz", "quest_answer");

          echo "A->> ".$pb_question.$reply_format."<br>";

          # send user the message
          $this->send_sms($phone_number, $pb_question.$reply_format, $sender);
        }
        else
        {
          # is a 2nd timer i.e he is answering the probation question
          $is_correct = $this->quiz_model->is_answer_correct("quiz_id", $quiz_id, $phone_number, $succeeding_msg, "probation_answer", "quest_answer");

          if($is_correct)
          {
            # got the correct answer
            # reset redemption status

            $this->quiz_model->update_probation_count($phone_number, -1);
            $this->quiz_model->reset_probation_status("phone_number", $phone_number, "probation_count", 0);
            $this->quiz_model->reset_probation_status("phone_number", $phone_number, "first_time_probation", 0);


            # send user the same question
            $prob_msg = "Probation code is correct :)\n\n";
            $prob_msg .= $question;

            echo $prob_msg.$reply_format;

            $this->send_sms($phone_number, $prob_msg.$reply_format, $sender);
          }
          else
          {
            # redemption code wrong -> re-send question
            $prob_msg = "WRONG probation code!\n\n";

            $prob_msg .= $this->quiz_model->get_db_field("quiz_id", $quiz_id, "probation_quiz", "quest_answer");

            echo $prob_msg.$reply_format;

            $this->send_sms($phone_number, $prob_msg.$reply_format, $sender);
          }
        } 
      }

    }
    else
    {
      # register user first
      $user_registered = $this->quiz_model->register_user($phone_number, strtoupper($succeeding_msg), $current_date_time);

      $question = $user_registered;

      echo $welcome_msg."<br>";
      echo "Q-> ".$question.$reply_format."<br>";

      # send welcome message to user
      $this->send_sms($phone_number, $welcome_msg, $sender);
      # send question to user
      $this->send_sms($phone_number, $question.$reply_format, $sender);
    }
  }

	public function send_sms($phone_number, $question, $sender)
	{
    # send question to the user using AfricasTalking API

    # credentials
    $username = "aisec";
    $apikey = "63bea0c464119b566dbc6f93246abffdae17216ebbac2e6ef770e58f446a9cb9";


    // Create a new instance of our awesome gateway class
    $gateway = new AfricasTalkingGateway($username, $apikey);
    // Any gateway errors will be captured by our custom Exception class below,
    // so wrap the call in a try-catch block
    
    try
    {
      // Thats it, hit send and we'll take care of the rest.
      

      $results = $gateway->sendMessage($phone_number, $question, $sender);
        
    }
    catch ( AfricasTalkingGatewayException $e )
    {
      echo "Encountered an error while sending: ".$e->getMessage();
    }
	}

	public function get_question()
	{

	}


}