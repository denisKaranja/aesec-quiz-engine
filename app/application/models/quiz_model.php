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




	
}