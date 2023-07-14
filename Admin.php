<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller
{
    function __construct(){

        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();
		$this->load->model('admin/user_model', 'user_model');
		$this->load->model('admin/fund_model', 'fund_model');

		$this->load->model('admin/admin_model', 'admin');
		$this->load->model('admin/Activity_model', 'activity_model');
    }

	//-----------------------------------------------------		
	function index($type=''){
	$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
		$this->session->set_userdata('filter_type',$type);
		$this->session->set_userdata('filter_keyword','');
		$this->session->set_userdata('filter_status','');
		
		$data['admin_roles'] = $this->admin->get_admin_roles();
		
		$data['title'] = 'Admin List';

		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/admin/index', $data);
		$this->load->view('admin/includes/_footer');
	}

	//---------------------------------------------------------
	function filterdata(){

		$this->session->set_userdata('filter_type',$this->input->post('type'));
		$this->session->set_userdata('filter_status',$this->input->post('status'));
		$this->session->set_userdata('filter_keyword',$this->input->post('keyword'));
	}

	//--------------------------------------------------		
	function list_data(){

		$data['info'] = $this->admin->get_all();

		$this->load->view('admin/admin/list',$data);
	}

	//-----------------------------------------------------------
	function change_status(){

		$this->rbac->check_operation_access(); // check opration permission

		$this->admin->change_status();
	}
	
	//--------------------------------------------------
	function add(){

		$this->rbac->check_operation_access(); // check opration permission

		$data['admin_roles']=$this->admin->get_admin_roles();

		if($this->input->post('submit')){
				$this->form_validation->set_rules('username', 'Username', 'trim|alpha_numeric|is_unique[ci_admin.username]|required');
				$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
				$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
				//$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
				$this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
				$this->form_validation->set_rules('password', 'Password', 'trim|required');
				$this->form_validation->set_rules('role', 'Role', 'trim|required');
				if ($this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);
					$this->session->set_flashdata('errors', $data['errors']);
					redirect(base_url('admin/admin/add'),'refresh');
				}
				else{
					$data = array(
						'admin_role_id' => $this->input->post('role'),
						'username' => $this->input->post('username'),
						'firstname' => $this->input->post('firstname'),
						'lastname' => $this->input->post('lastname'),
						'email' => $this->input->post('email').$this->input->post('email_suffix'),
						'mobile_no' => $this->input->post('mobile_no'),
						'password' =>  $this->input->post('password'),
						'is_active' => 1,
						'created_at' => date('Y-m-d : h:m:s'),
						'updated_at' => date('Y-m-d : h:m:s'),
					);
					$data = $this->security->xss_clean($data);
					$result = $this->admin->add_admin($data);
					if($result){

						// Activity Log 
						$this->activity_model->add_log(4);

						$this->session->set_flashdata('success', 'Admin has been added successfully!');
						redirect(base_url('admin/admin'));
					}
				}
			}
			else
			{
				$this->load->view('admin/includes/_header', $data);
        		$this->load->view('admin/admin/add');
        		$this->load->view('admin/includes/_footer');
			}
	}

	//--------------------------------------------------
	function edit($id=""){

		$this->rbac->check_operation_access(); // check opration permission

		$data['admin_roles'] = $this->admin->get_admin_roles();

		if($this->input->post('submit')){
			$this->form_validation->set_rules('username', 'Username', 'trim|alpha_numeric|required');
			$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
			$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			$this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|min_length[5]');
			$this->form_validation->set_rules('role', 'Role', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('admin/admin/edit/'.$id),'refresh');
			}
			else{
				$data = array(
					'admin_role_id' => $this->input->post('role'),
					'username' => $this->input->post('username'),
					'firstname' => $this->input->post('firstname'),
					'lastname' => $this->input->post('lastname'),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'is_active' => 1,
					'updated_at' => date('Y-m-d : h:m:s'),
				);

				if($this->input->post('password') != '')
				$data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

				$data = $this->security->xss_clean($data);
				$result = $this->admin->edit_admin($data, $id);

				if($result){
					// Activity Log 
					$this->activity_model->add_log(5);

					$this->session->set_flashdata('success', 'Admin has been updated successfully!');
					redirect(base_url('admin/admin'));
				}
			}
		}
		elseif($id==""){
			redirect('admin/admin');
		}
		else{
			$data['admin'] = $this->admin->get_admin_by_id($id);
			
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/admin/edit', $data);
			$this->load->view('admin/includes/_footer');
		}		
	}

	//--------------------------------------------------
	function check_username($id=0){

		$this->db->from('admin');
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('admin_id !='.$id);
		$query=$this->db->get();
		if($query->num_rows() >0)
			echo 'false';
		else 
	    	echo 'true';
    }

    //------------------------------------------------------------
	function delete($id=''){
	   
		$this->rbac->check_operation_access(); // check opration permission

		$this->admin->delete($id);

		// Activity Log 
		$this->activity_model->add_log(6);

		$this->session->set_flashdata('success','User has been Deleted Successfully.');	
		redirect('admin/admin');
	}

	public function change_pwd(){

		$id = $this->session->userdata('admin_id');

		if($this->input->post('submit')){

			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('confirm_pwd', 'Confirm Password', 'trim|required|matches[password]');

			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('admin/profile/change_pwd'),'refresh');
			}
			else{

				$data = array(
					'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
				);
				$data = $this->security->xss_clean($data);
				
					$result = $this->admin->change_pwd($data, $id);

				

				if($result){
					if($this->session->userdata('admin_role_id')=='5')
					{
						if(!empty($this->input->post('password')))
						{
							$arrdata = array(
							'user_id'=>$id,
							'activity'=>'Password Changed',
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>$this->session->userdata('admin_id'),
							);

							$this->fund_model->add_log($arrdata);
						}
						$this->session->set_flashdata('success', 'Password has been changed successfully!');
						redirect(base_url('admin/users/change_pwd'));
					}else
					{
						$this->session->set_flashdata('success', 'Password has been changed successfully!');
						redirect(base_url('admin/profile/change_pwd'));
					}
					
				}
			}
		}
		else{
			
			$data['title'] = 'Change Password';
			//$data['user'] = $this->admin->get_user_detail();
			$data['user']	= $this->user_model->get_admin_by_id($this->session->userdata('admin_id'));
			$this->load->view('admin/includes/_header',$data);
			$this->load->view('admin/admin/change_pwd', $data);
			$this->load->view('admin/includes/_footer');
		}
	}

	function import_excel()
	{
			$data['title'] = 'Change Password';
			$data['user']	= $this->user_model->get_admin_by_id($this->session->userdata('admin_id'));
			$data['excel_data']	= $this->user_model->get_excel_data();

			$this->load->view('admin/includes/_header',$data);
			$this->load->view('admin/users/import_excel', $data);
			$this->load->view('admin/includes/_footer');
	}



	function import_excel_data()
	{

		//echo "hi";die();
		$path = 'uploads/';
			require_once APPPATH . "/third_party/PHPExcel.php";
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'xlsx|xls|csv';
			$config['remove_spaces'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);            
			if (!$this->upload->do_upload('excel')) {
			$error = array('error' => $this->upload->display_errors());
			} else {
			$data = array('upload_data' => $this->upload->data());
			if (!empty($data['upload_data']['file_name'])) {
			$import_xls_file = $data['upload_data']['file_name'];
			} else {
			$import_xls_file = 0;
			}
			$inputFileName = $path . $import_xls_file;
			//echo $inputFileName;die();
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$flag = true;
			$i=0;
//`emp_id`, `full_name`, `job_title`, `department`, `unit`, `gender`, `ethnicity`, `age`, `hire_date`, `annual_salary`, `bonus`, `country`, `city`, `exit_date`
			foreach($allDataInSheet as $row)
			{
				$data1[$i]['emp_id']= $row['A'];
				$data1[$i]['full_name']= $row['B'];
				$data1[$i]['job_title']= $row['C'];
				$data1[$i]['department']= $row['D'];
				$data1[$i]['unit']= $row['E'];
				$data1[$i]['gender']= $row['F'];
				$data1[$i]['ethnicity']= $row['G'];
				$data1[$i]['age']= $row['H'];
				$data1[$i]['hire_date']= $row['I'];
				$data1[$i]['annual_salary']= $row['J'];
				$data1[$i]['bonus']= $row['K'];
				$data1[$i]['country']= $row['L'];
				$data1[$i]['city']= $row['M'];
				$data1[$i]['exit_date']= $row['N'];
				$i++;

			}

			$this->user_model->save_excel($data1);
			$this->session->set_flashdata('success','Excel Imported successfully!!');
			redirect(base_url().'admin/admin/import_excel');

			
	}
}
	public function display()
	{

		
		$data = $this->input->get();

		if(!empty($data))
		{
		$emp_code  	= $data['emp_code'];
		$title  	= $data['jtitle'];
		$department  = $data['department'];
		$gender  = $data['gender'];
		
		$country     = $data['Country'];
		$City     = $data['City'];
		$from_hiring_date     = $data['from_hiring_date'];
		$to_hiring_date = $data['to_hiring_date'];
//print_r($data) ;die();

		$data['excel_data']	= $this->user_model->get_excel_data_search($emp_code ,$title,$department ,$country ,$City,$from_hiring_date,$to_hiring_date,$gender );
		if(!empty($data['excel_data']	))
		{
		echo json_encode($data['excel_data']);
		}else{
			$arr = array('msg'=>"Data Not Found!!");
		}
		
	}else{

		$arr = array('msg'=>"Please Pass Parameters");
		echo json_encode($arr);

	}
}








	
}

?>