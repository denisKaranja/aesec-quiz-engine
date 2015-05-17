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

	public function register_user($phone_number, $name, $time)
	{
		#	register a user to the quiz game
		#	@params int(phone number), name(string)
		# @return string(question 1)

		$insert_data = array(
				"phone_number" => $phone_number,
				"name" => $name,
				"time" => $time
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

	public function get_db_field($unique_field, $unique_value, $field_name, $table_name)
	{
		$where_data = array(
				$unique_field => $unique_value
			);

		$this->db->select($field_name);
		$value = $this->db->get_where($table_name, $where_data);

		foreach($value->result() as $key)
		{
			$results = $key->$field_name;
		}

		return $results;
	}

	public function is_answer_correct($unique_filed, $quiz_count, $phone_number, $user_answer, $field_name, $table)
	{
		# check if user answer is correct
		# @params int(quiz number), int(phone number), string(answer)
		#	@return 

		$where_data = array(
				$unique_filed => $quiz_count
			);

		$this->db->select($field_name);
		$db_answer = $this->db->get_where($table, $where_data);

		foreach($db_answer->result() as $key)
		{
			$db_answer = $key->$field_name;
		}

		if($db_answer == $user_answer)
		{
			#	correct answer
			return true;
		}
		else
		{
			return false;
		}
	}

	public function update_quiz_count($phone_number, $quiz_count)
	{
		#	update quiz count and increment the present value by 1
		# @params string(phone number), int(quiz id)
		# @return boolean

		$quiz_count += 1;

		$update_data = array(
				"quiz_count" => $quiz_count
			);

		$where_data = array(
				"phone_number" => $phone_number
			);

		$update_count = $this->db->update("members", $update_data, $where_data);

		if($update_count)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function update_probation_count($phone_number, $pb_count)
	{
		#	update probation status when the user submits the wrong answer
		#	@params int(phone number)
		# @return boolean
		$pb_count += 1;

		$where_data = array(
				"phone_number" => $phone_number
			);

		$update_data = array(
				"probation_count" => $pb_count
			);

		if($this->db->update("members", $update_data, $where_data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function reset_probation_status($phone_number)
	{
		#	reset the probation status of a user when they answer the redemption quiz
		#	@params int(phone number)
		#	@return boolean

		$update_data = array(
				"first_time_probation" => 0
			);

		$where_data = array(
				"phone_number" => $phone_number
			);

		if($this->db->update("members", $update_data, $where_data))
		{
			return true;
		}
		else
		{
			return false;
		}

	}







}