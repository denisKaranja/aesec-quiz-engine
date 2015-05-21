<?php

class Quiz_model extends CI_Model
{

	/**
	*	@access public
	*	@param String $phone_numer
	*	@return Boolean
	*/
	 function is_user_registered($phone_number)
	{
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

	/**
	*	@access public
	*	@param String $phone_number, String $name, String $time
	*	@return String question
	*/
	 function register_user($phone_number, $name, $time)
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

	/**
	*	@access public
	*	@param Integer $quiz_number
	*	@return String question
	*/
	 function get_question($quiz_number)
	{
		#	get the question after user registration
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

	/**
	*	@access public
	*	@param String $phone_number
	*	@return Integer $quiz_id
	*/
	 function get_quiz_count($phone_number)
	{
		#	get quiz_cout for the member
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

	/**
	*	@access public
	*	@param String $unique_field, Integer $unique_value, String $field_name, String $table_name
	*	@return String, Integer
	*/
	 function get_db_field($unique_field, $unique_value, $field_name, $table_name)
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

	/**
	*	@access public
	*	@return Boolean
	*
	*/
	 function is_answer_correct($unique_filed, $quiz_count, $phone_number, $user_answer, $field_name, $table)
	{
		# check if user answer is correct
		$where_data = array(
				$unique_filed => $quiz_count
			);

		$this->db->select($field_name);
		$db_answer = $this->db->get_where($table, $where_data);

		foreach($db_answer->result() as $key)
		{
			$db_answer = $key->$field_name;
		}

		# question 4 two answer
		if($quiz_count == 4)
		{
			if(($db_answer == $user_answer) || ("aiesecer" == $user_answer) || ("aiesecers" == $user_answer))
			{
				#	correct answer
				return true;
			}
			else
			{
				return false;
			}
		}

		# question 6 two answer
		if($quiz_count == 6)
		{
			if(($db_answer == $user_answer) || ("afro-xlds" == $user_answer))
			{
				#	correct answer
				return true;
			}
			else
			{
				return false;
			}
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

	/**
	*	@access public
	*	@param String $phone_number, Integer $quiz_count
	*	@return Boolean
	*/
	 function update_quiz_count($phone_number, $quiz_count)
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

	/**
	*	@access public
	*	@param String $phone_number, Integer $pb_count
	*	@return Boolean
	*/
	 function update_probation_count($phone_number, $pb_count)
	{
		#	update probation status when the user submits the wrong answer
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

	/**
	*	@access public
	*	@return Boolean
	*
	*/
	 function reset_probation_status($unique_filed, $unique_value, $update_field, $update_value)
	{
		#	reset the probation status of a user when they answer the redemption quiz
		$update_data = array(
				$update_field => $update_value
			);

		$where_data = array(
				$unique_filed => $unique_value
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

	/**
	*	@access public
	*	@param String $phone_number, Integer $pb_status
	*	@return Boolean
	*/
	 function update_probation_status($phone_number, $pb_status)
	{
		# update probation status(for first timers on probation)
		$pb_status += 1;

		$update_data = array(
				"first_time_probation" => $pb_status
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

	/**
	* @access public
	*/
	function update_winners($phone_number)
	{
		$where_data = array(
				"phone_number" => $phone_number
			);

		$update_data = array(
				"aiesec_winner" => 1
			);

		$this->db->update("members", $update_data, $where_data);
	}


	/**
	*	@access public
	*	@param String $phone_number
	*	@return boolean
	*/
	function is_already_a_winner($phone_number)
	{
		$where_data = array(
				"phone_number" => $phone_number
			);

		$this->db->select("aiesec_winner");

		$winner = $this->db->get_where("members", $where_data);

		foreach($winner->result() as $our_key)
		{
			$new_winner = $our_key->aiesec_winner;
		}

		if($new_winner == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	*	@access public
	*	@param String $phone_number
	*	@return Int
	*/
	function is_disqualified($phone_number)
	{
		#	disqualify a user after failing redemption question twice

		$where_data = array(
				"phone_number" => $phone_number
			);

		$this->db->select("first_time_probation");

		$disqualify = $this->db->get_where("members", $where_data);

		foreach($disqualify->result() as $our_key)
		{
			$find_out = $our_key->first_time_probation;
		}

		return $find_out;
	}


	/**
	*	@access public
	*	@param String $phone_number
	*	@return Int
	*/
	function disqualify_user($phone_number)
	{
		#	disqualify a user
		$where_data = array(
				"phone_number" => $phone_number
			);

		$update_data = array(
				"active" => 0
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

	/**
	*	@access public
	*	@param String $phone_number
	*	@return Int
	*/
	function is_inactive($phone_number)
	{
		#	checks to see if a user is disqualified
		$where_data = array(
				"phone_number" => $phone_number,
				"active" => 0
			);

		$query = $this->db->get_where("members", $where_data);

		if($query->num_rows() == 1)
		{
			return true;
		}
		elseif($query->num_rows() == 0)
		{
			return false;
		}
	}

	/**
	*	@access public
	*	@param String $phone_number
	*	@return boolean
	*/
	function save_winner($phone_number, $date_time)
	{
		$insert_data = array(
				"phone_number" => $phone_number,
				"finish_time" => $date_time
			);

		if($this->db->insert("winners", $insert_data))
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	/**
	*	@access public
	*	@param String $phone_number
	*	@return String
	*/
	function get_winners()
	{
		$list_of_winners = '';

		$this->db->select('members.name, members.phone_number');
		$this->db->from('members');
		$this->db->join('winners', 'winners.phone_number = members.phone_number');
		$query = $this->db->get();

		if($query)
		{
			foreach($query->result() as $our_winners)
			{
				$name = $our_winners->name;
				$phone = $our_winners->phone_number;

				$list_of_winners .= $name." -> ".$phone."\n";
			}


		}

		return $list_of_winners;
	}

}