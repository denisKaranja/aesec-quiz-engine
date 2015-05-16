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
    $phone_number = "+25445832352";
    $succeeding_msg = "Denis Mburu";
    # check if user is registered
    if($this->quiz_model->is_user_registered($phone_number))
    {
      # registered user
      echo "You are already registered :) ";
    }
    else
    {
      # register user first
      $user_registered = $this->quiz_model->register_user($phone_number, strtoupper($succeeding_msg));

      $new_question = $user_registered;

      echo $new_question;
    }


  }

	public function send_sms()
	{

	}

	public function get_question()
	{

	}


}