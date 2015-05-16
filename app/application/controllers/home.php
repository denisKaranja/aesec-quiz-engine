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

    $keyword = substr($user_message, 0, 5);
    $succeeding_msg = substr($user_message, 5);

    $current_date_time = date("Y-m-d H:i:s");

    if ($keyword == "flit ")
    {
        #send the user a question
        $this->verify_user_answer($phone_number, $succeeding_msg, $current_date_time, $sender);
      
    }

    # tester
    $this->verify_user_answer($phone_number, $succeeding_msg, $current_date_time, $sender);	
	}
  
  public function verify_user_answer($phone_number, $succeeding_msg, $current_date_time, $sender)
  {

    # @params -> gets message from the user i.e 'flit message comes here'
    # @return -> sends feedback to the user

    //testers
    $phone_number = "+254714315084";
    $succeeding_msg = "aiesec";


    # check if user is registered
    if($this->quiz_model->is_user_registered($phone_number))
    {
      # registered user
      $quiz_id = $this->quiz_model->get_quiz_count($phone_number);
      $question = $this->quiz_model->get_question($quiz_id);

      echo "You're already registered...<br>";
      echo "Q-> ".$question."<br>";

      $is_correct = $this->quiz_model->is_answer_correct($quiz_id, $phone_number, $succeeding_msg);

      if($is_correct)
      {
        $response = $this->quiz_model->get_db_field("quiz_id", $quiz_id, "right_response", "quest_answer");
        echo "A->> ".$response."<br>";

        # update quiz_count in members table
        $this->quiz_model->update_quiz_count($phone_number, $quiz_id);

        # send next question
        $next_question = 
      }
      else
      {
        $response = $this->quiz_model->get_db_field("quiz_id", $quiz_id, "wrong_response", "quest_answer");
        echo "A->> ".$response."<br>";
      }

      $this->send_sms($phone_number, $question, $sender);
    }
    else
    {
      # register user first
      $user_registered = $this->quiz_model->register_user($phone_number, strtoupper($succeeding_msg), $current_date_time);

      $question = $user_registered;

      echo "You've been registered...<br>";
      echo "Q-> ".$question."<br>";
      # send question to user
      $this->send_sms($phone_number, $question, $sender);
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