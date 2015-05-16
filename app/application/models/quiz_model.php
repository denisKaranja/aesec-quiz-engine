<?php

class Quiz_model extends CI_Model
{
	public function is_user_registered($phone_number)
	{
		# check if user is registered
		#	@params ->int(phone_number)
		#	@return boolean
		#

		$where_data = array(
				"phone_number" => $phone_number
			);

		$query = $this->db->get_where("members", $where_data);

		if($query->num_rows() == 0)
		{
			# user not registered
			return false;
		}
		else
		{
			return true;
		}
	}



	public function register_user($phone_number, $name)
	{
		#	register a user to the quiz game
		#	@params int(phone number), name(string)
		# @return string(question 1)

		$insert_data = array(
				"phone_number" => $phone_number,
				"name" => $name
			);

		if($this->db->insert("members", $insert_data))
		{

			$get_where = array(
					"phone_number" => $phone_number
				);

			$this->db->select("quiz_count");
			$question = $this->db->get_where("members", $get_where);

			foreach($question->result() as $quiz)
			{
				return $quiz->quiz_count;
			}
		}
		else
		{
			return false;
		}
	}




}