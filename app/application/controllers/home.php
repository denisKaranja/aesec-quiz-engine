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
        $this->receive_sms($phone_number, $succeeding_msg, $current_date_time, $sender);
      
    }

    echo "Hello there ;)...";

		
	}
  
  public function receive_sms()
  {

  }

	public function send_sms()
	{

	}

	public function get_question()
	{

	}


}