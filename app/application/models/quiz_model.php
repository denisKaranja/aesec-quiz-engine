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
				$quiz_id = $quiz->quiz_count;
			}

			return $this->get_question($quiz_id);
		}
		else
		{
			return false;
		}
	}

	public function get_question($quiz_number)
	{
		#	get the question after user registration
		#	@params int(quiz_id)
		#	@return string(question)

		$where_data = array(
				"quiz_id" => $quiz_number
			);

		$this->db->select("question");
		$question = $this->db->get_where("quest_answer", $where_data);

		foreach($question->result() as $key)
		{
			return $key->question;
		}
	}

	public function get_quiz_count($phone_number)
	{
		#	get quiz_cout for the member
		#	@params int(phone numeber)
		#	@return int(quiz id)

		$where_data = array(
				"phone_number" => $phone_number
			);

		$this->db->select("quiz_count");
		$question = $this->db->get_where("members", $where_data);

		foreach($question->result() as $key)
		{
			$quiz_id = $key->quiz_count;
		}

		return $quiz_id;
	}

	public function get_db_field($unique_value, $field_name, $table_name)
	{
		$where_data = array(
				$unique_value => $unique_value
			);

		$this->db->select($field_name);
		$value = $this->db->get_where($table_name, $where_data);

		foreach($value->result() as $key)
		{
			$result = $key->field_name;
		}

		return $result;
	}


}