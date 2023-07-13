<?php
	class User_model extends CI_Model{

		public function add_user($data){
			$this->db->insert('ci_users', $data);
			return true;
		}

		//---------------------------------------------------
		// get all users for server-side datatable processing (ajax based)
		public function get_all_users(){
			$this->db->select('*');
			$this->db->where('is_admin',0);
			$this->db->order_by('id','DESC');
			return $this->db->get('ci_users')->result_array();
		}

		public function get_fund_data()
		{
			$this->db->select('ci_users_fund_debit.*,ci_users.username,ci_users.account_no,ci_users.my_fund');
			$this->db->from('ci_users_fund_debit');
			$this->db->join('ci_users','ci_users.id=ci_users_fund_debit.user_id','inner join');
			$this->db->order_by('ci_users_fund_debit.id','DESC');
			$query = $this->db->get();
			return $query->result_array();
		}

		public function update_order_status($data,$id)
	{
		$this->db->where('id', $id);
		$this->db->update('tbl_collaterals_enquiry', $data);
		return true;
	}

	public function get_order_info($id)
	{
		$this->db->select('*');
		$this->db->from('tbl_collaterals_enquiry');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row_array();
		
	}

	public function check_no_exists($ac)
	{
		$this->db->select('*');
		$this->db->from('ci_users');
		$this->db->where('account_no',$ac);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function update_stock($data,$id)
	{
		$this->db->where('id', $id);
		$this->db->update('tbl_office_collaterals', $data);
		return true;
	}

		public function get_all_pending_kyc($from_date,$to_date){
			$this->db->select('*');
			$this->db->where('kyc_status',0);
			if(!empty($from_date) && !empty($to_date))
			{
				$this->db->where('ci_users.created_at BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
	
			}
			$this->db->order_by('id','DESC');
			return $this->db->get('ci_users')->result_array();
		}

		public function get_all_query()
		{
			$this->db->select('*');
			$this->db->where('status',0);
			$this->db->order_by('id','DESC');
			return $this->db->get('ci_raise_query')->result_array();
		}

		public function check_client_agreement($id)
		{
			$this->db->select('*');
			$this->db->where('user_id',$id);
			return $this->db->get('ci_users_client_agreement')->row_array();
		}

		public function check_event_client_agreement($id)
		{
			$this->db->select('*');
			$this->db->where('user_id',$id);
			return $this->db->get('ci_users_event_client_agreement')->row_array();
		}


		public function get_auto_upgrade_sip()
		{
			$this->db->select('tbl_auto_upgrade_sip.*,ci_users.bank_holder_name,ci_users.account_no');
			$this->db->from('tbl_auto_upgrade_sip');
			$this->db->join('ci_users','ci_users.id=tbl_auto_upgrade_sip.user_id','inner join');
			return $this->db->get()->result_array();
		
		}
		

		public function get_user_detail_for_level($id)
		{
			$this->db->select('max(my_direct) as my_direct_count');
			$this->db->where('is_parent',$id);
			return $this->db->get('ci_users')->row_array();
		}

		public function get_user_detail($id) 
		{

			$query = $this->db->get_where('ci_users', array('id' => $id));
			return $result = $query->row_array();

		}

		public function get_user_by_account($account_no)
		{
			$query = $this->db->get_where('ci_users', array('account_no' => $account_no));
			return $result = $query->row_array();
		}

		public function get_active_alert()
		{
			$query = $this->db->get_where('ci_alerts', array('status' =>'Active'));
			return $result = $query->row_array();
		}

		public function get_my_levels($id)
		{
			$this->db->select("*");
			$this->db->from("ci_users");
			$this->db->where('is_parent',$id);
			$this->db->order_by('capital_aum','ASC');
			return $this->db->get()->result_array();
		}

		public function get_my_directs($id)
		{
			$this->db->select("*");
			$this->db->from("ci_users");
			$this->db->where('is_parent',$id);
			return $this->db->get()->result_array();
		
		}

		public function get_daily_capital_return($id)
		{
			$date  = date('Y-m-d');
			$this->db->select("ci_users_capitalaum_daily_interest.*,ci_users.username,ci_users.account_no");
			$this->db->from("ci_users_capitalaum_daily_interest");
			$this->db->join("ci_users","ci_users.id=ci_users_capitalaum_daily_interest.user_id");

			$this->db->where('user_id',$id);
			$this->db->where('date(from_date)',$date);
			return $this->db->get()->result_array();
		
		}

		public function get_capital_cash($id,$from_date,$to_date)
		{
			$this->db->select("ci_users_capitalaum_daily_interest.*,ci_users.username,ci_users.account_no");
			$this->db->from("ci_users_capitalaum_daily_interest");
			$this->db->join("ci_users","ci_users.id=ci_users_capitalaum_daily_interest.user_id");
			$this->db->where('user_id',$id);
			if(!empty($from_date) && !empty($to_date))
			{
				$this->db->where('ci_users_capitalaum_daily_interest. from_date BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
			
			}

			$this->db->order_by('ci_users_capitalaum_daily_interest.id','DESC');


			return $this->db->get()->result_array();
		}

		public function get_extra_aum_history($id,$from_date,$to_date)
		{
			$this->db->select("ci_extra_add_aum.*,ci_users.username,ci_users.account_no");
			$this->db->from("ci_extra_add_aum");
			$this->db->join("ci_users","ci_users.id=ci_extra_add_aum.user_id");
			$this->db->where('user_id',$id);
			if(!empty($from_date) && !empty($to_date))
			{
				$this->db->where('ci_extra_add_aum.from_date BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
			
			}

			$this->db->order_by('ci_extra_add_aum.id','DESC');


			return $this->db->get()->result_array();
		
		}

		public function get_daily_extra_capital_return($id)
		{
			$date  = date('Y-m-d');
			$this->db->select("ci_users_sip_daily_interest.*,ci_users.username,ci_users.account_no");
			$this->db->from("ci_users_sip_daily_interest");
			$this->db->join("ci_users","ci_users.id=ci_users_sip_daily_interest.user_id");

			$this->db->where('user_id',$id);
			$this->db->where('date(from_date)',$date);
			return $this->db->get()->result_array();
		
		}

		public function get_daily_extra_sip_return($id)
		{
			$date  = date('Y-m-d');
			$this->db->select("ci_extra_add_sip.*,ci_users.username,ci_users.account_no");
			$this->db->from("ci_extra_add_sip");
			$this->db->join("ci_users","ci_users.id=ci_extra_add_sip.user_id");

			$this->db->where('user_id',$id);
			$this->db->where('date(from_date)',$date);
			return $this->db->get()->result_array();
		
		}

		public function get_daily_sip_return($id)
		{
			$date  = date('Y-m-d');
			$this->db->select("ci_users_sip_daily_interest.*,ci_users.username,ci_users.account_no");
			$this->db->from("ci_users_sip_daily_interest");
			$this->db->join("ci_users","ci_users.id=ci_users_sip_daily_interest.user_id");

			$this->db->where('user_id',$id);
			$this->db->where('date(from_date)',$date);
			return $this->db->get()->result_array();
		
		}

		public function get_total_capital_aum($user_id,$from_date,$to_date)
		{
			$sql="SELECT sum(amount) as total,sum(withdraw_amount) as withdrawl  FROM `ci_funds` WHERE `description` like 'Capital AUM%' AND user_id='$user_id' AND approved='1'";
			$query = $this->db->query($sql);

			$result= $query->row_array();
			return $result;

		}

		public function get_total_sip_aum($user_id)
		{
			$sql="SELECT sum(amount) as total,sum(withdraw_amount) as withdrawl  FROM `ci_funds` WHERE `description` like 'SIP AMOUNT%' AND user_id='$user_id' AND approved='1'";
			$query = $this->db->query($sql);
			$result= $query->row_array();
			return $result;
		}
      
      	public function get_team_levels($id)
		{
			$this->db->select("*");
			$this->db->from("ci_users");
			$this->db->where('is_parent',$id);
			$this->db->order_by('capital_aum','ASC');

			return $this->db->get()->result_array();
		}

		public function get_my_levels_arr($id)
		{
			$usersStr = implode(',', $id); // returns 1,2,3,4,5

			$query ="select * from ci_users where id IN($usersStr) order by capital_aum ASC";
			$res = $this->db->query($query)->result_array();
			return $res;

			
		
		}

		public function get_my_levels_arr_each($arr)
		{
			$arr = implode("a",$arr); 

			$id= explode("a",$arr);

			$usersStr = implode(',', $id); // returns 1,2,3,4,5

			$query ="select * from ci_users where id IN($usersStr) order by capital_aum ASC";
			$res = $this->db->query($query)->result_array();
			return $res;

		}

		function get_admin_data($role)
		{
			$this->db->select("*");
			$this->db->from("ci_admin");
			$this->db->where('admin_role_id',$role);
			return $this->db->get()->row_array();
		
		}

		function get_my_team($id)
		{
			$this->db->select("*");
			$this->db->from("ci_users");
			$this->db->where('is_parent',$id);
			$this->db->order_by('id','ASC');

			return $this->db->get()->result_array();
		}

		function get_team_level_details($id)
		{
			$this->db->select("*");
			$this->db->from("ci_users");
			$this->db->where('is_parent',$id);

			return $this->db->get()->row_array();
	
		}

	public function get_my_levels_by_level($id,$level)
	{
		
		$this->db->select("*");
		$this->db->from("ci_users");
		$this->db->where('is_parent',$id);
		//$this->db->where('capital_aum!=','0');
		$this->db->where('close_account_status','Open');

		$this->db->where('sort_order!=','1');

		$this->db->order_by('sort_order','ASC');
		if($level=='1')
		{
			$this->db->limit('2','0');

		}else if($level=='3')
		{
			$this->db->limit('2','2');
		}else if($level=='4')
		{
			$this->db->limit('4','4');

		}else if($level=='5')
		{
			$this->db->limit('4','8');

		}else if($level=='6')
		{
			$this->db->limit('4','12');

		}else if($level=='7')
		{
			$this->db->limit('4','16');

		}else if($level=='8')
		{
			$this->db->limit('4','20');

		}else if($level=='9')
		{
			$this->db->limit('4','24');

		}else if($level=='10')
		{
			$this->db->limit('4','28');

		}
		//echo $this->db->last_query();die();
		return $this->db->get()->result_array();
	}



	public function get_my_levels_details($id,$level)
	{
		$this->db->select("*");
		$this->db->from("ci_users");
		$this->db->where('is_parent',$id);
		$this->db->where('sort_order',$level);
	    $this->db->where('close_account_status','Open');
		$this->db->order_by('capital_aum','ASC');

		return $this->db->get()->row_array();
	}

	public function getcountofusers($id)
	{
		$this->db->select("count(*) as user_count");
		$this->db->from("ci_users");
		$this->db->where('is_parent',$id);
		return $this->db->get()->row_array();
	}

	public function set_fund($id)
	{
		$this->db->select("*");
		$this->db->from("ci_users");
		$this->db->where('id',$id);
		$res = $this->db->get()->row_array();
		if(!empty($res))
		{
			//print_r($res);die();
			//$sip_balance = $res['sip_balance'] + $res['my_fund'];
			$data = array('set_fund'=>'sip');
			$this->db->where('id', $id);
			$this->db->update('ci_users', $data);

			//$data1 = array('my_fund'=>'0');
			//$this->db->where('id', $id);
			//$this->db->update('ci_users', $data1);
			return true;

		}
	}

	public function set_capital_auto($id,$sip)
	{
		$this->db->select("*");
		$this->db->from("ci_users");
		$this->db->where('id',$id);
		$res = $this->db->get()->row_array();
		if(!empty($res))
		{
			//$cap = $res['capital_aum'] + $sip;
			//$capital_aum = $cap + $res['my_fund'];
			$data = array('set_fund'=>'capital_aum');
			$this->db->where('id', $id);
			$this->db->update('ci_users', $data);
			//$data1 = array('my_fund'=>'0');
			//$this->db->where('id', $id);
			//$this->db->update('ci_users', $data1);
			return true;

		}
	}

	public function insert_va($data)
    {
        $this->db->insert_batch('ci_users',$data);
        return $this->db->insert_id();
    }


    public function get_userid($ac_no)
   {
      $this->db->select('id');
      $this->db->from('ci_users');
       $this->db->where('account_no',$ac_no);

      $query = $this->db->get();
      $result =  $query->row_array();
      //echo $result['id'];die();
      if(!empty($result))
      {
         return $result['id'];

      }else
      {
         return 0;
      }
   }

   function save_excel($data)
   {
   	$this->db->insert_batch('emp_data',$data);
   }

   function get_excel_data_search($emp_code ,$title,$department ,$country ,$City,$from_hiring_date,$to_hiring_date,$gender)
   {
   	
	   	$this->db->select('*');
	   	$this->db->from('emp_data');
	   	$this->db->where('emp_id',$emp_code);
	   	$this->db->where('job_title',$title);	
	   	$this->db->where('department',$department);	
	   	$this->db->where('country',$country);	
	   	$this->db->where('city',$City);	
	   	$this->db->where('gender',$gender);	
	   	$this->db->where('hire_date BETWEEN "'.$from_hiring_date.'" AND "'.$to_hiring_date.'"');
	   	$this->db->order_by('hire_date','ASC');
	   	$query = $this->db->get();
	    $result  = $query->result_array();
	    //echo $this->db->last_query();die();
	  	 return $result;

   }
 function get_excel_data()
 {
 		$this->db->select('*');
	   	$this->db->from('emp_data');
	    $this->db->where('emp_data');
	   	$query = $this->db->get();
	    $result  = $query->result_array();
	   return $result;
 }



	function set_auto_upgrade()
	{
		$this->db->select("*");
		$this->db->from("ci_users");
		$res =  $this->db->get()->result_array();
		foreach($res as $row)
		{
			if($row['set_fund']=='sip')
			{

			$sip_balance = $row['sip_balance'] + $row['my_fund'];
			$data = array('sip_balance'=>$sip_balance,'set_fund'=>'sip');
			$this->db->where('id', $row['id']);
			$this->db->update('ci_users', $data);

			$data1 = array('my_fund'=>'0');
			$this->db->where('id', $row['id']);
			$this->db->update('ci_users', $data1);
			
			}else if($row['set_fund']=='capital_aum')
			{
				$sip_balance = $row['capital_aum'] + $row['my_fund'];
				$data = array('capital_aum'=>$sip_balance,'set_fund'=>'capital_aum');
				$this->db->where('id', $row['id']);
				$this->db->update('ci_users', $data);

				$data1 = array('my_fund'=>'0');
				$this->db->where('id', $row['id']);
				$this->db->update('ci_users', $data1);
			}else
			{
				$sip_balance = $row['capital_aum'] + $row['my_fund'];
				$data = array('capital_aum'=>$sip_balance,'set_fund'=>'capital_aum');
				$this->db->where('id', $row['id']);
				$this->db->update('ci_users', $data);

				$data1 = array('my_fund'=>'0');
				$this->db->where('id', $row['id']);
				$this->db->update('ci_users', $data1);
			}

		}

		return true;
	}


	public function birthday_wish()
	{
			$this->db->select("*");
			$this->db->from("ci_users");
			return $this->db->get()->result_array();
	}

	public function get_all_user_detail($id)
	{
		$query = $this->db->get_where('ci_users', array('is_parent' => $id));
		return $result = $query->result_array();
	}

	public function get_all_active($id)
	{
			$this->db->select('*');
			$this->db->from('ci_users');
		    $this->db->where('capital_aum!=',0);
		    $this->db->where('sip_balance!=',0);
		    $this->db->where('is_parent',$id);

			$query = $this->db->get();
			return $query->result_array();
	
	}

	public function get_admin_by_id($id)
	{
		$query = $this->db->get_where('ci_admin', array('admin_id' => $id));
		return $result = $query->row_array();
	}

	public function check_user_max()
	{		
			$this->db->select('max(id) as id');
			$this->db->from('ci_users');
			$query = $this->db->get();
			return $query->row_array();
	}

	public function check_self_Capital_verify($user_id)
	{
			$this->db->select('*');
			$this->db->from('ci_funds');
			$this->db->where('user_id',$user_id);
			$this->db->where('description','Self Capital');
			$this->db->where('approved',1);
			$query = $this->db->get();
			return $query->row_array();
	}

	public function check_sip_verify($user_id)
	{
			$this->db->select('*');
			$this->db->from('ci_funds');
			$this->db->where('user_id',$user_id);
			$this->db->where('description','SIP Amount');
			$this->db->where('approved',1);
			$query = $this->db->get();
			return $query->row_array();
	}

	public function gettodaymembercount($user_id)
	{
			$date  = date('Y-m-d');
			$this->db->select('count(*) as todays_count');
			$this->db->from('ci_users');
			$this->db->where('is_parent',$user_id);
			$this->db->where('date(created_at)',$date);
			$query = $this->db->get();
			return $query->row_array();
	}

	public function get_search_result($search_string)
	{
			$this->db->select('*');
			$this->db->from('ci_users');
			$this->db->where('account_no',$search_string);
			$this->db->where('is_parent',$this->session->userdata('admin_id'));
			$query  = $this->db->get();
			$result = $query->result_array();
			if(!empty($result))
			{
				return $result;
			}
			else if(empty($result))
			{
				$this->db->select('*');
				$this->db->from('ci_users');
				$this->db->where('username',$search_string);
				$this->db->where('is_parent',$this->session->userdata('admin_id'));

				$query  = $this->db->get();
				$result1 = $query->result_array();
				if(!empty($result1))
				{
					return $result1;
				}
			}else if(empty($result1))
			{
				$this->db->select('*');
				$this->db->from('ci_users');
				$this->db->where('mobile_no',$search_string);
				$this->db->where('is_parent',$this->session->userdata('admin_id'));

				$query  = $this->db->get();
				$result2 = $query->result_array();
				if(!empty($result2))
				{
					return $result2;
				}
			}else if(empty($result2))
			{
				$this->db->select('*');
				$this->db->from('ci_users');
				$this->db->where('type_of_partner',$search_string);
			    $this->db->where('is_parent',$this->session->userdata('admin_id'));

				$query  = $this->db->get();
				$result3 = $query->result_array();
				if(!empty($result3))
				{
					return $result3;
				}

			}else if(empty($result3))
			{
				$this->db->select('*');
				$this->db->from('ci_users');
				$this->db->where('firstname',$search_string);
			    $this->db->where('is_parent',$this->session->userdata('admin_id'));

				$query  = $this->db->get();
				$result4 = $query->result_array();
				if(!empty($result4))
				{
					return $result4;
				}

			}else if(empty($result4))
			{
				$this->db->select('*');
				$this->db->from('ci_users');
				$this->db->where('lastname	',$search_string);
			    $this->db->where('is_parent',$this->session->userdata('admin_id'));

				$query  = $this->db->get();
				$result5 = $query->result_array();
				if(!empty($result5))
				{
					return $result5;
				}

			}else if(empty($result5))
			{
				$this->db->select('*');
				$this->db->from('ci_users');
				$this->db->where('email',$search_string);
			    $this->db->where('is_parent',$this->session->userdata('admin_id'));

				$query  = $this->db->get();
				$result6 = $query->result_array();
				if(!empty($result6))
				{
					return $result6;
				}

			}
	}

	public function get_all_payments($from_date,$to_date)
	{
		if(empty($from_date) && empty($to_date))
		{
			if($this->session->userdata('admin_role_id')==5)
			{
				$user_id  = $this->session->userdata('admin_id');
				// $this->db->select('*');
				// $this->db->from('ci_funds');
				// $this->db->where('user_id',$user_id);
				// $this->db->where('description!=','Fund Request');
				// $this->db->order_by('id','DESC');
				// $query = $this->db->get();
				// return $query->result_array();
				$with = "Fund Request".'%';
				$query ="select ci_funds.*,ci_users.account_no from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where ci_funds.user_id='$user_id' AND ci_funds.description not  like '$with' order by ci_funds.id desc";	
				$result =$this->db->query($query)->result_array();
				return $result;

			}else
			{

				$with = "Fund Request".'%';
				$query ="select ci_funds.*,ci_users.account_no from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where ci_funds.description not  like '$with' order by ci_funds.id desc";	
				$result =$this->db->query($query)->result_array();
				//echo $this->db->last_query();die();

				return $result;
				/*$this->db->select('ci_funds.*,ci_users.account_no');
				$this->db->from('ci_funds');
				$this->db->join('ci_users','ci_users.id=ci_funds.user_id','inner join');
				$this->db->like('ci_funds.description!=','Fund Request%');
				$this->db->order_by('ci_funds.id','DESC');
				$query = $this->db->get();
				echo $this->db->last_query();die();
				return $query->result_array*/			}
		}else
		{
			if($this->session->userdata('admin_role_id')==5)
			{
				$user_id  = $this->session->userdata('admin_id');
				$with = "Fund Request".'%';
				$query ="select ci_funds.*,ci_users.account_no from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where ci_funds.user_id='$user_id' AND (date(ci_funds.created_at) BETWEEN '$from_date' AND '$to_date') AND ci_funds.description not  like '$with' order by ci_funds.id desc";	

			}else
			{
				$with = "Fund Request".'%';
				$query ="select ci_funds.*,ci_users.account_no from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where (date(ci_funds.created_at) BETWEEN '$from_date' AND '$to_date') AND ci_funds.description not  like '$with' order by ci_funds.id desc";	
			}
				
				$result =$this->db->query($query)->result_array();
				return $result;
		}
		
		
	}

	public function get_cap_history($id,$from_date,$to_date)
	{
		$with = "Capital AUM".'%';
		if(!empty($from_date) && !empty($to_date))
		{
				$query ="select ci_funds.*,ci_users.account_no from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where ci_funds.user_id='$id' AND  ci_funds.approved='1' AND (ci_funds.created_at BETWEEN '$from_date' AND '$to_date') AND ci_funds.description  like '$with'  order by ci_funds.id desc";	
	
		}else
		{
			$query ="select ci_funds.*,ci_users.account_no from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where ci_funds.user_id='$id' AND  ci_funds.approved='1' AND ci_funds.description  like '$with'  order by ci_funds.id desc";	
	
		}
			$result =$this->db->query($query)->result_array();
	     return $result;
			
	}

	public function get_sip_history($id,$from_date,$to_date)
	{
		$with = "SIP AMOUNT".'%';
		if(!empty($from_date) && !empty($to_date))
		{
			$query ="select ci_funds.*,ci_users.account_no from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where ci_funds.user_id='$id' AND  ci_funds.approved='1' AND (ci_funds.created_at BETWEEN '$from_date' AND '$to_date') AND ci_funds.description  like '$with'  order by ci_funds.id desc";	
		
		}else
		{
			$query ="select ci_funds.*,ci_users.account_no from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where ci_funds.user_id='$id' AND  ci_funds.approved='1' AND ci_funds.description  like '$with'  order by ci_funds.id desc";	
		
		}
		
		$result =$this->db->query($query)->result_array();
		//echo $this->db->last_query();die();
	     return $result;
		
	}

	public function get_capital_return_history($id,$from_date,$to_date)
	{
		if(!empty($from_date) && !empty($to_date))
		{
				$query ="select ci_users_capitalaum_daily_interest.*,ci_users.account_no,ci_users.username from ci_users_capitalaum_daily_interest JOIN ci_users ON 	ci_users.id=ci_users_capitalaum_daily_interest.user_id where ci_users_capitalaum_daily_interest.user_id='$id'  AND (ci_users_capitalaum_daily_interest.created_at BETWEEN '$from_date' AND '$to_date')   order by ci_users_capitalaum_daily_interest.id desc";	
          $result =$this->db->query($query)->result_array();
	     return $result;
		
		}else
		{
			//$query ="select ci_users_capitalaum_daily_interest.*,ci_users.account_no from ci_users_capitalaum_daily_interest JOIN ci_users ON ci_users.id=ci_users_capitalaum_daily_interest.user_id where ci_users_capitalaum_daily_interest.user_id='$id'  order by ci_users_capitalaum_daily_interest.id desc";	
             $query = "SELECT ci_users_capitalaum_daily_interest.*,ci_users.account_no,ci_users.username from ci_users_capitalaum_daily_interest JOIN ci_users ON 	ci_users.id=ci_users_capitalaum_daily_interest.user_id where ci_users_capitalaum_daily_interest.user_id='$id' AND ci_users_capitalaum_daily_interest.withdrawl IS NULL  AND ci_users_capitalaum_daily_interest.from_date BETWEEN DATE_SUB(NOW(), INTERVAL 32 DAY) AND NOW() order by ci_users_capitalaum_daily_interest.id desc";
			$result =$this->db->query($query)->result_array();
	     	if(!empty($result))
	     	{
	     		return $result;
	     	}else
	     	{
	     		$query = "SELECT  ci_users_capitalaum_daily_interest.*,ci_users.account_no,ci_users.username from ci_users_capitalaum_daily_interest JOIN ci_users ON ci_users.id=ci_users_capitalaum_daily_interest.user_id where ci_users_capitalaum_daily_interest.user_id='$id' AND ci_users_capitalaum_daily_interest.withdrawl IS NULL order by ci_users_capitalaum_daily_interest.id desc";
	     		$result =$this->db->query($query)->result_array();
	     		return $result;
	     	}
		
		}
		
	}
      
      public function get_extra_return_history1($id,$from_date,$to_date)
        {
        
         	$query = "SELECT ci_extra_add_aum.*,ci_users.username,ci_users.account_no from ci_extra_add_aum JOIN ci_users ON ci_users.id=ci_extra_add_aum.user_id where ci_extra_add_aum.user_id='$id' AND ci_extra_add_aum.new_interest IS NOT NULL order by ci_extra_add_aum.id desc";
			$result =$this->db->query($query)->result_array();
        	if(empty($result))
              {
             	 $query = "SELECT ci_extra_add_aum.*,ci_users.username,ci_users.account_no from ci_extra_add_aum JOIN ci_users ON ci_users.id=ci_extra_add_aum.user_id  where ci_extra_add_aum.user_id='$id' AND ci_extra_add_aum.new_interest IS NOT NULL order by ci_extra_add_aum.id desc limit 0";
				$result =$this->db->query($query)->result_array();
            }
        	return $result;
      }
      
      public function get_extra_sip_history1($id,$from_date,$to_date)
      {
        
         	$query = "SELECT ci_extra_add_sip.*,ci_users.username,ci_users.account_no from ci_extra_add_sip JOIN ci_users ON ci_users.id=ci_extra_add_sip.user_id where ci_extra_add_sip.user_id='$id' AND ci_extra_add_sip.new_interest IS NULL order by ci_extra_add_sip.id desc limit 1,30";
			$result =$this->db->query($query)->result_array();
        	if(empty($result))
              {
             	 $query = "SELECT ci_extra_add_sip.*,ci_users.username,ci_users.account_no from ci_extra_add_sip JOIN ci_users ON ci_users.id=ci_extra_add_sip.user_id  where ci_extra_add_sip.user_id='$id' AND ci_extra_add_sip.new_interest IS NULL order by ci_extra_add_sip.id desc limit 0";
				$result =$this->db->query($query)->result_array();
            }
        	return $result;
      }
      
      public function get_capital_return_history1($id,$from_date,$to_date)
	{
		if(!empty($from_date) && !empty($to_date))
		{
				$query ="select ci_users_capitalaum_daily_interest.*,ci_users.account_no from ci_users_capitalaum_daily_interest JOIN ci_users ON 	ci_users.id=ci_users_capitalaum_daily_interest.user_id where ci_users_capitalaum_daily_interest.user_id='$id' AND (ci_users_capitalaum_daily_interest.withdrawl IS NULL OR ci_users_capitalaum_daily_interest.withdrawl='0') AND (ci_users_capitalaum_daily_interest.created_at BETWEEN '$from_date' AND '$to_date')   order by ci_users_capitalaum_daily_interest.id desc";	
          		$result =$this->db->query($query)->result_array();
	     		return $result;
		
		}else
		{
			   $query = "SELECT ci_users_capitalaum_daily_interest.*,ci_users.account_no from ci_users_capitalaum_daily_interest JOIN ci_users ON ci_users.id=ci_users_capitalaum_daily_interest.user_id where ci_users_capitalaum_daily_interest.user_id='$id' AND (ci_users_capitalaum_daily_interest.withdrawl IS NULL OR ci_users_capitalaum_daily_interest.withdrawl='0') AND ci_users_capitalaum_daily_interest.from_date BETWEEN DATE_SUB(NOW(), INTERVAL 32 DAY) AND NOW() order by ci_users_capitalaum_daily_interest.id desc limit 0,30";
			$result =$this->db->query($query)->result_array();
	     	if(!empty($result))
	     	{
	     		return $result;
	     	}else
	     	{
	     		$query = "SELECT  ci_users_capitalaum_daily_interest.*,ci_users.account_no from ci_users_capitalaum_daily_interest JOIN ci_users ON ci_users.id=ci_users_capitalaum_daily_interest.user_id where ci_users_capitalaum_daily_interest.user_id='$id' AND(ci_users_capitalaum_daily_interest.withdrawl IS NULL OR ci_users_capitalaum_daily_interest.withdrawl='0') order by ci_users_capitalaum_daily_interest.id desc";
	     		$result =$this->db->query($query)->result_array();
	     		return $result;
	     	}
			
		}
		
		
		
	}


	public function get_sip_return_history1($id,$from_date,$to_date)
	{
		if(!empty($from_date) && !empty($to_date))
		{
			$query ="select ci_users_sip_daily_interest.*,ci_users.account_no from ci_users_sip_daily_interest JOIN ci_users ON ci_users.id=ci_users_sip_daily_interest.user_id where ci_users_sip_daily_interest.user_id='$id' AND (ci_users_sip_daily_interest.withdrawl IS NULL OR ci_users_sip_daily_interest.withdrawl='0')  AND (ci_users_sip_daily_interest.created_at BETWEEN '$from_date' AND '$to_date')  order by ci_users_sip_daily_interest.id desc";$result =$this->db->query($query)->result_array();
	     	    return $result;	
		
		}else
		{
			//$query ="select ci_users_sip_daily_interest.*,ci_users.account_no from ci_users_sip_daily_interest JOIN ci_users ON ci_users.id=ci_users_sip_daily_interest.user_id where ci_users_sip_daily_interest.user_id='$id' AND (ci_users_sip_daily_interest.withdrawl IS NULL OR ci_users_sip_daily_interest.withdrawl='0') AND ci_users_sip_daily_interest.from_date BETWEEN DATE_SUB(NOW(), INTERVAL 32 DAY) AND NOW()  order by ci_users_sip_daily_interest.id desc limit 0,30";

			$query ="select ci_users_sip_daily_interest.*,ci_users.account_no from ci_users_sip_daily_interest JOIN ci_users ON ci_users.id=ci_users_sip_daily_interest.user_id where ci_users_sip_daily_interest.user_id='$id' AND (ci_users_sip_daily_interest.withdrawl IS NULL OR ci_users_sip_daily_interest.withdrawl='0') AND ci_users_sip_daily_interest.from_date order by ci_users_sip_daily_interest.id desc";	
			 $result =$this->db->query($query)->result_array();
			 if(!empty($result))
			 {
	    	 	return $result;

			 }else
			 {
			 	$query ="select ci_users_sip_daily_interest.*,ci_users.account_no from ci_users_sip_daily_interest JOIN ci_users ON ci_users.id=ci_users_sip_daily_interest.user_id where ci_users_sip_daily_interest.user_id='$id' AND (ci_users_sip_daily_interest.withdrawl IS NULL OR ci_users_sip_daily_interest.withdrawl='0')  order by ci_users_sip_daily_interest.id desc";
			 	   $result =$this->db->query($query)->result_array();
			 	   //echo $this->db->last_query();die();
	     	    	return $result;	
		
			 }
		
		}
		
		
		
	}

	public function get_sip_cash_history($id,$from_date,$to_date)
	{
		if(!empty($from_date) && !empty($to_date))
		{
			$query ="select ci_users_sip_daily_interest.*,ci_users.account_no from ci_users_sip_daily_interest JOIN ci_users ON ci_users.id=ci_users_sip_daily_interest.user_id where ci_users_sip_daily_interest.user_id='$id'  AND (ci_users_sip_daily_interest.created_at BETWEEN '$from_date' AND '$to_date')  order by ci_users_sip_daily_interest.id desc";
		}else
		{
			$query ="select ci_users_sip_daily_interest.*,ci_users.account_no from ci_users_sip_daily_interest JOIN ci_users ON ci_users.id=ci_users_sip_daily_interest.user_id where ci_users_sip_daily_interest.user_id='$id'  order by ci_users_sip_daily_interest.id desc";
		}

		$result = $this->db->query($query)->result_array();
	    return $result;	
		
	}

	public function get_extra_sip_cash_history($id,$from_date,$to_date)
	{
		if(!empty($from_date) && !empty($to_date))
		{
			$query ="select ci_extra_add_sip.*,ci_users.account_no from ci_extra_add_sip JOIN ci_users ON ci_users.id=ci_extra_add_sip.user_id where ci_extra_add_sip.user_id='$id'  AND (ci_extra_add_sip.created_at BETWEEN '$from_date' AND '$to_date')  order by ci_extra_add_sip.id desc";
		}else
		{
			$query ="select ci_extra_add_sip.*,ci_users.account_no from ci_extra_add_sip JOIN ci_users ON ci_users.id=ci_extra_add_sip.user_id where ci_extra_add_sip.user_id='$id'  order by ci_extra_add_sip.id desc";
		}

		$result = $this->db->query($query)->result_array();
	    return $result;	
		
	}



	public function get_sip_return_history($id,$from_date,$to_date)
	{
		if(!empty($from_date) && !empty($to_date))
		{
			$query ="select ci_users_sip_daily_interest.*,ci_users.account_no from ci_users_sip_daily_interest JOIN ci_users ON ci_users.id=ci_users_sip_daily_interest.user_id where ci_users_sip_daily_interest.user_id='$id'  AND (ci_users_sip_daily_interest.created_at BETWEEN '$from_date' AND '$to_date')  order by ci_users_sip_daily_interest.id desc";$result =$this->db->query($query)->result_array();
	     	return $result;	
		
		}else
		{
			$query ="select ci_users_sip_daily_interest.*,ci_users.account_no from ci_users_sip_daily_interest JOIN ci_users ON ci_users.id=ci_users_sip_daily_interest.user_id where ci_users_sip_daily_interest.user_id='$id' AND ci_users_sip_daily_interest.from_date BETWEEN DATE_SUB(NOW(), INTERVAL 32 DAY) AND NOW()  order by ci_users_sip_daily_interest.id desc limit 1,30";	
			 $result =$this->db->query($query)->result_array();
			 if(!empty($result))
			 {
	    	 	return $result;

			 }else
			 {
			 	$query ="select ci_users_sip_daily_interest.*,ci_users.account_no from ci_users_sip_daily_interest JOIN ci_users ON ci_users.id=ci_users_sip_daily_interest.user_id where ci_users_sip_daily_interest.user_id='$id'   order by ci_users_sip_daily_interest.id desc";
			 	   $result =$this->db->query($query)->result_array();
	     	    	return $result;	
		
			 }
		
		}
		
		
		
	}


	public function get_all_payments_today($from_date,$to_date,$today)
	{
		if(!empty($today))
		{

				$with = "Fund Request".'%';
				$query ="select * from ci_funds where description like '$with' AND date(created_at)='$today' order by id desc";	
				$result =$this->db->query($query)->result_array();
				return $result;

		}
	}

	public function get_team($parent_id)
	{
			$this->db->select('*');
			$this->db->from('ci_users');
			$this->db->where('is_parent',$parent_id);
			$this->db->order_by('sort_order','ASC');
			$query = $this->db->get();
			return $query->result_array();
	}

	public function get_team_count($parent_id)
	{
			$this->db->select('count(*) as level2count');
			$this->db->from('ci_users');
			$this->db->where('is_parent',$parent_id);
			$this->db->order_by('sort_order','ASC');
			$query = $this->db->get();
			return $query->row_array();
	}

	public function get_requests($from_date,$to_date)
	{	
		if(empty($from_date) && empty($to_date))
		{
				if($this->session->userdata('admin_role_id')==5)
			{

				/*$with = "Fund Request".'%';
				$query ="select * from ci_funds where user delete_status!='Inactive' AND description like '$with'";	
				$result =$this->db->query($query)->result_array();
				return $result;*/
				$user_id  = $this->session->userdata('admin_id');

				$with = "Fund Request".'%';
				$query ="select ci_funds.*,ci_users.account_no,ci_users.username from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id  where ci_funds.user_id='$user_id'  AND ci_funds.delete_status!='Inactive' AND ci_funds.description like '$with' order by ci_funds.id desc";	
				$result =$this->db->query($query)->result_array();
				return $result;
				
				/*$this->db->select('*');
				$this->db->from('ci_funds');
				$this->db->where('user_id',$user_id);
				$this->db->like('description','Fund Request'.'%');

				$this->db->order_by('id','DESC');
				$query = $this->db->get();
				return $query->result_array();
*/			}else
			{
				$with = "Fund Request".'%';
				$query ="select ci_funds.*,ci_users.account_no,ci_users.username from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where ci_funds.delete_status!='Inactive' AND ci_funds.description like '$with' order by ci_funds.id desc";	
				$result =$this->db->query($query)->result_array();
				return $result;
				/*$this->db->select('*');
				$this->db->from('ci_funds');
				$this->db->where('delete_status!=','Inactive');

				$this->db->like('description','Fund Request%');

				$this->db->order_by('id','DESC');
				$query = $this->db->get();
				echo $this->db->last_query();die();
				return $query->result_array();*/
			}
		}else
		{
			if($this->session->userdata('admin_role_id')==5)
			{
				$user_id  = $this->session->userdata('admin_id');
				/*$this->db->select('*');
				$this->db->from('ci_funds');
				$this->db->where('user_id',$user_id);
				$this->db->where('description','Fund Request');
				$this->db->where('date(created_at) BETWEEN "'.$from_date.'" AND "'.$to_date.'"');

				$this->db->order_by('id','DESC');
				$query = $this->db->get();
				return $query->result_array();*/

				$user_id  = $this->session->userdata('admin_id');

				$with = "Fund Request".'%';
				$query ="select ci_funds.*,ci_users.account_no,ci_users.username from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where ci_funds.user_id='$user_id'  AND ci_funds.delete_status!='Inactive' AND ci_funds.description like '$with' AND date(ci_funds.created_at) BETWEEN '".$from_date."' AND '".$to_date."'  order by ci_funds.id desc";	
				$result =$this->db->query($query)->result_array();
				return $result;
				

			}else
			{
				
				if($this->session->userdata('admin_role_id')==5)
				{
					
				}	
				$user_id  = $this->session->userdata('admin_id');

				$with = "Fund Request".'%';
				$query ="select ci_funds.*,ci_users.account_no,ci_users.username from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where  ci_funds.delete_status!='Inactive' AND ci_funds.description like '$with' AND date(ci_funds.created_at) BETWEEN '".$from_date."' AND '".$to_date."'  order by ci_funds.id desc";	
				$result =$this->db->query($query)->result_array();

				return $result;
				
			}
		}


	}

	public function get_pending_requests($from_date,$to_date)
	{	
		if(empty($from_date) && empty($to_date))
		{
				
			
				$with = "Fund Request".'%';
				$query ="select ci_funds.*,ci_users.account_no,ci_users.username from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where ci_funds.approved='0' AND ci_funds.delete_status!='Inactive' AND ci_funds.description like '$with' order by ci_funds.id desc";	
				$result =$this->db->query($query)->result_array();
				return $result;
			
		
		}else
		{
			
				$with = "Fund Request".'%';
				$query ="select ci_funds.*,ci_users.account_no,ci_users.username from ci_funds JOIN ci_users ON ci_users.id=ci_funds.user_id where ci_funds.approved!='1'  AND ci_funds.delete_status!='Inactive' AND ci_funds.description like '$with' AND date(ci_funds.created_at) BETWEEN '".$from_date."' AND '".$to_date."'  order by ci_funds.id desc";	
				$result =$this->db->query($query)->result_array();
				return $result;
				
			}
	}

	public function check_all_bank_list($data)
	{
		$str = array();$capital_with = array();

		
		foreach($data as $row)
		{
			$this->db->select('ci_users_collateral.*,ci_users.username');
			$this->db->from('ci_users_collateral');
			$this->db->join('ci_users','ci_users.id=ci_users_collateral.user_id');

			$this->db->where('ci_users_collateral.id',$row);
			$query = $this->db->get();
			$res =  $query->result_array();

			 array_push($capital_with,$res);
			 
		}

			
			return $capital_with;
				
	}
		



	public function check_all_bank($data)
	{

		$sip_team = array();

		$res=array();$res1=array();$res2=array();$res3= array();
		foreach ($data as &$str) {
			if(strpos($str,'sip_team'))
			{
    		$str = str_replace('sip_team', '', $str);
    	    array_push($sip_team,$str);
    		}
		}

		
			if(!empty($sip_team))
			{
				$this->db->select('ci_users_sip_team_income_withdraw.*,ci_users.username');
				$this->db->from('ci_users_sip_team_income_withdraw');
				$this->db->join('ci_users','ci_users.id=ci_users_sip_team_income_withdraw.user_id');

				$this->db->where_in('ci_users_sip_team_income_withdraw.id',$sip_team);
				$query = $this->db->get();
				$res =  $query->result_array();
			}



		$capital_with = array();
	
		foreach ($data as &$str) {
			if(strpos($str,'capital_withdraw'))
			{
				 $str = str_replace('capital_withdraw', '', $str);
				 array_push($capital_with,$str);
			}
    		
    	   

		}

		print_r($capital_with);die();
		if(!empty($capital_with))
		{
			

			$this->db->select('ci_users_capital_withdraw.*,ci_users.username');
			$this->db->from('ci_users_capital_withdraw');
			$this->db->join('ci_users','ci_users.id=ci_users_capital_withdraw.user_id');
			$this->db->where_in('ci_users_capital_withdraw.id',$capital_with);
			$query = $this->db->get();
			//echo $this->db->last_query();die();
			$res1=  $query->result_array();
		}



		$capital_team = array();
		foreach ($data as &$str) {
			if(strpos($str,'capital_team'))
			{
	    		$str = str_replace('capital_team', '', $str);
	    	    array_push($capital_team,$str);
    		}
		}
		if(!empty($capital_team))
		{
			

			$this->db->select('ci_users_team_withdraw.*,ci_users.username');
			$this->db->from('ci_users_team_withdraw');
			$this->db->join('ci_users','ci_users.id=ci_users_team_withdraw.user_id');
			$this->db->where_in('ci_users_team_withdraw.id',$capital_team);
			$query = $this->db->get();
			//echo $this->db->last_query();die();
			$res2=  $query->result_array();
		}

		$sip_withdraw = array();
		foreach ($data as &$str) {
			if(strpos($str,'sip_withdraw'))
			{
    		$str = str_replace('sip_withdraw', '', $str);
    	    array_push($sip_withdraw,$str);
    		}

		}
		//print_r($sip_withdraw);die();
		if(!empty($sip_withdraw))
		{
			

			$this->db->select('ci_users_sip_withdraw.*,ci_users.username');
			$this->db->from('ci_users_sip_withdraw');
			$this->db->join('ci_users','ci_users.id=ci_users_sip_withdraw.user_id');
			$this->db->where_in('ci_users_sip_withdraw.id',$sip_withdraw);
			$query = $this->db->get();
			//echo $this->db->last_query();die();
			$res3=  $query->result_array();
		}

		$res4= array();
		$capital_interest = array();
		foreach ($data as &$str) {
			if(strpos($str,'capital_interest'))
			{
    		$str = str_replace('capital_interest', '', $str);
    	    array_push($capital_interest,$str);
    		}
		}
		if(!empty($capital_interest))
		{
			

			$this->db->select('ci_users_capitalaum_daily_interest.*,ci_users.username');
			$this->db->from('ci_users_capitalaum_daily_interest');
			$this->db->join('ci_users','ci_users.id=ci_users_capitalaum_daily_interest.user_id');
			$this->db->where_in('ci_users_capitalaum_daily_interest.id',$capital_interest);
			$query = $this->db->get();
			//echo $this->db->last_query();die();
			$res4=  $query->result_array();
		}


		$res5= array();
		$sip_interest = array();
		foreach ($data as &$str) {
			if(strpos($str,'sip_interest'))
			{
    		$str = str_replace('sip_interest', '', $str);
    	    array_push($sip_interest,$str);
    		}
		}
		if(!empty($sip_interest))
		{
			

			$this->db->select('ci_users_sip_daily_interest.*,ci_users.username');
			$this->db->from('ci_users_sip_daily_interest');
			$this->db->join('ci_users','ci_users.id=ci_users_sip_daily_interest.user_id');
			$this->db->where_in('ci_users_sip_daily_interest.id',$sip_interest);
			$query = $this->db->get();
			//echo $this->db->last_query();die();
			$res5=  $query->result_array();
		}


		$n = array_merge($res,$res1,$res2,$res3,$res4,$res5);
		 $new_array = array();
		 print_r($res); print_r($res1);print_r($res2); 
		 print_r($res3);print_r($res4);print_r($res5);

		  die();

 		//$new_array = $this->unique_key($n,'id');

 		//print_r($new_array);die();
    	// array( 5=>'duplicate', 6=>'three3' 7=>'three3' )
		return $n;
		
	}

	function unique_key($array,$keyname){

 		$new_array = array();
 		foreach($array as $key=>$value){

  		 if(!isset($new_array[$value[$keyname]])){
    	$new_array[$value[$keyname]] = $value;
  		 }

 		}
 		$new_array = array_values($new_array);
 		return $new_array;
	}

	public function get_prefix()
	{
		$this->db->select('account_no_prefix');
		$this->db->from('ci_general_settings');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function check_order($parent_id)
	{
		$this->db->select('max(sort_order) as sort_order');
		$this->db->from('ci_users');
		$this->db->where('is_parent', $parent_id);
		$query = $this->db->get();
		return $query->row_array();
		
	}

		public function change_pwd($data, $id){
		$this->db->where('id', $id);
		$this->db->update('ci_users', $data);
		return true;
	}

	function get_userbyreference($reference_id)
	{
		$query = $this->db->get_where('ci_users', array('account_no' => $reference_id));
		return $result = $query->row_array();
	}

	function get_parent_name($id)
	{
		$this->db->select('ci_users.*');
		$this->db->from('ci_users');		
		$this->db->where('ci_users.id', $id);
		$query = $this->db->get();
		$result =  $query->row_array();
		if($result)
		{ 
			$parent = $result['is_parent'];
			$this->db->select('ci_users.*');
			$this->db->from('ci_users');		
			$this->db->where('ci_users.id', $parent);
			$query1 = $this->db->get();
			$result1 =  $query1->row_array();
			return $result1;

		}
	}


		//---------------------------------------------------
		// Get user detial by ID
		public function get_user_by_id($id){
			
			$query = $this->db->get_where('ci_users', array('id' => $id));
			return $result = $query->row_array();
		}

		//---------------------------------------------------
		// Edit user Record
		public function edit_user($data, $id){
			$this->db->where('id', $id);
			$this->db->update('ci_users', $data);
			//echo $this->db->last_query();die();
			return true;
		}

		public function update_cap_team($data,$id)
		{
			
			$this->db->select('*');
		    $this->db->from('ci_users_team_withdraw');
		    $this->db->where('user_id',$id);
			$this->db->order_by('id','DESC');
			$this->db->limit('1');
			$query =$this->db->get();

			$result = $query->row_array();
			$this->db->where('id', $result['id']);
			$this->db->update('ci_users_team_withdraw', $data);
			return true;

		}

		public function update_sip_team($data,$id)
		{
			$this->db->select('*');
		    $this->db->from('ci_users_sip_team_income_withdraw');
			$this->db->order_by('id','DESC');
			$this->db->limit('1');
			$query =$this->db->get();

			$result = $query->row_array();
			$this->db->where('id', $result['id']);
			$this->db->update('ci_users_sip_team_income_withdraw', $data);
			return true;

		}

		function back_entry_list()
		{
			$this->db->select('*');
		    $this->db->from('ci_users');
			$this->db->where('back_entry','true');
			$query =$this->db->get();
			$result = $query->result_array();
			return $result;

		}

		function back_withdraw_list()
		{
			$this->db->select('ci_users_back_withdraw.*,ci_users.username,ci_users.account_no,ci_users.email,ci_users.account_no,ci_users.mobile_no');
		    $this->db->from('ci_users_back_withdraw');
		     $this->db->join('ci_users','ci_users.id=ci_users_back_withdraw.user_id');
			$query =$this->db->get();
			$result = $query->result_array();
			return $result;

		}

		public function update_capital_cashback($data,$id)
		{
			$this->db->select('*');
		    $this->db->from('ci_users_capitalaum_daily_interest');
			$this->db->where('ci_users_capitalaum_daily_interest.user_id',$id);
			$this->db->order_by('id','DESC');
			$this->db->limit('1');

			$query  = $this->db->get();
			$result = $query->row_array();

			$this->db->where('id', $result['id']);
			$this->db->update('ci_users_capitalaum_daily_interest', $data);
			return true;

		}
		
		public function update_sip_cashback($data,$id)
		{
			
			$this->db->select('*');
		    $this->db->from('ci_users_sip_daily_interest');
			$this->db->order_by('id','DESC');
			$this->db->limit('1');
			$query =$this->db->get();
			$result = $query->row_array();
			$this->db->where('id', $result['id']);
			$this->db->update('ci_users_sip_daily_interest', $data);
			return true;

		}
		

		//---------------------------------------------------
		// Change user status
		//-----------------------------------------------------
		function change_status()
		{	
			$data =  array('kyc_status'=>$this->input->post('status'),'updated_at'=>date('Y-m-d h:i:s'));
			//$this->db->set('is_active', $this->input->post('status'),'updated_at'=>date('Y-m-d h:i:s'));
			$this->db->where('id', $this->input->post('id'));
			$this->db->update('ci_users',$data);
		} 

		function get_user_data()
		{
			$query = $this->db->get_where('ci_users');
			$this->db->order_by('id','DESC');
			return $result = $query->result_array();
		}

		function get_all_inactive($user_id)
		{
			$this->db->select('*');
		    $this->db->from('ci_users');
		    $this->db->where('capital_aum',0);
		    $this->db->where('sip_balance',0);
		    $this->db->where('is_parent',$user_id);

			$this->db->order_by('id','DESC');
			$query =$this->db->get();
			$result = $query->result_array();
			return $result;

		}

		function save_team_income($data)
		{
			$this->db->insert('ci_users_team_withdraw', $data);
			return true;

		}

		function save_sip_team_income($data)
		{
			$this->db->insert('ci_users_sip_team_income_withdraw', $data);
			return true;

		}

	

	}




?>