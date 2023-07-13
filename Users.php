<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends MY_Controller {

	public function __construct(){

		parent::__construct();
		//auth_check(); // check login auth
		//$this->rbac->check_module_access();
		$this->load->model('admin/admin_model', 'admin');
		$this->load->model('admin/fund_model', 'fund_model');
		$this->load->model('admin/report_model', 'report_model');

		$this->load->model('admin/user_model', 'user_model');
		$this->load->model('admin/Activity_model', 'activity_model');
		$this->load->model('admin/Dashboard_model', 'dashboard_model');
      	    $this->load->model('admin/admin_roles_model', 'admin_roles');

		$this->load->helper(array('form', 'url','security'));

	}


	public function testemail()
	{
		send_email_user('gitanjaliyadav5@gmail.com','Test SMS','Check details');
	}

	public function send_sms_to_mob()
	{
		$mobile = urlencode('9552689263');
		$text = urlencode('Dear User,Thanks For Crediting Amount 1000.');


		send_sms_text($mobile,$text);
	}

	//-----------------------------------------------------------
	public function index(){
		$data['user_list'] =  $this->report_model->get_all_active_users_all(0,0);
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/users/user_list',$data);
		$this->load->view('admin/includes/_footer');
	}

	public function todays_birthday($value='')
	{
		$data['users'] =  $this->report_model->get_todays_birthday_users(0,0);

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/users/todays_birthday',$data);
		$this->load->view('admin/includes/_footer');
	}
  
  public function sendbirthsms()
	{
			$username  = $_POST['username'];
    	    $arr = $this->setting_model->get_sms_by_id(10);

		  		$sms = $arr['message'];
		  		$sms = str_replace('{#var#}',$_POST['username'],$sms);
		  		
           $sms = urlencode($sms);
    			//echo $sms;die();
					//send_sms(urlencode('91'.$_POST['mobile']),$sms);
    		  send_sms_text(urlencode($_POST['mobile']),$sms);
    		  //send_email_user(urlencode($_POST['mobile']),$sms);
       	 $arr = array('status'=>true);
       		echo json_encode($arr);
	}
  
  public function collaterals_enquiry($from_date,$to_date)
	{
		if(!empty($from_date) && !empty($to_date))
		{
			$data['enquiry'] =  $this->report_model->get_collaterals_enquiry($from_date,$to_date);
		}else
		{
			$data['enquiry'] =  $this->report_model->get_collaterals_enquiry(0,0);

		}

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/users/enquiry',$data);
		$this->load->view('admin/includes/_footer');
	
	}

	/* OFFICE COLLATERAL LIST ADD EDIT DELETE*/
	public function office_collaterals_list()
	{
		$data['office_collaterals'] = $this->setting_model->get_office_collaterals();
		$data['languages'] = $this->setting_model->get_all_languages();

		$data['title'] = 'Event List';

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/general_settings/office_collaterals_list', $data);
		$this->load->view('admin/includes/_footer');
	}


	public function visiting_card()
	{
		$data['holiday_list'] = $this->setting_model->get_holiday();
		$data['languages'] = $this->setting_model->get_all_languages();
		$data['setting'] = $this->setting_model->get_general_settings();
		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
		$data['title'] = 'Visiting Card';

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/general_settings/visiting_card', $data);
		$this->load->view('admin/includes/_footer');
	}
	

	public function all_users($from_date,$to_date)
	{
		if(!empty($from_date) && !empty($to_date))
		{
			$data['users'] =  $this->report_model->get_all_users_clients($from_date,$to_date);
			$data['from_date'] = $from_date;
			$data['to_date']   = $to_date;

					
		}else
		if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
		{

			$data['users'] =  $this->report_model->get_all_users_clients($this->input->post('from_date'),$this->input->post('to_date'));
			$data['from_date'] = $this->input->post('from_date');
			$data['to_date']   = $this->input->post('to_date');
		
					

		}else
		{
					$data['users'] =  $this->report_model->get_all_users_clients(0,0);

		}

		if(!empty($from_date) && !empty($to_date))
		{
			$data['admin'] =  $this->report_model->get_all_users_admin($from_date,$to_date);
			$data['from_date'] = $from_date;
			$data['to_date']   = $to_date;

					
		}
		else if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
		{
			$data['admin'] =  $this->report_model->get_all_users_admin($this->input->post('from_date'),$this->input->post('to_date'));
			$data['from_date'] = $this->input->post('from_date');
			$data['to_date']   = $this->input->post('to_date');
				
					

		}else
		{
		   $data['admin'] =  $this->report_model->get_all_users_admin(0,0);

		}

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/users/total_all_users',$data);
		$this->load->view('admin/includes/_footer');
	}


	public function download_all_users($from_date,$to_date)
	{
	   $settings 	=  $this->setting_model->get_general_settings();

	   if(!empty($from_date) && !empty($to_date))
		{
			$users =  $this->report_model->get_all_users_clients($from_date,$to_date);
			$data['from_date'] = $from_date;
		    $data['to_date'] = $to_date;
		

		}else
		if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
		{
			$users =  $this->report_model->get_all_users_clients($this->input->post('from_date'),$this->input->post('to_date'));
			$data['from_date'] = $this->input->post('from_date');
		    $data['to_date'] = $this->input->post('to_date');
		
					

		}else
		{
			$users =  $this->report_model->get_all_users_clients(0,0);

		}


		if(!empty($from_date) && !empty($to_date))
		{
			$data['admin'] =  $this->report_model->get_all_users_admin($from_date,$to_date);
		

		}else
			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
		{
					$data['admin'] =  $this->report_model->get_all_users_admin($this->input->post('from_date'),$this->input->post('to_date'));
					

		}else
		{
					$data['admin'] =  $this->report_model->get_all_users_admin(0,0);

		}

		$this->load->library('MYPDF');


	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', 'B', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print
		$txt="";
		$txt .= '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt .= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th style="text-align:center" width="5%">ID</th><th style="text-align:center" width="25%">Username</th>
			<th style="text-align:center">Account No</th><th style="text-align:center">Created Date</th>
			<th style="text-align:center">Active Date</th><th style="text-align:center">Mobile</th>
			<th style="text-align:center">Designation</th>
			</tr><tbody>';
			foreach($users as $row)
			{
				if(!empty($row['kyc_date']))
				{
					$kyc_date = $row['kyc_date'];
				}else{
					$kyc_date ="NA";
				}
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .$row['username'].'</td>
			  <td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .date('d-m-Y',strtotime($row['created_at'])).'</td>
			<td style="text-align:center">' .$kyc_date.'</td>

			<td style="text-align:center">' .$row['mobile_no'].'</td>
			<td style="text-align:center">' .'User'.'</td>

			</tr>';

			$id++;}

			$txt.='<tfoot><tr class="bo"><td colspan="7" class="bo">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="7" class="bo">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	$txt.='</body></html>';
	
			$pdf->writeHTML($txt, true, false, true, false, '');

		// print a block of text using Write()

		// ---------------------------------------------------------
		 ob_clean();
		//Close and output PDF document
		$pdf->Output('all_users.pdf', 'I');


	}



	public function reg_users($from_date,$to_date)
	{
		if(!empty($from_date) && !empty($to_date))
		{
			$data['users'] =  $this->dashboard_model->get_all_reg_users($this->input->post('from_date'),$this->input->post('to_date'));
			$data['from_date'] = $from_date;
			$data['to_date']   = $to_date;

			
		}
		if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
		{
			$data['users'] =  $this->dashboard_model->get_all_reg_users($this->input->post('from_date'),$this->input->post('to_date'));
			$data['from_date'] = $this->input->post('from_date');
			$data['to_date']   = $this->input->post('to_date');
					

		}else
		{
			$data['users'] =  $this->dashboard_model->get_all_reg_users(0,0);

		}

		


		

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/users/all_users',$data);
		$this->load->view('admin/includes/_footer');
	}


	public function download_registered_users($from_date,$to_date)
	{
		$settings =  $this->setting_model->get_general_settings();

		if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
		{
					$users =  $this->dashboard_model->get_all_reg_users($this->input->post('from_date'),$this->input->post('to_date'));
					

		}else
		{
					$users =  $this->dashboard_model->get_all_reg_users(0,0);

		}
		$this->load->library('MYPDF');


	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', 'B', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print
		$txt="";
		$txt .= '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt .= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th style="text-align:center" width="5%">ID</th><th style="text-align:center" width="25%">Username</th>
			<th style="text-align:center">Account No</th><th style="text-align:center">Created Date</th>
			<th style="text-align:center">Active Date</th><th style="text-align:center">Mobile</th>
			<th style="text-align:center">Designation</th>
			</tr><tbody>';
			foreach($users as $row)
			{

				if(!empty($row['kyc_date']))
				{
					$kyc_date = $row['kyc_date'];
				}else{
					$kyc_date ="NA";
				}
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .$row['username'].'</td>
			  <td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .date('d-m-Y',strtotime($row['created_at'])).'</td>
			<td style="text-align:center">' .$kyc_date.'</td>

			<td style="text-align:center">' .$row['mobile_no'].'</td>
			<td style="text-align:center">' .'User'.'</td>

			</tr>';

			$id++;}

			$txt.='<tfoot><tr class="bo"><td colspan="7" class="bo">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="7" class="bo">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	$txt.='</body></html>';
	
			$pdf->writeHTML($txt, true, false, true, false, '');

		// print a block of text using Write()

		// ---------------------------------------------------------
		 ob_clean();
		//Close and output PDF document
		$pdf->Output('reg_users.pdf', 'I');

	}

	public function active_users($from_date,$to_date)
	{

		if(!empty($from_date) && !empty($to_date))
		{
			$data['users'] =  $this->report_model->get_all_active_users($from_date,$to_date);
			$data['from_date'] = $from_date;
		    $data['to_date']   = $to_date;
	
		}else
		if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
		{
			$data['users'] =  $this->report_model->get_all_active_users($this->input->post('from_date'),$this->input->post('to_date'));
			$data['from_date'] = $this->input->post('from_date');
		    $data['to_date']   = $this->input->post('to_date');
					

		}else
		{
					$data['users'] =  $this->report_model->get_all_active_users(0,0);

		}

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/users/active_users',$data);
		$this->load->view('admin/includes/_footer');
	}

	public function set_tds()
	{
				$this->setting_model->set_tds();

	}

	public function download_active_users($from_date,$to_date)
	{
	   $settings 	=  $this->setting_model->get_general_settings();
	   if(!empty($from_date) && !empty($to_date))
		{
					$users =  $this->report_model->get_all_active_users($from_date,$to_date);
					

		}else
		if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
		{
					$users =  $this->report_model->get_all_active_users($this->input->post('from_date'),$this->input->post('to_date'));
					

		}else
		{
					$users =  $this->report_model->get_all_active_users(0,0);

		}

		$this->load->library('MYPDF');


		$this->load->library('MYPDF');


		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', 'B', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt .= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th style="text-align:center" width="5%">ID</th><th style="text-align:center" width="25%">Username</th>
			<th style="text-align:center">Account No</th><th style="text-align:center">Created Date</th>
			<th style="text-align:center">Active Date</th><th style="text-align:center">Mobile</th>
			<th style="text-align:center">Designation</th>
			</tr><tbody>';
			foreach($users as $row)
			{
				if(!empty($row['kyc_date']))
				{
					$kyc_date = $row['kyc_date'];
				}else{
					$kyc_date = "NA";
				}
			$txt.=	'<tr>
				<td style="text-align:center" >' .$id.'</td>
				<td style="text-align:center">' .$row['username'].'</td>
			  <td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .date('d-m-Y',strtotime($row['created_at'])).'</td>
			<td style="text-align:center">' .$kyc_date.'</td>

			<td style="text-align:center">' .$row['mobile_no'].'</td>
			<td style="text-align:center">' .'User'.'</td>

			</tr>';

			$id++;}
			$txt.='<tfoot><tr class="bo"><td colspan="7" class="bo">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="7" class="bo">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
		$txt.='	</tfoot></table>';
		$txt.='</body></html>';
	
			$pdf->writeHTML($txt, true, false, true, false, '');

		// print a block of text using Write()

		// ---------------------------------------------------------
		 ob_clean();
		//Close and output PDF document
		$pdf->Output('active_users.pdf', 'I');

	}

	public function inactive_users($value='')
	{
		$data['users'] =  $this->report_model->get_all_active_users();

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/users/inactive_users',$data);
		$this->load->view('admin/includes/_footer');
	}

	public function show_filter()
	{
		$data['user'] = $this->user_model->get_user_detail($this->input->post('user_id'));
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/users/show_filter_list',$data);
		$this->load->view('admin/includes/_footer');
	}

	public function admin_bulk_send()
	{
		$data['user_list'] =  $this->report_model->get_all_active_users(0,0);
		
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/users/admin_bulk_send',$data);
		$this->load->view('admin/includes/_footer');
	}

	public function id_card($id)
	{
		if($this->session->userdata('admin_role_id')=='5')
		{
			$data['user']    = $this->user_model->get_user_detail($id);
			$data['user_id'] =  $this->user_model->get_user_detail($id);

		}else
		{
			$data['user_id'] =  $this->user_model->get_user_detail($id);

		}
		$data['active_alert'] = $this->user_model->get_active_alert();

		$this->load->view('admin/includes/_header',$data);
		$this->load->view('admin/users/id_card',$data);
		$this->load->view('admin/includes/_footer');
	}

	public function send_sms_to_all()
	{

		  $sms = $this->input->post('msg');
          $sms = urlencode($sms);
			$user_list =  $this->report_model->get_all_active_users();

			foreach($user_list as $row)
			{
				$contact_mobile = '91'.$row['mobile_no'];
     	  		$contact_mobile =  urlencode($contact_mobile);
	     	  /* Common Helper Function To Send SMS */
	          //send_sms($contact_mobile,$sms);
	          /* END  Common Helper Function To Send SMS */
	          send_sms_text(urlencode($row['mobile_no']),urlencode($sms));
			}
         	 $this->session->set_flashdata('success', 'SMS Send To All Users!!');
			redirect(base_url('admin/users/admin_bulk_send'),'refresh');
       
	}


	public function check_sponsor()
	{
		$data['user_list'] =  $this->report_model->get_all_active_users_all(0,0);
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/users/check_sponsor',$data);
		$this->load->view('admin/includes/_footer');
	}


	 public function sendotp()
	{

		  $arr = $this->setting_model->get_sms_by_id(1);

		  $sms = $arr['message'];
		  $sms = str_replace('{#var}',$this->input->post('otp'),$sms);
          $sms = urlencode($sms);

          $contact_mobile = '91'.$this->input->post('mobile');
     	  $contact_mobile =  urlencode($contact_mobile);
     	  /* Common Helper Function To Send SMS */
          //send_sms($contact_mobile,$sms);
          /* END  Common Helper Function To Send SMS */
         send_sms_text($contact_mobile,$sms);
       	 $arr = array('status'=>true);
       		echo json_encode($arr);
	}

	public function update_mob()
	{
		$data = array('mobile_no'=>$_POST['new_mob']);
		$id   = $_POST['user_id'];
		$flag = $this->user_model->edit_user($data, $id);
		if($flag)
		{
			$arrdata = array(
							'user_id'=>$this->session->userdata('admin_id'),
							'activity'=>'Primary Mobile No Updated',
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>$this->session->userdata('admin_id'),
							);

							$this->fund_model->add_log($arrdata);
			$arr = array('status'=>true);
		}else
		{
			$arr = array('status'=>false);
		}
			
       		echo json_encode($arr);
	}

	public function update_sec_mob()
	{
		$data = array('sec_mobile_no'=>$_POST['new_mob']);
		$id   = $_POST['user_id'];
		$flag = $this->user_model->edit_user($data, $id);
		if($flag)
		{
			$arrdata = array(
							'user_id'=>$this->session->userdata('admin_id'),
							'activity'=>'Secondary Mobile No Updated',
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>$this->session->userdata('admin_id'),
							);

							$this->fund_model->add_log($arrdata);
			$arr = array('status'=>true);
		}else
		{
			$arr = array('status'=>false);
		}
			
       		echo json_encode($arr);
	}


	public function update_ifsc()
	{
		$data = array('ifsc_code'=>$_POST['new_ifsc'],'bank_name'=>$_POST['bank_name'],'branch_name'=>$_POST['branch_name'],'bank_address'=>$_POST['bank_address'],'account_no1'=>$_POST['account_no1']);
		$id   = $_POST['user_id'];
		$flag = $this->user_model->edit_user($data, $id);
		if($flag)
		{
			$arrdata = array(
							'user_id'=>$this->session->userdata('admin_id'),
							'activity'=>'Primary Bank Details Updated',
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>$this->session->userdata('admin_id'),
							);

							$this->fund_model->add_log($arrdata);
			$arr = array('status'=>true);
		}else
		{
			$arr = array('status'=>false);
		}
			
       		echo json_encode($arr);
	}

	public function update_ifsc_sec()
	{
		$data = array('sec_ifsc_code'=>$_POST['new_ifsc'],'sec_bank_name'=>$_POST['bank_name'],'sec_branch'=>$_POST['branch_name'],'sec_bank_address'=>$_POST['bank_address'],'account_no2'=>$_POST['account_no2']);
		$id   = $_POST['user_id'];
		$flag = $this->user_model->edit_user($data, $id);
		if($flag)
		{
			$arrdata = array(
							'user_id'=>$this->session->userdata('admin_id'),
							'activity'=>'Secondary Bank Details Updated',
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>$this->session->userdata('admin_id'),
							);

							$this->fund_model->add_log($arrdata);
			$arr = array('status'=>true);
		}else
		{
			$arr = array('status'=>false);
		}
			
       		echo json_encode($arr);
	}
	
	public function datatable_json(){				   					   
		$records['data'] = $this->user_model->get_all_users();
		$data = array();

		$i=0;
		foreach ($records['data']   as $row) 
		{  
			$img = base_url().'uploads/kyc_photo/'.$row['aadhar_photo'];


			$status = ($row['kyc_status'] == 1)? 'checked': '';
			$verify = ($row['is_verify'] == 1)? 'Verified': 'Pending';
			$status = strtoupper($status);
			$verify = strtoupper($verify);
			$data[] = array(
				++$i,
				strtoupper($row['username']),
				$row['email'],
				$row['mobile_no'],
				date('d/m/Y',strtotime($row['created_at'])),
			'<a href="'.base_url().'uploads/kyc_photo/'.$row['aadhar_photo'].'" class="pop" target="_blank"><img src="'.base_url().'uploads/kyc_photo/'.$row['aadhar_photo'].'" style="height:80px;width:80px;" ></a>',	
				'<a href="'.base_url().'uploads/kyc_photo/'.$row['pancard_photo'].'" class="pop1" target="_blank"><img src="'.base_url().'uploads/kyc_photo/'.$row['pancard_photo'].'" style="height:80px;width:80px;" onclick="show_modal2();"></a>',	
				'<input class="tgl_checkbox tgl-ios" 
				data-id="'.$row['id'].'" 
				id="cb_'.$row['id'].'"
				type="checkbox"  
				'.$status.'><label for="cb_'.$row['id'].'"></label>',		

				'<a title="View" class="view btn btn-sm btn-default" target=
				"_blank" href="'.base_url('admin/users/dashboard_view/'.$row['id']).'"> <i class="fa fa-eye"></i></a>
				<a title="Edit" class="update btn btn-sm btn-default" href="'.base_url('admin/users/edit/'.$row['id']).'"> <i class="fa fa-pencil-square-o"></i></a>
				<a title="Delete" class="delete btn btn-sm btn-default" href='.base_url("admin/users/delete/".$row['id']).' title="Delete" onclick="return confirm(\'Do you want to delete ?\')"> <i class="fa fa-trash-o"></i></a>'
			);
		}
		$records['data']=$data;
		echo json_encode($records);						   
	}

	//-----------------------------------------------------------
	function change_status()
	{   
		$this->user_model->change_status();
	}

	public function unblock_account($id)
	{
		$arr = array('close_account_status'=>'Open');

		$this->user_model->edit_user($arr,$id);
		$arrdata = array(
							'user_id'=>$id,
							'activity'=>'Account Reactivated Successfully',
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>$this->session->userdata('admin_id'),
							);

		$this->fund_model->add_log($arrdata);
		$this->session->set_flashdata('success', 'User Account has been Unblocked successfully!');
					redirect(base_url('admin/users'));

	}

	public function add(){
		
		$this->rbac->check_operation_access(); // check opration permission

		if($this->input->post('submit')){
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
			$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
			//$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			$this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');

			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('admin/users/add'),'refresh');
			}
			else{
					$user_exists = $this->user_model->check_no_exists(strtoupper($this->input->post('ac_no')));
              		if(!empty($user_exists))
                      {
                      $this->session->set_flashdata('error', "This Account No Is Already Exists!!");
					redirect(base_url('admin/users/add'),'refresh');
                    }else{
                      
                    
					$get_prefix = $this->user_model->get_prefix();
					$prefix 	= $get_prefix['account_no_prefix'];
					
					/*$check_no = $this->user_model->check_user_max();
					if(empty($check_no))
					{
						$account_no =1;
					}else
					{
						$account_no = $check_no['id']+1;
					}*/
					$ac_n     = random_int(100000, 999999);
					/*$check_no = $this->user_model->check_no_exists($prefix.$ac_n);
					if(empty($check_no))
					{
					    $account_no = $ac_n;
					}else
					{
						$account_no = $check_no['id']+1;
						
					}

					$account_no =  $prefix.$account_no;*/

					$sort_order = $this->user_model->check_order($this->input->post('parent_id'));
					if(!empty($sort_order))
					{
						$sort_order = $sort_order['sort_order'] + 1;
					}else
					{
						$sort_order = 1;
					}

					$data = array(
					'account_no'=>strtoupper($this->input->post('ac_no')),
					'sort_order'=>$sort_order,
					'reference_id'=>$this->input->post('account_no'),
					'username' => $this->input->post('username'),
					'firstname' => $this->input->post('firstname'),
					'lastname' => $this->input->post('lastname'),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					//'gender' 	=> $this->input->post('gender'),
					'is_parent' => $this->input->post('parent_id'),
					//'dob' => $this->input->post('dob'),
					//'age' => $this->input->post('age'),

					//'address' => $this->input->post('address'),
					'password' =>  password_hash($this->input->post('password'), PASSWORD_BCRYPT),
					'role'=>5,
					'my_fund'=>0,
					'self_capital'=>0,
					'capital_aum'=>0,
					'sip_balance'=>0,
					'is_verify'=>1,
					'is_supper'=>1,
					'is_active'=>1,
					'close_account_status'=>'Open',
					'created_at' => date('Y-m-d h:m:s'),
				    'updated_at' => date('Y-m-d h:m:s'),

				);
				$data = $this->security->xss_clean($data);
				$result = $this->user_model->add_user($data);
				if($result){

			    $check_direct = $this->user_model->get_user_detail($this->input->post('parent_id'));
				if(!empty($check_direct['my_direct']))
				{
					$my_direct = $check_direct['my_direct'] + 1;
				}else
				{
					$my_direct = 1;
				}

				$arr =  array('my_direct'=>$my_direct);
				$this->user_model->edit_user($arr,$this->input->post('parent_id'));

				 $arr = $this->setting_model->get_sms_by_id(7);

		  $sms = $arr['message'];
		  $sms = $this->str_replace_limit('{#var#}', $this->input->post('username'), $sms, 1);
		  $sms = $this->str_replace_limit('{#var#}',strtoupper($this->input->post('ac_no')) , $sms, 1);

		  $sms  = str_replace('{#var#}',$this->input->post('password'),$sms);
 		  $sms1 = $sms;
	
          $sms = urlencode($sms);
          $contact_mobile = urlencode('91'.$this->input->post('mobile_no'));
          //echo $contact_mobile;die();
          $user_id  = $this->session->userdata('user_id');
            /* Common_Helper Function To Send SMS */
          //send_sms($contact_mobile,$sms);
          /* Common Helper Function to Send EMail */

          send_sms_text($contact_mobile,$sms);
          send_email_user($this->input->post('email_id'),$sms1,'User Registration');
						
					$arrp  = $this->setting_model->get_sms_by_id(16);
		  		$smsp  = $arrp['message'];
		  		$smsp  = str_replace('{#var#}',strtoupper($this->input->post('ac_no')),$smsp);
 			
          $smsp = urlencode($smsp);
          $parent_data = $this->user_model->get_user_detail($this->input->post('parent_id'));
          $contact_mobilep = urlencode('91'.$parent_data['mobile_no']);
        
          //send_sms($contact_mobilep,$smsp);
          /* Common Helper Function to Send EMail */
		  		send_sms_text($contact_mobilep,$smsp);



					// Activity Log 
					$this->activity_model->add_log(1);
					$this->session->set_flashdata('success', 'User with Account No "'.strtoupper($this->input->post('ac_no')).'" AND Password "'.$this->input->post('password').'"  Registered Successfully!!');
					redirect(base_url('admin/users'));
				}
			}
          }
          
          
		}
		else{
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/users/user_add');
			$this->load->view('admin/includes/_footer');
		}
		
	}

	public function edit($id = 0){

		$this->rbac->check_operation_access(); // check opration permission

		if($this->input->post('submit')){
			if($this->input->post('submit')=='Reject')
			{
				$arr = $this->setting_model->get_sms_by_id(17);

		  		$sms = $arr['message'];
          		$sms = urlencode($sms);
          		$contact_mobile = urlencode('91'.$this->input->post('mobile_no'));
          		$user_id  = $this->session->userdata('user_id');
            		/* Common_Helper Function To Send SMS */
          		//send_sms($contact_mobile,$sms);
          		send_sms_text($contact_mobile,$sms);
  		
			}
			

			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('firstname', 'Username', 'trim|required');
			$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			$this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);

					$this->session->set_flashdata('errors', $data['errors']);
					redirect(base_url('admin/users/edit/'.$id),'refresh');
			}
			else{

			if(!empty($_FILES['aadhar_photo']['name']) && !empty($_FILES['pancard_photo']['name']) && empty($_FILES['photo']['name']))	
		{

				$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 10048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    	
    		if ( ! $this->upload->do_upload('aadhar_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['adharcard_photo'] =  $this->upload->data('file_name');

    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 10048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('pancard_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['pancard_photo'] =  $this->upload->data('file_name');
		}

		if(!empty($_FILES['aadhar_photo']['name']) && empty($_FILES['pancard_photo']['name']) &&  empty($_FILES['photo']['name']))	
		{
    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 10048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('aadhar_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['adharcard_photo'] =  $this->upload->data('file_name');
    	}	
		
		if(!empty($_FILES['pancard_photo']['name']) && empty($_FILES['aadhar_photo']['name']) && empty($_FILES['photo']['name']))	
		{
	    	$config['upload_path']   = './uploads/kyc_photo/';
	    	$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
	    	$config['max_size']      = 10048;
	    	$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
	    	$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('pancard_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['pancard_photo'] =  $this->upload->data('file_name');
    	}

    	if(!empty($_FILES['photo']['name']) && empty($_FILES['aadhar_photo']['name']) && empty($_FILES['pancard_photo']['name']))	
		{
	    	$config['upload_path']   = './uploads/profile_photo/';
	    	$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
	    	$config['max_size']      = 10048;
	    	$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
	    	$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['photo'] =  $this->upload->data('file_name');
    	}if(!empty($_FILES['photo']['name']) && !empty($_FILES['aadhar_photo']['name']) && empty($_FILES['pancard_photo']['name']))	
		{
	    	$config['upload_path']   = './uploads/profile_photo/';
	    	$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
	    	$config['max_size']      = 10048;
	    	$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
	    	$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['photo'] =  $this->upload->data('file_name');


    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 10048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('aadhar_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['adharcard_photo'] =  $this->upload->data('file_name');
    	}

    	if(!empty($_FILES['photo']['name']) && empty($_FILES['aadhar_photo']['name']) && !empty($_FILES['pancard_photo']['name']))	
		{
	    	$config['upload_path']   = './uploads/profile_photo/';
	    	$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
	    	$config['max_size']      = 10048;
	    	$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
	    	$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['photo'] =  $this->upload->data('file_name');


    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 10048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('pancard_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['pancard_photo'] =  $this->upload->data('file_name');
    	}

    	if(!empty($_FILES['photo']['name']) && !empty($_FILES['aadhar_photo']['name']) && !empty($_FILES['pancard_photo']['name']))	
		{
	    	$config['upload_path']   = './uploads/profile_photo/';
	    	$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
	    	$config['max_size']      = 10048;
	    	$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
	    	$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['photo'] =  $this->upload->data('file_name');


    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 10048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('aadhar_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['adharcard_photo'] =  $this->upload->data('file_name');

    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 10048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('pancard_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['pancard_photo'] =  $this->upload->data('file_name');
    	}


    	if(empty($data['photo']) &&  !empty($data['adharcard_photo']) && !empty($data['pancard_photo']))
    			{
    				$data = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'dob'	=> $this->input->post('dob'),
					'age'	=> $this->input->post('age'),

					'gender'	=> $this->input->post('gender'),
					'landmark' => strtoupper($this->input->post('landmark')),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'pincode'=>$this->input->post('pincode'),
					'district'=>$this->input->post('district'),
					'state'=>$this->input->post('state'),
					'city'=>$this->input->post('city'),

					'ifsc_code' => $this->input->post('ifsc_code'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'account_no2'=>strtoupper( $this->input->post('account_no2')),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',
					'aadhar_photo'=>$data['adharcard_photo'],
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),
					'pancard_photo'=>$data['pancard_photo'],
					'bank_holder_name'=>$this->input->post('bank_holder_name'),
					'sec_holder_name'=>$this->input->post('sec_holder_name'),
					);
					if(!empty($data['pancard_photo']))
					{
						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Pancard Photo Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					}
    			}else if( empty($data['photo']) && empty($data['adharcard_photo']) && empty($data['pancard_photo']))
    			{

    				$data = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'dob'	=> $this->input->post('dob'),
					'age'	=> $this->input->post('age'),
					'district'=>$this->input->post('district'),
					'state'=>$this->input->post('state'),
					'city'=>$this->input->post('city'),

					'gender'	=> $this->input->post('gender'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'ifsc_code' => $this->input->post('ifsc_code'),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'account_no2'=>strtoupper( $this->input->post('account_no2')),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'pincode'=>$this->input->post('pincode'),
					'bank_holder_name'=>$this->input->post('bank_holder_name'),
					'sec_holder_name'=>$this->input->post('sec_holder_name'),
					

					);
    			}else if( empty($data['photo']) && !empty($data['adharcard_photo']) && empty($data['pancard_photo']))
    			{
    				$data = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'gender'	=> $this->input->post('gender'),
					'dob'	=> $this->input->post('dob'),
					'age'	=> $this->input->post('age'),
					'district'=>$this->input->post('district'),
					'state'=>$this->input->post('state'),
					'city'=>$this->input->post('city'),

					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'pincode'=>$this->input->post('pincode'),
					'ifsc_code' => $this->input->post('ifsc_code'),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'account_no2'=>strtoupper( $this->input->post('account_no2')),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'pincode'=>$this->input->post('pincode'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'aadhar_photo'=>$data['adharcard_photo'],
					'bank_holder_name'=>$this->input->post('bank_holder_name'),
					'sec_holder_name'=>$this->input->post('sec_holder_name'),
					

					);
					if(!empty($data['adharcard_photo']))
					{
						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Aadhar Photo Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					}
    			}else if( empty($data['photo']) && empty($data['adharcard_photo']) && !empty($data['pancard_photo']))
    			{
    				$data = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email'    => $this->input->post('email'),
					'gender'   => $this->input->post('gender'),
					'age'	   => $this->input->post('age'),
					'dob'	=> $this->input->post('dob'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'pincode'=>$this->input->post('pincode'),
					'ifsc_code' => $this->input->post('ifsc_code'),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'account_no2'=>strtoupper( $this->input->post('account_no2')),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),
					'pancard_photo'=>$data['pancard_photo'],
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'district'=>$this->input->post('district'),
					'state'=>$this->input->post('state'),
					'city'=>$this->input->post('city'),
					'bank_holder_name'=>$this->input->post('bank_holder_name'),
					'sec_holder_name'=>$this->input->post('sec_holder_name'),
					


					);
					if(!empty($data['pancard_photo']))
					{
						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Pancard Photo Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					}
					
    			}else if(!empty($data['photo']) && empty($data['adharcard_photo']) && empty($data['pancard_photo']))
				{
					

					$data = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'gender'	=> $this->input->post('gender'),
					'age'	=> $this->input->post('age'),
					'district'=>$this->input->post('district'),
					'state'=>$this->input->post('state'),
					'city'=>$this->input->post('city'),

					'dob'	=> $this->input->post('dob'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'pincode'=>$this->input->post('pincode'),
					'aadhar_no'		=> $this->input->post('aadhar_no'),
					'pancard'		=> $this->input->post('pancard'),
					'photo'			=>$data['photo'], 
					'ifsc_code'   => $this->input->post('ifsc_code'),
					'account_no1' =>strtoupper( $this->input->post('account_no1')),
					'bank_name'   => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'account_no2'  =>strtoupper( $this->input->post('account_no2')),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'bank_holder_name'=>$this->input->post('bank_holder_name'),
					'sec_holder_name'=>$this->input->post('sec_holder_name'),
					
					);
					if(!empty($data['photo']))
					{
						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Profile Photo Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					}
				}else if(!empty($data['photo']) && !empty($data['adharcard_photo']) && empty($data['pancard_photo']))
				{
					

					$data = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'gender'	=> $this->input->post('gender'),
					'dob'	=> $this->input->post('dob'),
					'age'	=> $this->input->post('age'),
					'district'=>$this->input->post('district'),
					'state'=>$this->input->post('state'),
					'city'=>$this->input->post('city'),

					'ifsc_code' => $this->input->post('ifsc_code'),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'account_no2'=>strtoupper( $this->input->post('account_no2')),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),
					'pancard_photo'=>$data['pancard_photo'],
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'pincode'=>$this->input->post('pincode'),
					'aadhar_photo'=>$data['adharcard_photo'],
					'photo'			=>$data['photo'], 
					'bank_holder_name'=>$this->input->post('bank_holder_name'),
					'sec_holder_name'=>$this->input->post('sec_holder_name'),
					
			
					);
					if(!empty($data['photo']) && !empty($data['adharcard_photo']))
					{
						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Profile Photo And Aadhar Photo Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					}
				}else if(!empty($data['photo']) && !empty($data['adharcard_photo']) && !empty($data['pancard_photo']))
				{
					

					$data = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'dob'	=> $this->input->post('dob'),
					'gender'	=> $this->input->post('gender'),
					'pincode'=>$this->input->post('pincode'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'aadhar_no'		=> $this->input->post('aadhar_no'),
					'pancard'		=> $this->input->post('pancard'),
					'aadhar_photo'	=>$data['adharcard_photo'],
					'pancard_photo'	=>$data['pancard_photo'],
					'photo'			=>$data['photo'], 
					'ifsc_code' => $this->input->post('ifsc_code'),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'account_no2'=>strtoupper( $this->input->post('account_no2')),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),
				   'age'	=> $this->input->post('age'),
					'district'=>$this->input->post('district'),
					'state'=>$this->input->post('state'),
					'city'=>$this->input->post('city'),
					'bank_holder_name'=>$this->input->post('bank_holder_name'),
					'sec_holder_name'=>$this->input->post('sec_holder_name'),
					


					);

					if(!empty($data['photo']) && !empty($data['adharcard_photo']) && !empty($data['pancard_photo']))
					{
						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Profile Photo, Aadhar Photo And Pancard Photo Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					}
				}
				$userdet = $this->user_model->get_user_detail($id);
				if($userdet['mobile_no'] != $this->input->post('mobile_no') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Primary Mobile No Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}

				if($userdet['email'] != $this->input->post('email') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Email Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}
				if($userdet['sec_mobile_no'] != $this->input->post('sec_mobile_no') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Secondary Mobile No Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}
				if($userdet['address'] != $this->input->post('address') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Address Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}
				if($userdet['landmark'] != $this->input->post('landmark') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Landmark Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}

				if($userdet['pincode'] != $this->input->post('pincode') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Pincode Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}
				
				if($userdet['ifsc_code'] != $this->input->post('ifsc_code') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Primary IFSC Code Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}

				
				if($userdet['bank_name'] != $this->input->post('bank_name') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Primary Bank Name Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}
				if($userdet['sec_ifsc_code'] != $this->input->post('sec_ifsc_code') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Secondary IFSC Code Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}
				if($userdet['account_no1'] != $this->input->post('account_no1') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Primary Account No Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}

				if($userdet['account_no2'] != $this->input->post('account_no2') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Secondary Account No Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}
				if($userdet['sec_bank_name'] != $this->input->post('sec_bank_name') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Secondary Bank Name Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}

				if($userdet['pancard'] != $this->input->post('pancard') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Pancard No Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}

				if($userdet['aadhar_no'] != $this->input->post('aadhar_no') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Aadhar No Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}

				if($userdet['dob'] != $this->input->post('dob') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Birth Date Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}

				
				if($userdet['username'] != $this->input->post('username') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'First Name Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}

				if($userdet['firstname'] != $this->input->post('firstname') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Middle Name Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}

				if($userdet['lastname'] != $this->input->post('lastname') )
				{

						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Last Name Updated',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					
				}


				





				$data   = $this->security->xss_clean($data);
				$result = $this->user_model->edit_user($data, $id);
				//echo $this->db->last_query();die();
				if($result){
					// Activity Log 
					$this->activity_model->add_log(2);

					if($this->session->userdata('admin_role_id')=='5')
					{
						$this->session->set_flashdata('success', 'User has been Updated successfully!');
						redirect(base_url('admin/users/dashboard'));
					}else
					{
						$this->session->set_flashdata('success', 'User has been Updated successfully!');
						redirect(base_url('admin/users'));
					}
				}
			}
		}
		else{
			
			$data['user'] = $this->user_model->get_user_by_id($id);
			$data['active_alert'] = $this->user_model->get_active_alert();

			

			$this->load->view('admin/includes/_header',$data);
			$this->load->view('admin/users/user_edit', $data);
			$this->load->view('admin/includes/_footer');
		}
	}


	public function edit_kyc($id = 0){

		$this->rbac->check_operation_access(); // check opration permission

		if($this->input->post('submit')){

			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('firstname', 'Username', 'trim|required');
			$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			$this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);

					$this->session->set_flashdata('errors', $data['errors']);
					redirect(base_url('admin/users/edit_kyc/'.$id),'refresh');
			}
			else{

			if(!empty($_FILES['aadhar_photo']['name']) && !empty($_FILES['pancard_photo']['name']) && empty($_FILES['photo']['name']))	
		{

			$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 2048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('aadhar_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['adharcard_photo'] =  $this->upload->data('file_name');

    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 2048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('pancard_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit/'.$id),'refresh');
    		
    		}
    		$data['pancard_photo'] =  $this->upload->data('file_name');
		}

		if(!empty($_FILES['aadhar_photo']['name']) && empty($_FILES['pancard_photo']['name']) &&  empty($_FILES['photo']['name']))	
		{
    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 2048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('aadhar_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit_kyc/'.$id),'refresh');
    		
    		}
    		$data['adharcard_photo'] =  $this->upload->data('file_name');
    	}	
		
		if(!empty($_FILES['pancard_photo']['name']) && empty($_FILES['aadhar_photo']['name']) && empty($_FILES['photo']['name']))	
		{
	    	$config['upload_path']   = './uploads/kyc_photo/';
	    	$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
	    	$config['max_size']      = 2048;
	    	$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
	    	$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('pancard_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit_kyc/'.$id),'refresh');
    		
    		}
    		$data['pancard_photo'] =  $this->upload->data('file_name');
    	}

    	if(!empty($_FILES['photo']['name']) && empty($_FILES['aadhar_photo']['name']) && empty($_FILES['pancard_photo']['name']))	
		{
	    	$config['upload_path']   = './uploads/profile_photo/';
	    	$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
	    	$config['max_size']      = 2048;
	    	$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
	    	$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit_kyc/'.$id),'refresh');
    		
    		}
    		$data['photo'] =  $this->upload->data('file_name');
    	}if(!empty($_FILES['photo']['name']) && !empty($_FILES['aadhar_photo']['name']) && empty($_FILES['pancard_photo']['name']))	
		{
	    	$config['upload_path']   = './uploads/profile_photo/';
	    	$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
	    	$config['max_size']      = 2048;
	    	$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
	    	$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit_kyc/'.$id),'refresh');
    		
    		}
    		$data['photo'] =  $this->upload->data('file_name');


    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 2048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('aadhar_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit_kyc/'.$id),'refresh');
    		
    		}
    		$data['adharcard_photo'] =  $this->upload->data('file_name');
    	}

    	if(!empty($_FILES['photo']['name']) && empty($_FILES['aadhar_photo']['name']) && !empty($_FILES['pancard_photo']['name']))	
		{
	    	$config['upload_path']   = './uploads/profile_photo/';
	    	$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
	    	$config['max_size']      = 2048;
	    	$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
	    	$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit_kyc/'.$id),'refresh');
    		
    		}
    		$data['photo'] =  $this->upload->data('file_name');


    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 2048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('aadhar_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit_kyc/'.$id),'refresh');
    		
    		}
    		$data['pancard_photo'] =  $this->upload->data('file_name');
    	}

    	if(!empty($_FILES['photo']['name']) && !empty($_FILES['aadhar_photo']['name']) && !empty($_FILES['pancard_photo']['name']))	
		{
	    	$config['upload_path']   = './uploads/profile_photo/';
	    	$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
	    	$config['max_size']      = 2048;
	    	$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
	    	$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit_kyc/'.$id),'refresh');
    		
    		}
    		$data['photo'] =  $this->upload->data('file_name');


    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 2048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('aadhar_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit_kyc/'.$id),'refresh');
    		
    		}
    		$data['pancard_photo'] =  $this->upload->data('file_name');

    		$config['upload_path']   = './uploads/kyc_photo/';
    		$config['allowed_types'] = 'gif|jpeg|jpg|png|pdf';
    		$config['max_size']      = 2048;
    		$config['file_name']= round(microtime(true) * 1000); //just milisecond timestamp fot unique name
    		$this->load->library('upload', $config);
    		$this->upload->initialize($config);
    		if ( ! $this->upload->do_upload('aadhar_photo')) {
    		$error = array('error' => $this->upload->display_errors());
    		
					redirect(base_url('admin/users/edit_kyc/'.$id),'refresh');
    		
    		}
    		$data['adharcard_photo'] =  $this->upload->data('file_name');

    		//echo $data['adharcard_photo'];die();
    	}

			if(empty($data['photo']) &&  !empty($data['adharcard_photo']) && !empty($data['pancard_photo']))
    			{
    				$data1 = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					
					'dob'	=> $this->input->post('dob'),
					'gender'	=> $this->input->post('gender'),
					'ifsc_code' => $this->input->post('ifsc_code'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'account_no2'=>strtoupper( $this->input->post('account_no2')),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',
					'reject_reason'=>$this->input->post('reject_reason'),

					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),
					'pincode'=>$this->input->post('pincode'),

					'aadhar_photo'=>$data['adharcard_photo'],
					'pancard_photo'=>$data['pancard_photo'],
					);
    			}else if( empty($data['photo']) && empty($data['adharcard_photo']) && empty($data['pancard_photo']))
    			{
    				$data1 = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'dob'	=> $this->input->post('dob'),
					'gender'	=> $this->input->post('gender'),
					'ifsc_code' => $this->input->post('ifsc_code'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),

					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'account_no2'=>strtoupper( $this->input->post('account_no2')),
					'reject_reason'=>$this->input->post('reject_reason'),

					'pincode'=>$this->input->post('pincode'),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',

					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),

					);
    			}else if( empty($data['photo']) && !empty($data['adharcard_photo']) && empty($data['pancard_photo']))
    			{
    				$data1 = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'gender'	=> $this->input->post('gender'),
					'dob'	=> $this->input->post('dob'),
					'ifsc_code' => $this->input->post('ifsc_code'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'pincode'=>$this->input->post('pincode'),
					'reject_reason'=>$this->input->post('reject_reason'),

					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'aadhar_photo'=>$data['adharcard_photo'],
					'account_no2'=>strtoupper( $this->input->post('account_no2')),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',

					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),

					);
    			}else if( empty($data['photo']) && empty($data['adharcard_photo']) && !empty($data['pancard_photo']))
    			{
    				$data1 = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'gender'	=> $this->input->post('gender'),
					'reject_reason'=>$this->input->post('reject_reason'),

					'dob'	=> $this->input->post('dob'),
					'ifsc_code' => $this->input->post('ifsc_code'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'pincode'=>$this->input->post('pincode'),

	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',

					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'=> $this->input->post('aadhar_no'),
					'pancard'=> $this->input->post('pancard'),
					'pancard_photo'=>$data['pancard_photo'],
					'account_no2'=>strtoupper( $this->input->post('account_no2')),

					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),

					);
					
    			}else if(!empty($data['photo']) && empty($data['adharcard_photo']) && empty($data['pancard_photo']))
				{
					
				$data1 = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'gender'	=> $this->input->post('gender'),
					'reject_reason'=>$this->input->post('reject_reason'),

					'dob'	=> $this->input->post('dob'),
					'ifsc_code' => $this->input->post('ifsc_code'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'pincode'=>$this->input->post('pincode'),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',

					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'		=> $this->input->post('aadhar_no'),
					'pancard'		=> $this->input->post('pancard'),
					'photo'			=>$data['photo'], 
					'account_no2'=>strtoupper( $this->input->post('account_no2')),

					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),

					);
				}else if(!empty($data['photo']) && !empty($data['adharcard_photo']) && empty($data['pancard_photo']))
				{
					

					$data1 = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'gender'	=> $this->input->post('gender'),
					'reject_reason'=>$this->input->post('reject_reason'),

				    'dob'	=> $this->input->post('dob'),
					'ifsc_code' => $this->input->post('ifsc_code'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' => strtoupper($this->input->post('landmark')),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'pincode'=>$this->input->post('pincode'),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',

					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'		=> $this->input->post('aadhar_no'),
					'pancard'		=> $this->input->post('pancard'),
					'aadhar_photo'=>$data['adharcard_photo'],
					'photo'			=>$data['photo'], 
					'account_no2'=>strtoupper( $this->input->post('account_no2')),
			
					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),

					);
				}else if(!empty($data['photo']) && !empty($data['adharcard_photo']) && !empty($data['pancard_photo']))
				{

					$data1 = array(
					'username' => strtoupper($this->input->post('username')),
					'firstname' => strtoupper($this->input->post('firstname')),
					'lastname' => strtoupper($this->input->post('lastname')),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					//'password' =>  password_hash($this->input->post('password'), PASSWORD_BCRYPT),
					'dob'	=> $this->input->post('dob'),
					'gender'	=> $this->input->post('gender'),
					'account_no1'=>strtoupper( $this->input->post('account_no1')),
					'pincode'=>$this->input->post('pincode'),
	    			'sec_mobile_no'=>(!empty($this->input->post('sec_mobile_no')))?$this->input->post('sec_mobile_no'):'0',
					'reject_reason'=>$this->input->post('reject_reason'),

					'ifsc_code' => $this->input->post('ifsc_code'),
					'address'	=> strtoupper($this->input->post('address')),
					'landmark' 	=> strtoupper($this->input->post('landmark')),
					'bank_name' => $this->input->post('bank_name'),
					'branch_name' => $this->input->post('branch_name'),
					'bank_address' => $this->input->post('bank_address'),
					'aadhar_no'		=> $this->input->post('aadhar_no'),
					'pancard'		=> $this->input->post('pancard'),
					'aadhar_photo'	=>$data['adharcard_photo'],
					'pancard_photo'	=>$data['pancard_photo'],
					'photo'			=>$data['photo'], 
					'account_no2'=>strtoupper( $this->input->post('account_no2')),

					'sec_ifsc_code'=>$this->input->post('sec_ifsc_code'),
					'sec_bank_name'=>$this->input->post('sec_bank_name'),
					'sec_bank_address'=>$this->input->post('sec_bank_address'),
					'sec_branch'=>$this->input->post('sec_branch'),

					);
				}
				
				$data1   = $this->security->xss_clean($data1);
				$result = $this->user_model->edit_user($data1, $id);
				//print_r($this->db->last_query());die();
				if($result){
					// Activity Log 

					$data2['user'] = $this->user_model->get_user_detail($id);
					if(!empty($data2['user']))
					{
						if(!empty($data2['user']['bank_name']) && !empty($data2['user']['account_no1']) && !empty($data2['user']['address']) && !empty($data2['user']['ifsc_code']) && !empty($data2['user']['aadhar_photo']) && !empty($data2['user']['pancard_photo']) && !empty($data2['user']['pancard']) &&  !empty($data2['user']['aadhar_photo']))
						{
							$res1= array('kyc_date'=>date('Y-m-d'),'kyc_status'=>1);
							 $this->user_model->edit_user($res1, $id);
						}else
						{

						}
					}
					$this->activity_model->add_log(2);

					if($this->session->userdata('admin_role_id')=='5')
					{
						$this->session->set_flashdata('success', 'User has been Updated successfully!');
						redirect(base_url('admin/users/approve_kyc'));
					}else
					{
						$this->session->set_flashdata('success', 'User has been Updated successfully!');
						redirect(base_url('admin/users'));
					}
				}
			}
		}
		else{
			
			   $data['user'] = $this->user_model->get_user_by_id($id);

			
			$data['active_alert'] = $this->user_model->get_active_alert();

			$this->load->view('admin/includes/_header',$data);
			$this->load->view('admin/users/edit_kyc', $data);
			$this->load->view('admin/includes/_footer');
		}
	}

	public function delete($id = 0)
	{
		$this->rbac->check_operation_access(); // check opration permission
		
		$arr = array('is_active'=>0);
	    $this->db->where('id',$id);
		$this->db->update('ci_users',$arr);
		
		// Activity Log 
		$this->activity_model->add_log(3);

		$this->session->set_flashdata('success', 'User has been deleted successfully!');
		redirect(base_url('admin/users'));
	}

	public function password_change()
	{
		 $data['user_list'] = $this->user_model->get_all_users();
		 $data['active_alert'] = $this->user_model->get_active_alert();

		$this->load->view('admin/includes/_header',$data);
		$this->load->view('admin/users/user_password_change', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function update_user_pass()
	{
		$data = $this->input->post();
		if(!empty($data))
		{
			$arr = array('password'=>password_hash($data['password'],PASSWORD_BCRYPT));
			$this->user_model->edit_user($arr,$data['user_id']);

			$arr = $this->setting_model->get_sms_by_id(12);
  			$mobile = $this->user_model->get_user_detail($data['user_id']);
		  	$sms = $arr['message'];
		  	$sms = $this->str_replace_limit('{#var#}', $mobile['username'], $sms, 1);
		  $sms = str_replace('{#var#}',$data['password'],$sms);

          $sms = urlencode($sms);
       	  //echo $mobile['mobile_no'];die();
          $contact_mobile = urlencode($mobile['mobile_no']);

            /* Common_Helper Function To Send SMS */
          //send_sms($contact_mobile,$sms);
          send_sms_text($contact_mobile,$sms);
         	 	if(!empty($this->input->post('password')))
					{
						$arrdata = array(
						'user_id'=>$data['user_id'],
						'activity'=>'Password Changed',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					}
			$this->session->set_flashdata('success', 'Password Updated Successfully!!');
			redirect(base_url().'admin/users/password_change');

		}else
		{
					$this->session->set_flashdata('error', 'Please Select User And Enter Password To Update');
					redirect(base_url().'admin/users/password_change');


		}
	}



	//--------------------------------------------------
	function add_admin(){

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
						'email' => $this->input->post('email'),
						'mobile_no' => $this->input->post('mobile_no'),
						'password' =>  password_hash($this->input->post('password'), PASSWORD_BCRYPT),
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
				$data['active_alert'] = $this->user_model->get_active_alert();

				$this->load->view('admin/includes/_header', $data);
        		$this->load->view('admin/admin/add');
        		$this->load->view('admin/includes/_footer');
			}
	}


	//-----------------------------------------------------		
	function admin_list($type=''){

		$this->session->set_userdata('filter_type',$type);
		$this->session->set_userdata('filter_keyword','');
		$this->session->set_userdata('filter_status','');
		
		$data['admin_roles'] = $this->admin->get_admin_roles();
		
		$data['title'] = 'Admin List';

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/admin/index', $data);
		$this->load->view('admin/includes/_footer');
	}


	//-------------------------------------------------------------------------
	public function profile(){

		if($this->input->post('submit')){
			$data = array(
				'username' => $this->input->post('username'),
				'firstname' => $this->input->post('firstname'),
				'lastname' => $this->input->post('lastname'),
				'email' => $this->input->post('email'),
				'mobile_no' => $this->input->post('mobile_no'),
				'updated_at' => date('Y-m-d : h:m:s'),
			);
			$data = $this->security->xss_clean($data);
			$result = $this->admin_model->update_user($data);
			if($result){
				$this->session->set_flashdata('success', 'Profile has been Updated Successfully!');
				redirect(base_url('admin/profile'), 'refresh');
			}
		}
		else{

			$data['title'] = 'Admin Profile';
			$data['admin'] = $this->admin->get_user_detail();
			$data['active_alert'] = $this->user_model->get_active_alert();

			$this->load->view('admin/includes/_header');
			$this->load->view('admin/profile/index', $data);
			$this->load->view('admin/includes/_footer');
		}
	}


	public function client_levels()
	{
		$data['active_alert'] = $this->user_model->get_active_alert();

		$data['user_list'] =  $this->report_model->get_all_active_users_all(0,0);
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
		
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/report/client_levels', $data);
		$this->load->view('admin/includes/_footer');
	}


	//-------------------------------------------------------------------------
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

				
				$data = $this->security->xss_clean($data);
				if($this->session->userdata('admin_role_id')=='5')
				{
					$data = array(
					'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
				);
					$result = $this->user_model->change_pwd($data, $id);

				}else
				{
					$data = array(
					'password' => $this->input->post('password')
				);
					$result = $this->admin->change_pwd($data, $id);

				}

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
			$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
			$data['active_alert'] = $this->user_model->get_active_alert();

			$this->load->view('admin/includes/_header',$data);
			$this->load->view('admin/users/change_pwd', $data);
			$this->load->view('admin/includes/_footer');
		}
	}

/* DASHBOARD REPORTS */
	public function get_cap_history()
	{
			$data['title'] = 'Capital History';
			$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
			$data['active_alert'] = $this->user_model->get_active_alert();
			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['cap_history']  = $this->user_model->get_cap_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));
			}else
			{
					$data['cap_history']  = $this->user_model->get_cap_history($this->session->userdata('admin_id'),0,0);
		
			}
			$this->load->view('admin/includes/_header',$data);
			$this->load->view('admin/users/cap_history', $data);
			$this->load->view('admin/includes/_footer');
		
	}

	public function download_cap_history()
	{
		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
			$data['active_alert'] = $this->user_model->get_active_alert();
			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['cap_history']  = $this->user_model->get_cap_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));
			}else
			{
					$data['cap_history']  = $this->user_model->get_cap_history($this->session->userdata('admin_id'),0,0);
		
			}
			$this->load->library('MYPDF');
	  		 $settings =  $this->setting_model->get_general_settings();

			$my_team = $this->dashboard_model->get_active_capital_aum_ac();


		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Capital History');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Capital History', PDF_HEADER_STRING, array(0,64,255), 
  		array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);
 	
		// add a page
		$pdf->AddPage();
		$id=1;$capital_aum=0;
		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="20%" style="text-align:center">Date</th>
			<th  width="20%" style="text-align:center">Account No</th>
			<th  width="20%" style="text-align:center">Amount</th>
			<th  width="20%" style="text-align:center">Withdrawl</th>

			<th  width="20%" style="text-align:center"> Status</th>

			</tr><tbody>';
			
			foreach($data['cap_history'] as $row)
			{ 
				if($row['approved']==0)
				{
				 $status  = "Pending";
				}else
				{
				 $status  = "Approved";

				}
				if(!empty($row['withdraw_amount']))
				{
					$capital_aum+= bcdiv($row['amount']- $row['withdraw_amount'] ,1,2) ;

				}else
				{
				 $capital_aum+=bcdiv($row['amount'],1,2);

				}

			$txt.=	'<tr>
			<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['created_at']).'</td>
			<td style="text-align:center">' .$row['account_no'].'</td>';
			if(!empty($row['withdraw_amount']))
			{
				$txt.=	'<td style="text-align:center">' .($row['amount'] - $row['withdraw_amount']).'</td>';

			}else{

				$txt.=	'<td style="text-align:center">' .($row['amount']).'</td>';
			}

			
			$txt.=	'<td style="text-align:center">' .$row['withdraw_amount'].'</td>
			<td style="text-align:center">' .$status.'</td>
			</tr>';

			$id++;}
			
	$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="3" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="3">'.$words.'</th></tr><tr class="bo"><td colspan="6" class="bo">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="6" class="bo">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

			$pdf->writeHTML($txt, true, false, true, false, '');
		// print a block of text using Write()

		// ---------------------------------------------------------
		 ob_clean();
		//Close and output PDF document
		$pdf->Output('Capital_History.pdf', 'I');
	
	}

	public function get_sip_history()
	{
			$data['title'] = 'SIP AUM';
			$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
			$data['active_alert'] = $this->user_model->get_active_alert();
			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['cap_history']  = $this->user_model->get_sip_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$data['cap_history']  = $this->user_model->get_sip_history($this->session->userdata('admin_id'),0,0);

			}
			
			$this->load->view('admin/includes/_header',$data);
			$this->load->view('admin/users/sip_history', $data);
			$this->load->view('admin/includes/_footer');
		
	}

	public function download_sip_history()
	{
		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
			$data['active_alert'] = $this->user_model->get_active_alert();
			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['cap_history']  = $this->user_model->get_sip_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$data['cap_history']  = $this->user_model->get_sip_history($this->session->userdata('admin_id'),0,0);

			}
			
			$this->load->library('MYPDF');
	  		 $settings =  $this->setting_model->get_general_settings();

			$my_team = $this->dashboard_model->get_active_capital_aum_ac();


		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('SIP History');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Capital History', PDF_HEADER_STRING, array(0,64,255), 
  		array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);
 	
		// add a page
		$pdf->AddPage();
		$id=1;$capital_aum=0;
		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="20%" style="text-align:center">Date</th>
			<th  width="20%" style="text-align:center">Account No</th><th  width="20%" style="text-align:center">Amount</th>
			<th  width="20%" style="text-align:center"> Status</th>

			</tr><tbody>';
			
			foreach($data['cap_history'] as $row)
			{ 
				if($row['approved']==0)
				{
				 $status  = "Pending";
				}else
				{
				 $status  = "Approved";

				}
				$capital_aum+=$row['amount'];
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['created_at']).'</td>
			<td style="text-align:center">' .$row['account_no'].'</td>

			<td style="text-align:center">' .$row['amount'].'</td>
			<td style="text-align:center">' .$status.'</td>


			</tr>';

			$id++;}
			
	$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="3" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="3" style="text-align:center;">'.$words.'</th></tr><tr class="bo"><td colspan="6" class="bo">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="6" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

			$pdf->writeHTML($txt, true, false, true, false, '');
		// print a block of text using Write()

		// ---------------------------------------------------------
		 ob_clean();
		//Close and output PDF document
		$pdf->Output('Capital_History.pdf', 'I');
	}


	public function capital_cash_history($no_of_days)
	{
			$data['title'] = 'Capital Cashback History';
			$data['user']  = $this->user_model->get_user_detail($this->session->userdata('admin_id'));
			$data['active_alert'] = $this->user_model->get_active_alert();
			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['cap_history']  = $this->user_model->get_capital_return_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$data['cap_history']  = $this->user_model->get_capital_return_history($this->session->userdata('admin_id'),0,0);

			}
      		if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['extra_aum']  = $this->user_model->get_extra_return_history1($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$data['extra_aum']  = $this->user_model->get_extra_return_history1($this->session->userdata('admin_id'),0,0);

			}

			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['capital_cash_history'] = $this->user_model->get_capital_cash($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$data['capital_cash_history'] = $this->user_model->get_capital_cash($this->session->userdata('admin_id'),0,0);

			}

			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['extra_aum_history'] = $this->user_model->get_extra_aum_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$data['extra_aum_history'] = $this->user_model->get_extra_aum_history($this->session->userdata('admin_id'),0,0);

			}
			$data['no_of_days'] = $no_of_days;
			
			$this->load->view('admin/includes/_header',$data);
			$this->load->view('admin/users/capital_cash_history', $data);
			$this->load->view('admin/includes/_footer');
		

	}

	public function download_capital_cash_history()
	{

		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
			$data['active_alert'] = $this->user_model->get_active_alert();
			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['cap_history']  = $this->user_model->get_capital_return_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$data['cap_history']  = $this->user_model->get_capital_return_history($this->session->userdata('admin_id'),0,0);

			}
      		if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['extra_aum']  = $this->user_model->get_extra_return_history1($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$data['extra_aum']  = $this->user_model->get_extra_return_history1($this->session->userdata('admin_id'),0,0);

			}

			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$capital_cash_history = $this->user_model->get_capital_cash($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$capital_cash_history = $this->user_model->get_capital_cash($this->session->userdata('admin_id'),0,0);

			}

			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$extra_aum_history = $this->user_model->get_extra_aum_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$extra_aum_history = $this->user_model->get_extra_aum_history($this->session->userdata('admin_id'),0,0);

			}



      	$user = $this->user_model->get_user_detail($this->session->userdata('admin_id'));
	   $this->load->library('MYPDF');
	   $settings =  $this->setting_model->get_general_settings();

		$my_team = $this->dashboard_model->get_active_capital_aum_ac();


		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Capital Cashback History');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  	array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);
 		$txt ="";
		// add a page
		$pdf->AddPage();
		$id=1;$capital_aum=0;
		// set some text to print
		$txt .= '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="20%" style="text-align:center">Date</th>
			<th  width="20%" style="text-align:center">Account No</th>
			<th  width="20%" style="text-align:center">Capital AUM</th>
		    <th  width="20%" style="text-align:center">Capital Cashback</th>
		    <th  width="20%" style="text-align:center">Withdraw Amount</th>

			</tr><tbody>';
			$id=1;
			$capital_aum=0;
			if(!empty($user['back_entry_int']))
			{
				$txt.=	'<tr>
				<td style="text-align:center">' ."1".'</td>
			<td style="text-align:center">' .date_time($user['capital_aum_date']).'</td>
			<td style="text-align:center">' .$user['account_no'].'</td>

			<td style="text-align:center">' .$user['capital_aum'].'</td>
			<td style="text-align:center">' .bcdiv(($user['back_entry_int']),1,2).'</td>
			<td style="text-align:center">' ."0.00".'</td>


			</tr>';
			}
			foreach($capital_cash_history as $row)
			{

				     if(empty($row['withdrawl'])){

			 $capital_aum+=bcdiv($row['capital_aum_interest']/30,1,2);
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['from_date']).'</td>
			<td style="text-align:center">' .$row['account_no'].'</td>

			<td style="text-align:center">' .$row['capital_aum'].'</td>
			<td style="text-align:center">' .bcdiv(($row['capital_aum_interest']/30),1,2).'</td>
			<td style="text-align:center">' .bcdiv($row['withdrawl'],1,2).'</td>


			</tr>';

			$id++;} }

			foreach($extra_aum_history as $row)
			{
			if(empty($row['withdrawl'])){

				$capital_aum+=bcdiv($row['new_interest']/30,1,2);
			$txt.=	'<tr>
			<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['from_date']).'</td>
			<td style="text-align:center">' .$row['account_no'].'</td>

			<td style="text-align:center">' .$row['new_cap'].'</td>
			<td style="text-align:center">' .($row['new_interest']/30).'</td>
			<td style="text-align:center">' .bcdiv($row['withdrawl'],1,2).'</td>


			</tr>';

			$id++;
			} }

	$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="4" style="text-align:center">'.round($capital_aum,2).'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="4" style="text-align:center;">'.$words.'</th></tr><tr class="bo"><td colspan="6" class="bo">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 66px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="6" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

			$pdf->writeHTML($txt, true, false, true, false, '');
		// print a block of text using Write()

		// ---------------------------------------------------------
		 ob_clean();
		//Close and output PDF document
		$pdf->Output('Capital_Cash_History.pdf', 'I');
	}

	public function sip_cash_history()
	{
		  $data['title'] = 'SIP Cashback';
			$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
			$data['active_alert'] = $this->user_model->get_active_alert();
			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['cap_history']  = $this->user_model->get_sip_return_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$data['cap_history']  = $this->user_model->get_sip_return_history($this->session->userdata('admin_id'),0,0);

			}

			$data['extra_aum_sip']  = $this->user_model->get_extra_sip_history1($this->session->userdata('admin_id'),0,0);
       		if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
       		{
       			$data['sip_cash_history']  = $this->user_model->get_sip_cash_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

       		}else
       		{
       			$data['sip_cash_history']  = $this->user_model->get_sip_cash_history($this->session->userdata('admin_id'),0,0);

       		}

       		if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
       		{
       			$data['extra_sip_history']  = $this->user_model->get_extra_sip_cash_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

       		}else
       		{
       			$data['extra_sip_history']  = $this->user_model->get_extra_sip_cash_history($this->session->userdata('admin_id'),0,0);

       		}


			
			$this->load->view('admin/includes/_header',$data);
			$this->load->view('admin/users/sip_cash_history', $data);
			$this->load->view('admin/includes/_footer');
		

	}

	public function download_sip_cash_history()
	{
		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
			$data['active_alert'] = $this->user_model->get_active_alert();
			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['cap_history']  = $this->user_model->get_sip_return_history($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$data['cap_history']  = $this->user_model->get_sip_return_history($this->session->userdata('admin_id'),0,0);

			}

			if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
			{
				$data['extra_aum_sip']  = $this->user_model->get_extra_sip_history1($this->session->userdata('admin_id'),$this->input->post('from_date'),$this->input->post('to_date'));

			}else
			{
				$data['extra_aum_sip']  = $this->user_model->get_extra_sip_history1($this->session->userdata('admin_id'),0,0);

			}
		$this->load->library('MYPDF');
	   	$settings =  $this->setting_model->get_general_settings();

		$my_team = $this->dashboard_model->get_active_capital_aum_ac();
		$user    = $this->user_model->get_user_detail($this->session->userdata('admin_id'));

		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('SIP Cashback History');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'SIP Cashback History', PDF_HEADER_STRING, array(0,64,255), 
  		array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);
 	
		// add a page
		$pdf->AddPage();
		$id=1;$capital_aum=0;
		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="20%" style="text-align:center">Date</th>
			<th  width="20%" style="text-align:center">Account No</th><th  width="20%" style="text-align:center">Username</th>
			<th  width="20%" style="text-align:center">SIP AUM</th>
		    <th  width="20%" style="text-align:center">SIP Cashback</th>
			</tr><tbody>';
			if(!empty($user['back_enry_sip_int']))
			{
				$txt.=	'<tr>
				<td style="text-align:center">' ."1".'</td>
			<td style="text-align:center">' .date_time($user['sip_date']).'</td>
			<td style="text-align:center">' .$user['account_no'].'</td>

			<td style="text-align:center">' .$user['sip_balance'].'</td>
			<td style="text-align:center">' .bcdiv(($user['back_enry_sip_int']),1,2).'</td>
			<td style="text-align:center">' ."0.00".'</td>


			</tr>';
			}
			if(!empty($data['cap_history']))
			{
			foreach($data['cap_history'] as $row)
			{ 
				if(empty($row['withdrawl'])){

				$capital_aum+=($row['sip_interest']/30);
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['from_date']).'</td>
			<td style="text-align:center">' .$row['account_no'].'</td>

			<td style="text-align:center">'.$row['username'].'</td>
			<td style="text-align:center">' .$row['sip_balance'].'</td>
			<td style="text-align:center">' .($row['sip_interest']/30).'</td>


			</tr>';

			$id++;} } }
			if(!empty($data['extra_aum_sip']))
			{
			foreach($data['extra_aum_sip'] as $row)
			{
				if(empty($row['withdrawl'])){

				$capital_aum+=($row['new_interest']/30);
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['from_date']).'</td>
			<td style="text-align:center">' .$row['account_no'].'</td>

			<td style="text-align:center">'.$row['username'].'</td>
			<td style="text-align:center">' .$row['new_sip'].'</td>
			<td style="text-align:center">' .($row['new_interest']/30).'</td>


			</tr>';

			$id++;
			} } }

	$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="4" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="3">'.$words.'</th></tr><tr class="bo"><td colspan="6" class="bo">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="6" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

			$pdf->writeHTML($txt, true, false, true, false, '');
		// print a block of text using Write()

		// ---------------------------------------------------------
		 ob_clean();
		//Close and output PDF document
		$pdf->Output('Active_Capital.pdf', 'I');
	}

	public function get_royalty_capital_history($from_date,$to_date)
	{
		$data['title'] = "Royalty Capital History";
		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income    = 0;$level9_income = 0;$level10_income = 0;
		$data['setting2']      	= $this->setting_model->get_general_settings();

	    $setting      	= $this->setting_model->get_general_settings();
	    $team_capital_aum =0;$team_count = 0;$team_self_capital = 0;
		$data['active_alert'] = $this->user_model->get_active_alert();
 		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
 		$data['team_income_level2']=0;$data['team_income_level3']=0;$data['team_income_level4'] =0;$data['team_income_level5']=0;$data['team_income_level6']=0;$data['team_income_level7']=0;$data['team_income_level8']=0;$data['team_income_level9']=0;$data['team_income_level10']=0;
		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date   = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d') < $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();


			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					if(!empty($row['final_cap_date']))
					{
						
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
                  	
                    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    			$level1_income = $level1_income/30;
				    			$level1_income =  round($level1_income * $interval);
							}
							if($interval>30)
							{
								$level1_income = $level1_income/30;
				    			$level1_income =  round($level1_income * $interval);
						
							}
						  	array_push($l1_arr,$row['id']);


				 }   
			}
					
			}


			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
				{
				
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);
				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row1)
 						{
 							if(!empty($row1['capital_aum']))
							{

								if(!empty($row['final_cap_date']))
								{
							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row1['capital_aum'] * $per;
							   $team_capital_aum += $row1['capital_aum'];
							    $data['level2_capital']+=$row1['capital_aum']; 
	 						   $team_self_capital+= $row1['self_capital'];
                              $date   = date('Y-m-d',strtotime($row1['final_cap_date']));
                              $date = date('Y-m-d', strtotime($date . ' +1 day'));

                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level2_income = $level2_income/30;
				    			$level2_income =  round($level2_income * $interval);
							}

							if($interval>30)
							{
								$level2_income = $level2_income/30;
				    			$level2_income =  round($level2_income * $interval);
						
							}
								$team_count++;
								array_push($l2_arr,$row1['id']);
							}
						}
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						$data['level_2'] 		= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 

 					}
			}	

	}		

		if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{
							if(!empty($row['final_cap_date']))
					{
						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level3'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

						$per     = ceil($setting['level3_incentive'])/100;
								 $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
							   $date   = date('Y-m-d',strtotime($row['final_cap_date']));
							   $date = date('Y-m-d', strtotime($date . ' +1 day'));

                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    		$level3_income = $level3_income/30;
				    		$level3_income =  round($level3_income * $interval);
							}

							if($interval>30)
							{
								$level3_income = $level3_income/30;
				    			$level3_income =  round($level3_income * $interval);
						
							}

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
						}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  
			}
			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
							{
								$per              = ceil($setting['level4_incentive'])/100;
								$level4_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								$date   = date('Y-m-d',strtotime($row['final_cap_date']));
							 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  round($level4_income * $interval);
							}

							if($interval>30)
							{
								$level4_income = $level4_income/30;
				    			$level4_income =  round($level4_income * $interval);
							}
						    $team_count++;
							array_push($l4_arr,$row['id']);
							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income ;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 
				 	 }	
					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								
								if(!empty($row['final_cap_date']))
								{
								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								$date   = date('Y-m-d',strtotime($row['final_cap_date']));
								 $date = date('Y-m-d', strtotime($date . ' +1 day'));
								 $date = date('Y-m-d', strtotime($date . ' +1 day'));
                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  round($level5_income * $interval);
							}

							if($interval>30)
							{
								$level5_income = $level5_income/30;
				    			$level5_income =  round($level5_income * $interval);
							}
							 	$team_count++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income ;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  
			}
			}



			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
								{		

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];
                              
                              $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                              $date = date('Y-m-d', strtotime($date . ' +1 day'));
                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  round($level6_income * $interval);
							}

							if($interval>30)
							{
								$level6_income = $level6_income/30;
				    			$level6_income =  round($level6_income * $interval);
							}
							array_push($l6_arr,$row['id']);

						}

							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								
								if(!empty($row['final_cap_date']))
								{
								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;
                          
                           	   $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                           	   $date = date('Y-m-d', strtotime($date . ' +1 day'));
                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  round($level7_income * $interval);
							}

							if($interval>30)
							{
								$level7_income = $level7_income/30;
				    			$level7_income =  round($level7_income * $interval);
							}

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 
				 	 }
					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
									
								if(!empty($row['final_cap_date']))
								{
								$per              = ceil($setting['level8_incentive'])/100;
								$level8_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

                              $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                              $date = date('Y-m-d', strtotime($date . ' +1 day'));
                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  round($level8_income * $interval);
							}

							if($interval>30)
							{
								$level8_income = $level8_income/30;
				    			$level8_income =  round($level8_income * $interval);
							}
								 $team_count++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income ;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
						$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
							{	

							  $per              = ceil($setting['level9_incentive'])/100;
							  $level9_income   += $row['capital_aum'] * $per;
							  $team_capital_aum+= $row['capital_aum'];
							  $data['level9_capital']+=$row['capital_aum']; 

							  $team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);
							  $date   = date('Y-m-d',strtotime($row['final_cap_date']));
							  $date = date('Y-m-d', strtotime($date . ' +1 day'));
                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  round($level9_income * $interval);
							}

							if($interval>30)
							{
								$level9_income = $level9_income/30;
				    			$level9_income =  round($level9_income * $interval);
							}

							
							 $team_count++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  
			}
			


			if(count($data['team_income_level1'])>=33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
								{	

								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);
						    $date   = date('Y-m-d',strtotime($row['final_cap_date']));
							$date = date('Y-m-d', strtotime($date . ' +1 day'));
                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  round($level10_income * $interval);
							}

							if($interval>30)
							{
								$level10_income = $level10_income/30;
				    			$level10_income =  round($level10_income * $interval);
							}
				

							}
							 $team_count++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  

			}

		}
	}

		}


		$data['my_team'] = $this->user_model->get_all_user_detail($user_id);
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/get_royalty_capital_history', $data);
		$this->load->view('admin/includes/_footer');
	

	}

	public function download_royalty_capital_history()
	{
		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income   = 0;$level9_income = 0;$level10_income = 0;
		$data['setting2']      	= $this->setting_model->get_general_settings();

	    $setting      	= $this->setting_model->get_general_settings();
	    $team_capital_aum =0;$team_count = 0;$team_self_capital = 0;
		$data['active_alert'] = $this->user_model->get_active_alert();
 		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
 		$data['team_income_level2']=0;$data['team_income_level3']=0;$data['team_income_level4'] =0;$data['team_income_level5']=0;$data['team_income_level6']=0;$data['team_income_level7']=0;$data['team_income_level8']=0;$data['team_income_level9']=0;$data['team_income_level10']=0;
		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					if(!empty($row['final_cap_date'])){
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
                  	
                   $date = date('Y-m-d',strtotime($row['final_cap_date']));
            		$date = date('Y-m-d', strtotime($date . ' +1 day'));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30 && $interval>0)
							{
				    		$level1_income = $level1_income/30;
				    		$level1_income =  round($level1_income * $interval);
							}
							if($interval>30)
							{
				    		$level1_income = $level1_income/30;
				    		$level1_income =  round($level1_income * $interval);
							}
				    array_push($l1_arr,$row['id']);
				    
				}
				}	

			}
			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['final_cap_date'])){
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);
				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row1)
 						{
 							if(!empty($row1['capital_aum']))
							{


							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row1['capital_aum'] * $per;
							   $team_capital_aum += $row1['capital_aum'];
							    $data['level2_capital']+=$row1['capital_aum']; 
	 						   $team_self_capital+= $row1['self_capital'];
                              	$date   = date('Y-m-d',strtotime($row1['final_cap_date']));
                              	$date = date('Y-m-d', strtotime($date . ' +1 day'));

                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    		$level2_income = $level2_income/30;
				    		$level2_income =  round($level2_income * $interval);
							}

							if($interval>30)
							{
				    			$level2_income = $level2_income/30;
				    			$level2_income =  round($level2_income * $interval);
							}
								$team_count++;
								array_push($l2_arr,$row1['id']);
							}
							
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						$data['level_2'] 		= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					   }
 					}
			}	

	}

			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
					if(!empty($row['final_cap_date'])){
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level2'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								 $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
							   $date   = date('Y-m-d',strtotime($row['final_cap_date']));
							   $date = date('Y-m-d', strtotime($date . ' +1 day'));

                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    		$level3_income = $level3_income/30;
				    		$level3_income =  round($level3_income * $interval);
							}

							if($interval>30)
							{
				    			$level3_income = $level3_income/30;
				    			$level3_income =  round($level3_income * $interval);
							}

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
						}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  
			}
			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						
 						if(!empty($row['final_cap_date'])){	
						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level4_incentive'])/100;
								$level4_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								$date   = date('Y-m-d',strtotime($row['final_cap_date']));
								 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  round($level4_income * $interval);
							}

							if($interval>30)
							{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  round($level4_income * $interval);
							}
						       $team_count++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 
				 	 }
					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								
								if(!empty($row['final_cap_date'])){
								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								$date   = date('Y-m-d',strtotime($row['final_cap_date']));
								 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval)
							{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  round($level5_income * $interval);
							}

							if($interval>30)
							{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  round($level5_income * $interval);
							}
							 	$team_count++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  
			}
			}


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date'])){

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];
                              
                              $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                            $date = date('Y-m-d', strtotime($date . ' +1 day'));

                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  round($level6_income * $interval);
							}
							if($interval>30)
							{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  round($level6_income * $interval);
							}
							array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}

		}

			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								
								if(!empty($row['final_cap_date'])){
								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;
                          
                           	   $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                                $date = date('Y-m-d', strtotime($date . ' +1 day'));

                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  round($level7_income * $interval);
							}

							if($interval>30)
							{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  round($level7_income * $interval);
							}

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;
						}

						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								
							if(!empty($row['final_cap_date'])){
								$per              = ceil($setting['level8_incentive'])/100;
								$level8_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

                              $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                              $date = date('Y-m-d', strtotime($date . ' +1 day'));

                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  round($level8_income * $interval);
							}

							if($interval>30)
							{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  round($level8_income * $interval);
							}
								 $team_count++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 
				 	 }
					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								
								if(!empty($row['final_cap_date'])){
								$per              = ceil($setting['level9_incentive'])/100;
								$level9_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);
							  $date   = date('Y-m-d',strtotime($row['final_cap_date']));
							 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  round($level9_income * $interval);
							}

							if($interval>30)
							{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  round($level9_income * $interval);
							}

							
							 $team_count++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  
			}
			


			if(count($data['team_income_level1'])==33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								
								if(!empty($row['final_cap_date'])){
								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);
								 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                              $date = date('Y-m-d', strtotime($date . ' +1 day'));

                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  round($level10_income * $interval);
							}

							if($interval>30)
							{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  round($level10_income * $interval);
							}
				

							}
							 $team_count++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  
			}
			}

		}


		}

		$this->load->library('MYPDF');
	   $settings =  $this->setting_model->get_general_settings();

		$my_team = $this->user_model->get_all_inactive($this->session->userdata('admin_id'));


		// set some text to print
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Total Active Account');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print

		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="15%" style="text-align:center">Date</th>
			<th  width="15%" style="text-align:center">Account No</th><th  width="12%" style="text-align:center">Username</th>
			<th  width="15%" style="text-align:center">Capital AUM</th>
			<th  width="15%" style="text-align:center">Team Income</th>
			<th  width="15%" style="text-align:center">Sponsor</th>
			<th  width="15%" style="text-align:center">Level</th>

			</tr><tbody>';
			$capital_aum=0;$id=1;
			if(!empty($data['team_income_level1']))
			{

				foreach($data['team_income_level1'] as $row)
				{
					if(!empty($row['final_cap_date'])){
				   $sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$per              	  = ceil($setting['level1_incentive'])/100;

					 $level1_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level1_income = $level1_income/30;
				    			$level1_income =  bcdiv($level1_income * $interval,1,2);
					}
					if($interval>30)
					{
				    			$level1_income = $level1_income/30;
				    			$level1_income =  bcdiv($level1_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['capital_aum'].'</td>
					<td style="text-align:center;">' .$level1_income.'</td>
					
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					
					<td style="text-align:center;">' .'Level 1'.'</td>
					</tr>';
					$capital_aum +=$level1_income;

				$id++;
				}
			}
			}
			if(!empty($data['team_income_level2']))
			{

				foreach($data['team_income_level2'] as $row)
				{
					if(!empty($row['final_cap_date'])){
					$per              	  = ceil($setting['level2_incentive'])/100;
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					 $level2_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level2_income = $level2_income/30;
				    			$level2_income =  bcdiv($level2_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level2_income = $level2_income/30;
				    			$level2_income =  bcdiv($level2_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['capital_aum'].'</td>
					<td style="text-align:center;">' .$level2_income.'</td>
					
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					
					<td style="text-align:center;">' .'Level 2'.'</td></tr>';
					
					$capital_aum +=$level2_income;

				$id++;
				}
			}
		}

			if(!empty($data['team_income_level3']))
			{

				foreach($data['team_income_level3'] as $row)
				{
					if(!empty($row['final_cap_date'])){
					$per       = ceil($setting['level3_incentive'])/100;
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$level3_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level3_income = $level3_income/30;
				    			$level3_income =  bcdiv($level3_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level3_income = $level3_income/30;
				    			$level3_income =  bcdiv($level3_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['capital_aum'].'</td>
					<td style="text-align:center;">' .$level3_income.'</td>
					
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					
					<td style="text-align:center;">' .'Level 3'.'</td></tr>';
					
					$capital_aum +=$level3_income;

				$id++;
				}
			}
		}
			if(!empty($data['team_income_level4']))
			{

				foreach($data['team_income_level4'] as $row)
				{
					if(!empty($row['final_cap_date'])){
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$per       = ceil($setting['level4_incentive'])/100;

					$level4_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  bcdiv($level4_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  bcdiv($level4_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['capital_aum'].'</td>
					<td style="text-align:center;">' .$level4_income.'</td>
					</tr>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					
					<td style="text-align:center;">' .'Level 4'.'</td></tr>';
					
					$capital_aum +=$level4_income;

				$id++;
				}
			}
		}
			if(!empty($data['team_income_level5']))
			{

				foreach($data['team_income_level5'] as $row)
				{
					if(!empty($row['final_cap_date'])){
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$per       = ceil($setting['level5_incentive'])/100;

					 $level5_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  bcdiv($level5_income * $interval,1,2);
					}
					if($interval>30)
					{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  bcdiv($level5_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['capital_aum'].'</td>
					<td style="text-align:center;">' .$level5_income.'</td>
					
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					
					<td style="text-align:center;">' .'Level 5'.'</td></tr>';
					
				$capital_aum +=$level5_income;

				$id++;
				}
			}
		}
			if(!empty($data['team_income_level6']))
			{

				foreach($data['team_income_level6'] as $row)
				{
					if(!empty($row['final_cap_date'])){
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$per       = ceil($setting['level6_incentive'])/100;

					 $level6_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  bcdiv($level6_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  bcdiv($level6_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['capital_aum'].'</td>
					<td style="text-align:center;">' .$level6_income.'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					
					<td style="text-align:center;">' .'Level 6'.'</td></tr>';
				$capital_aum +=$level6_income;

				$id++;
				}
			}
		}
			if(!empty($data['team_income_level7']))
			{

				foreach($data['team_income_level7'] as $row)
				{
					if(!empty($row['final_cap_date'])){
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$per       = ceil($setting['level7_incentive'])/100;

					$level7_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  bcdiv($level7_income * $interval,1,2);
					}
					if($interval>30)
					{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  bcdiv($level7_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['capital_aum'].'</td>
					<td style="text-align:center;">' .$level7_income.'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					
					<td style="text-align:center;">' .'Level 6'.'</td></tr>';
				$capital_aum +=$level7_income;

				$id++;
				}
			}
		}

			if(!empty($data['team_income_level8']))
			{

				foreach($data['team_income_level8'] as $row)
				{
					if(!empty($row['final_cap_date'])){
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$per       = ceil($setting['level8_incentive'])/100;

					 $level8_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  bcdiv($level8_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  bcdiv($level8_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['capital_aum'].'</td>
					<td style="text-align:center;">' .$level8_income.'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					
					<td style="text-align:center;">' .'Level 8'.'</td></tr>';
				$capital_aum +=$level8_income;

				$id++;
				}
			}
			}
			if(!empty($data['team_income_level9']))
			{

				foreach($data['team_income_level9'] as $row)
				{
					if(!empty($row['final_cap_date'])){
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$per       = ceil($setting['level9_incentive'])/100;

					 $level9_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  bcdiv($level9_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  bcdiv($level9_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['capital_aum'].'</td>
					<td style="text-align:center;">' .$level9_income.'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					
					<td style="text-align:center;">' .'Level 9'.'</td></tr>';
				
				$capital_aum +=$level9_income;

				$id++;
				}
			}
		}
			if(!empty($data['team_income_level10']))
			{

				foreach($data['team_income_level10'] as $row)
				{
					if(!empty($row['final_cap_date'])){
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$per       = ceil($setting['level10_incentive'])/100;

					 $level10_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  bcdiv($level10_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level10_income = $level10_income/30;
				    			$level10_income  =  bcdiv($level10_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['capital_aum'].'</td>
					<td style="text-align:center;">' .$level10_income.'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					
					<td style="text-align:center;">' .'Level 10'.'</td></tr>';
				
					$capital_aum +=$level10_income;
				$id++;
				}
			}
		}

	$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="6" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="6" style="text-align:center;">'.$words.'</th></tr><tr class="bo" ><td colspan="8" class="bo" style="text-align:center;">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="8" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

	$pdf->writeHTML($txt, true, false, true, false, '');
	ob_end_clean();

    $pdf->Output('Total_Royalty_Capital_History.pdf', 'I');
	
	}

	public function get_total_royalty()
	{
		$data['title'] = "Total Royalty";
		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income   = 0;$level9_income = 0;$level10_income = 0;
		$data['setting2']   = $this->setting_model->get_general_settings();

	    $setting      	= $this->setting_model->get_general_settings();
	    $team_capital_aum =0;$team_count = 0;$team_self_capital = 0;
		$data['active_alert'] = $this->user_model->get_active_alert();
 		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
 		$data['team_income_level2']=0;$data['team_income_level3']=0;$data['team_income_level4'] =0;$data['team_income_level5']=0;$data['team_income_level6']=0;$data['team_income_level7']=0;$data['team_income_level8']=0;$data['team_income_level9']=0;$data['team_income_level10']=0;
		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;


				    $data['level1_capital']+=$row['capital_aum']; 
				    array_push($l1_arr,$row['id']);
				    
				}
					

			}
			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);
				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{


							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level2_capital']+=$row['capital_aum']; 
	 						   $team_self_capital+= $row['self_capital'];;
								$team_count++;
								array_push($l2_arr,$row['id']);
							}
							
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						$data['level_2'] 		= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 

 					}
			}	

	}

			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level3'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
								

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
							}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level4_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

						       $team_count++;$team_self_capital++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

							 	$team_count++;$team_self_capital++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
							    array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;$team_self_capital++;

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								 $team_count++;$team_self_capital++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);


							
							 $team_count++;$team_self_capital++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])==33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  

			}

		}


		}

		/* SIP TEAM INCOME START */
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
		$data['active_alert'] = $this->user_model->get_active_alert();
	   $setting =  $this->setting_model->get_general_settings();
	   $data['setting2'] =  $this->setting_model->get_general_settings();
		$team_count =0;
		$sip_level1_income =0;$sip_level2_income =0;$sip_level3_income =0;$sip_level4_income =0;$sip_level5_income =0;$sip_level6_income =0;
		$sip_level7_income =0;$sip_level8_income =0;$sip_level9_income =0;$sip_level10_income =0;$team_sip=0;
		if($data['user']['sip_balance']>='10000')
		{
			$capital_aum_date = $data['user']['final_sip_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			$data['sip_level1']	= $this->user_model->get_my_levels($this->session->userdata('admin_id'));
			foreach($data['sip_level1'] as $row)
			{
				if(!empty($row['sip_balance']))
				{
					$per              	  = ceil($setting['level1_incentive'])/100;
					$sip_level1_income   += $row['sip_balance'] * $per;
				}
					

			}
			$data['sip_1'] 	= $sip_level1_income;
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=3)
					{
					foreach($data['sip_level1'] as $row)
					{

					$sip_level_2	= $this->user_model->get_my_levels($row['id']);
					if(!empty($sip_level_2))
					{
						foreach($sip_level_2 as $row)
						{
							if(!empty($row['sip_balance']))
							{
								$per          = ceil($setting['level2_incentive'])/100;
				
								$team_sip+= $row['sip_balance'];
 					
						   	$sip_level2_income+= $row['sip_balance'] * $per;
						   	$team_sip+= $row['capital_aum'];
							}
							$team_count++;
						
						}
						$data['sip_2'] = $sip_level1_income + $sip_level2_income;

				}	
			}

			}

		}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=5)
					{
							if(!empty($sip_level_2))
						{
							foreach($sip_level_2 as $row)
							{

								$sip_level_3	= $this->user_model->get_my_levels($row['id']);
							if(!empty($sip_level_3))
							{

							$per          = ceil($setting['level3_incentive'])/100;
				
							foreach($sip_level_3 as $row)
							{
								
								if(!empty($row['sip_balance']))
								{

								   $sip_level3_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								}
								$team_count++;
						
						}
							$data['sip_3'] = $sip_level1_income + $sip_level2_income + $sip_level3_income;
						}
					}	
				}

			}

			}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=9)
					{
							if(!empty($sip_level_3))
						{
							foreach($sip_level_3 as $row)
							{
								$sip_level_4	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_4))
						{

						$per    = ceil($setting['level4_incentive'])/100;
				

 						foreach($sip_level_4 as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level4_income+= $row['sip_balance'] * $per;
							   $team_sip+= $row['capital_aum'];
							}
							$team_count++;
							
						}
					$data['sip_4'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income;
			
				}
			}
		}
		}
	}
					
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=13)
					{
							if(!empty($sip_level_4))
						{
							foreach($sip_level_4 as $row)
							{

						$sip_level_5	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_5))
						{

 						foreach($sip_level_5 as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level5_income+= $row['sip_balance'] * $per;
						   $team_sip+= $row['capital_aum'];
						}
						$team_count++;
						
						}
						$data['sip_5'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income ;
						}
					}
				}
					
			}
			
			}

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=17)
					{
							if(!empty($sip_level_5))
						{
							foreach($sip_level_5 as $row)
							{
									$sip_level_6	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_6))
								{

									$per    = ceil($setting['level6_incentive'])/100;
								
					 					foreach($sip_level_6 as $row)
										{
											if(!empty($row['sip_balance']))
											{

											   $sip_level6_income+= $row['sip_balance'] * $per;
											   $team_sip+= $row['capital_aum'];
											}
											$team_count++;
											
										}
										$data['sip_6'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income;
								}

							}
						}
					}
				}

			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=21)
					{
							if(!empty($sip_level_6))
						{
							foreach($sip_level_6 as $row)
							{
								$sip_level_7	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_7))
								{

									$per    = ceil($setting['level7_incentive'])/100;
									
 								foreach($sip_level_7 as $row)
								{
									if(!empty($row['sip_balance']))
									{

								   $sip_level7_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
									}
								$team_count++;
						
									}
									$data['sip_7'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income;
							}

							}
						}
					}
				}
			

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=24)
					{
							if(!empty($sip_level_7))
						{
							foreach($sip_level_7 as $row)
							{
									$sip_level_8	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_8))
								{
									
											$per    = ceil($setting['level8_incentive'])/100;
										$data['sip_level8']	= $this->user_model->get_my_levels($row['id']);
						 					foreach($data['sip_level8'] as $row)
											{
												if(!empty($row['sip_balance']))
												{

												   $sip_level8_income+= $row['sip_balance'] * $per;
												   $team_sip+= $row['capital_aum'];
												}
												$team_count++;
												
											}
											$data['sip_8'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income;
									}
									
								
								}
							}
						}
					}
				


			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=29)
					{
							if(!empty($sip_level_8))
						{
							foreach($sip_level_8 as $row)
							{
								$sip_level_9	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_9))
						{

								$per    = ceil($setting['level9_incentive'])/100;
								$data['sip_level9']	= $this->user_model->get_my_levels($row['id']);
							foreach($data['sip_level9'] as $row)
							{
								if(!empty($row['sip_balance']))
								{

								   $sip_level9_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								   $team_count++;
								}
								
							}
								
								
							}
						$data['sip_9'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income;
					
				}
			}
		}
	}
			
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=33)
					{
							if(!empty($sip_level_9))
						{
							foreach($sip_level_9 as $row)
							{
								$sip_level_10	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_10))
							{

							$per    = ceil($setting['level10_incentive'])/100;
 						foreach($sip_level_10 as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level10_income+= $row['sip_balance'] * $per;
						   $team_sip+= $row['capital_aum'];
						}
						$team_count++;
						
					}
					$data['sip_10'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income + $sip_level10_income;
					$data['sip_team_income'] = $data['sip_10'];
					}

							}
						}
					}
			}
			
}
}

			if(!empty($sip_level1_income) && empty($sip_level2_income))
			{
				$data['sip_team_income'] = $sip_level1_income;

			}else if(!empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level2_income + $sip_level1_income;

			}else if(!empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level3_income + $sip_level2_income+ $sip_level1_income;

			}
			else if(!empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;

			}else if(!empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
						{
					$data['sip_team_income'] = $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;
;

			}else if(!empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;
;
			}else if(!empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;
			}else if(!empty($sip_level8_income) && !empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))

			{
					$data['sip_team_income'] = $sip_level8_income + $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;

			}else if(!empty($sip_level9_income) && !empty($sip_level8_income) && !empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))

			{
					$data['sip_team_income'] = $sip_level9_income + $sip_level8_income + $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income + $sip_level1_income;

			}else if(!empty($sip_level10_income) && !empty($sip_level9_income) && !empty($sip_level8_income) && !empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))

			{
					$data['sip_team_income'] = $sip_level10_income + $sip_level9_income + $sip_level8_income + $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income + $sip_level1_income;

			}


		$data['my_team'] = $this->user_model->get_all_user_detail($user_id);
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/get_total_royalty', $data);
		$this->load->view('admin/includes/_footer');
	
	}

	public function download_total_royalty()
	{
		$data['level1_capital']=0;
		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
	    $setting  =  $this->setting_model->get_general_settings();

		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				$level1_income=0;
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
                  	
                   $date = date('Y-m-d',strtotime($row['final_cap_date']));
           		  $date = date('Y-m-d', strtotime($date . ' +1 day'));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30 && $interval>0)
							{
				    		$level1_income = $level1_income/30;
				    		$level1_income =  round($level1_income * $interval);
							}

							if($interval>30)
							{
				    		$level1_income = $level1_income/30;
				    		$level1_income =  round($level1_income * $interval);
							}
				    	array_push($l1_arr,$row['id']);
				    
				}
					

			}
			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);
				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row1)
 						{
 							if(!empty($row1['capital_aum']))
							{


							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row1['capital_aum'] * $per;
							   $team_capital_aum += $row1['capital_aum'];
							    $data['level2_capital']+=$row1['capital_aum']; 
	 						   $team_self_capital+= $row1['self_capital'];
                              	$date   = date('Y-m-d',strtotime($row1['final_cap_date']));
                               $date = date('Y-m-d', strtotime($date . ' +1 day'));

                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                             if($interval<30 && $interval>0)
							{
				    		$level2_income = $level2_income/30;
				    		$level2_income =  round($level2_income * $interval);
							}

							 if($interval>30)
							{
				    			$level2_income = $level2_income/30;
				    			$level2_income =  round($level2_income * $interval);
							}
								$team_count++;
								array_push($l2_arr,$row1['id']);
							}
							
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						$data['level_2'] 		= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 

 					}
			}	

	}

			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level2'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								 $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
							   $date   = date('Y-m-d',strtotime($row['final_cap_date']));
							   $date = date('Y-m-d', strtotime($date . ' +1 day'));

                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    		$level3_income = $level3_income/30;
				    		$level3_income =  round($level3_income * $interval);
							}

							 if($interval>30)
							{
				    		$level3_income = $level3_income/30;
				    		$level3_income =  round($level3_income * $interval);
							}

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
						}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level4_incentive'])/100;
								$level4_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								$date   = date('Y-m-d',strtotime($row['final_cap_date']));
								 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  round($level4_income * $interval);
							}

							if($interval>30)
							{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  round($level4_income * $interval);
							}
						       $team_count++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								$date   = date('Y-m-d',strtotime($row['final_cap_date']));
					 			$date = date('Y-m-d', strtotime($date . ' +1 day'));

                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  round($level5_income * $interval);
							}

							if($interval>30)
							{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  round($level5_income * $interval);
							}
							 	$team_count++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];
                              
                              $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                              $date = date('Y-m-d', strtotime($date . ' +1 day'));

                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  round($level6_income * $interval);
							}

							  if($interval>30)
							{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  round($level6_income * $interval);
							}
							array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;
                          
                           	   $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                           	   $date = date('Y-m-d', strtotime($date . ' +1 day'));

                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  round($level7_income * $interval);
							}

							if($interval>30)
							{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  round($level7_income * $interval);
							}

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level8_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

                              $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                              $date = date('Y-m-d', strtotime($date . ' +1 day'));

                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  round($level8_income * $interval);
							}

							 if($interval>30)
							{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  round($level8_income * $interval);
							}
								 $team_count++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level9_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);
							  $date   = date('Y-m-d',strtotime($row['final_cap_date']));
							  $date = date('Y-m-d', strtotime($date . ' +1 day'));

                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  round($level9_income * $interval);
							}

							 if($interval>30)
							{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  round($level9_income * $interval);
							}

							
							 $team_count++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])==33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);
								 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
								 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30 && $interval>0)
							{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  round($level10_income * $interval);
							}

							if($interval>30)
							{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  round($level10_income * $interval);
							}
				

							}
							 $team_count++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  

			}

		}


		}

		$sip_level1_income=0;$sip_level2_income=0;$sip_level3_income=0;
		$sip_level4_income=0;$sip_level5_income=0;$sip_level6_income=0;
		$sip_level7_income=0;$sip_level8_income=0;$sip_level9_income=0;
		$sip_level10_income=0;$team_count=0;

		if($data['user']['sip_balance']>='10000')
		{
			$capital_aum_date = $data['user']['final_sip_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			$data['sip_level1']	= $this->user_model->get_my_levels($this->session->userdata('admin_id'));
			foreach($data['sip_level1'] as $row)
			{
				if(!empty($row['sip_balance']))
				{
					$per              	  = ceil($setting['level1_incentive'])/100;
					$sip_level1_income   += $row['sip_balance'] * $per;

					$date   = date('Y-m-d',strtotime($row['final_sip_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
       
                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");

                        if($interval<30  && $interval>0)
							{
				    			$sip_level1_income = $sip_level1_income/30;
				    			$sip_level1_income =  round($sip_level1_income * $interval);
							}

							if($interval>30)
							{
				    			$sip_level1_income = $sip_level1_income/30;
				    			$sip_level1_income =  round($sip_level1_income * $interval);
							}  

				}
					

			}
			$data['sip_1'] 	= $sip_level1_income;
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=3)
					{
					foreach($data['sip_level1'] as $row)
					{

					$sip_level_2	= $this->user_model->get_my_levels($row['id']);
					if(!empty($sip_level_2))
					{
						foreach($sip_level_2 as $row)
						{
							if(!empty($row['sip_balance']))
							{
								$per          = ceil($setting['level2_incentive'])/100;
				
								$team_sip+= $row['sip_balance'];
 							
						   	$sip_level2_income+= $row['sip_balance'] * $per;

						   	$date   = date('Y-m-d',strtotime($row['final_sip_date']));
						   	$date = date('Y-m-d', strtotime($date . ' +1 day'));

                            $date1 =  new DateTime($date);
                            $date2 = new DateTime(date('Y-m-d'));
                            $interval = $date1->diff($date2)->format("%a");

                        if($interval<30 && $interval>0)
							{
				    			$sip_level2_income = $sip_level2_income/30;
				    			$sip_level2_income =  round($sip_level2_income * $interval);
							}

							if($interval>30)
							{
				    			$sip_level2_income = $sip_level2_income/30;
				    			$sip_level2_income =  round($sip_level2_income * $interval);
							}

						   	$team_sip+= $row['capital_aum'];
							}
							$team_count++;
						
						}
						$data['sip_2'] = $sip_level1_income + $sip_level2_income;

				}	
			}

			}

		}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=5)
					{
							if(!empty($sip_level_2))
						{
							foreach($sip_level_2 as $row)
							{

								$sip_level_3	= $this->user_model->get_my_levels($row['id']);
							if(!empty($sip_level_3))
							{

							$per          = ceil($setting['level3_incentive'])/100;
				
							foreach($sip_level_3 as $row)
							{
								
								if(!empty($row['sip_balance']))
								{

								   $sip_level3_income+= $row['sip_balance'] * $per;

								   $date   = date('Y-m-d',strtotime($row['final_sip_date']));
						   $date = date('Y-m-d', strtotime($date . ' +1 day'));
	   
                            $date1 =  new DateTime($date);
                            $date2 = new DateTime(date('Y-m-d'));
                            $interval = $date1->diff($date2)->format("%a");

                        if($interval<30 && $interval>0)
							{
				    			$sip_level3_income = $sip_level3_income/30;
				    			$sip_level3_income =  round($sip_level3_income * $interval);
							}

							if($interval>30)
							{
				    			$sip_level3_income = $sip_level3_income/30;
				    			$sip_level3_income =  round($sip_level3_income * $interval);
							}
								   
						     $team_sip+= $row['capital_aum'];
								}
								$team_count++;
						
						}
							$data['sip_3'] = $sip_level1_income + $sip_level2_income + $sip_level3_income;
						}
					}	
				}

			}

			}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=9)
					{
							if(!empty($sip_level_3))
						{
							foreach($sip_level_3 as $row)
							{
								$sip_level_4	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_4))
						{

						$per    = ceil($setting['level4_incentive'])/100;
				

 						foreach($sip_level_4 as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level4_income+= $row['sip_balance'] * $per;
							   $team_sip+= $row['capital_aum'];

							   	$date   = date('Y-m-d',strtotime($row['final_sip_date']));
						   $date = date('Y-m-d', strtotime($date . ' +1 day'));
   	
                            $date1 =  new DateTime($date);
                            $date2 = new DateTime(date('Y-m-d'));
                            $interval = $date1->diff($date2)->format("%a");

                        if($interval<30 && $interval>0)
							{
				    			$sip_level4_income = $sip_level4_income/30;
				    			$sip_level4_income =  round($sip_level4_income * $interval);
							}

							if($interval>30)
							{
				    			$sip_level4_income = $sip_level4_income/30;
				    			$sip_level4_income =  round($sip_level4_income * $interval);
							}

							}
							$team_count++;
							
						}
					$data['sip_4'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income;
			
				}
			}
		}
		}
	}
					
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=13)
					{
							if(!empty($sip_level_4))
						{
							foreach($sip_level_4 as $row)
							{

						$sip_level_5	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_5))
						{

 						foreach($sip_level_5 as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level5_income+= $row['sip_balance'] * $per;
						   $date   = date('Y-m-d',strtotime($row['final_sip_date']));
						    $date = date('Y-m-d', strtotime($date . ' +1 day'));

                            $date1 =  new DateTime($date);
                            $date2 = new DateTime(date('Y-m-d'));
                            $interval = $date1->diff($date2)->format("%a");

                        if($interval<30 && $interval>0)
							{
				    			$sip_level5_income = $sip_level5_income/30;
				    			$sip_level5_income =  round($sip_level5_income * $interval);
							}

							if($interval>30)
							{
				    			$sip_level5_income = $sip_level5_income/30;
				    			$sip_level5_income =  round($sip_level5_income * $interval);
							}


						   $team_sip+= $row['capital_aum'];
						}
						$team_count++;
						
						}
						$data['sip_5'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income ;
						}
					}
				}
					
			}
			
			}

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=17)
					{
							if(!empty($sip_level_5))
						{
							foreach($sip_level_5 as $row)
							{
									$sip_level_6	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_6))
								{

									$per    = ceil($setting['level6_incentive'])/100;
								
					 					foreach($sip_level_6 as $row)
										{
											if(!empty($row['sip_balance']))
											{

											   $sip_level6_income+= $row['sip_balance'] * $per;
							$date   = date('Y-m-d',strtotime($row['final_sip_date']));
							$date = date('Y-m-d', strtotime($date . ' +1 day'));

                            $date1 =  new DateTime($date);
                            $date2 = new DateTime(date('Y-m-d'));
                            $interval = $date1->diff($date2)->format("%a");

                        if($interval<30 && $interval>0)
							{
				    			$sip_level6_income = $sip_level6_income/30;
				    			$sip_level6_income =  round($sip_level6_income * $interval);
							}

							if($interval>30)
							{
				    			$sip_level6_income = $sip_level6_income/30;
				    			$sip_level6_income =  round($sip_level6_income * $interval);
							}


											   $team_sip+= $row['capital_aum'];
											}
											$team_count++;
											
										}
										$data['sip_6'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income;
								}

							}
						}
					}
				}

			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=21)
					{
							if(!empty($sip_level_6))
						{
							foreach($sip_level_6 as $row)
							{
								$sip_level_7	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_7))
								{

									$per    = ceil($setting['level7_incentive'])/100;
									
 								foreach($sip_level_7 as $row)
								{
									if(!empty($row['sip_balance']))
									{

								   $sip_level7_income+= $row['sip_balance'] * $per;
								   $date   = date('Y-m-d',strtotime($row['final_sip_date']));
							 $date = date('Y-m-d', strtotime($date . ' +1 day'));
	   
                            $date1 =  new DateTime($date);
                            $date2 = new DateTime(date('Y-m-d'));
                            $interval = $date1->diff($date2)->format("%a");

                        if($interval<30 && $interval>0)
							{
				    			$sip_level7_income = $sip_level7_income/30;
				    			$sip_level7_income =  round($sip_level7_income * $interval);
							}

							if($interval>30)
							{
				    			$sip_level7_income = $sip_level7_income/30;
				    			$sip_level7_income =  round($sip_level7_income * $interval);
							}


								   $team_sip+= $row['capital_aum'];
									}
								$team_count++;
						
									}
									$data['sip_7'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income;
							}

							}
						}
					}
				}
			

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=24)
					{
							if(!empty($sip_level_7))
						{
							foreach($sip_level_7 as $row)
							{
									$sip_level_8	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_8))
								{
									
											$per    = ceil($setting['level8_incentive'])/100;
										$data['sip_level8']	= $this->user_model->get_my_levels($row['id']);
						 					foreach($data['sip_level8'] as $row)
											{
												if(!empty($row['sip_balance']))
												{

												   $sip_level8_income+= $row['sip_balance'] * $per;
							$date   = date('Y-m-d',strtotime($row['final_sip_date']));
							 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                            $date1 =  new DateTime($date);
                            $date2 = new DateTime(date('Y-m-d'));
                            $interval = $date1->diff($date2)->format("%a");

                        if($interval<30 && $interval>0)
							{
				    			$sip_level8_income = $sip_level8_income/30;
				    			$sip_level8_income =  round($sip_level8_income * $interval);
							}

							if($interval>30)
							{
				    			$sip_level8_income = $sip_level8_income/30;
				    			$sip_level8_income =  round($sip_level8_income * $interval);
							}			
												   $team_sip+= $row['capital_aum'];
												}
												$team_count++;
												
											}
											$data['sip_8'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income;
									}
									
								
								}
							}
						}
					}
				


			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=29)
					{
							if(!empty($sip_level_8))
						{
							foreach($sip_level_8 as $row)
							{
								$sip_level_9	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_9))
						{

								$per    = ceil($setting['level9_incentive'])/100;
								$data['sip_level9']	= $this->user_model->get_my_levels($row['id']);
							foreach($data['sip_level9'] as $row)
							{
								if(!empty($row['sip_balance']))
								{

								   $sip_level9_income+= $row['sip_balance'] * $per;
							$date   = date('Y-m-d',strtotime($row['final_sip_date']));
						    $date = date('Y-m-d', strtotime($date . ' +1 day'));
	
                            $date1 =  new DateTime($date);
                            $date2 = new DateTime(date('Y-m-d'));
                            $interval = $date1->diff($date2)->format("%a");

                        if($interval<30 && $interval>0)
							{
				    			$sip_level9_income = $sip_level9_income/30;
				    			$sip_level9_income =  round($sip_level9_income * $interval);
							}

							if($interval>30)
							{
				    			$sip_level9_income = $sip_level9_income/30;
				    			$sip_level9_income =  round($sip_level9_income * $interval);
							}			
							

								   $team_sip+= $row['capital_aum'];
								   $team_count++;
								}
								
							}
								
								
							}
						$data['sip_9'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income;
					
				}
			}
		}
	}
			
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=33)
					{
							if(!empty($sip_level_9))
						{
							foreach($sip_level_9 as $row)
							{
								$sip_level_10	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_10))
							{

							$per    = ceil($setting['level10_incentive'])/100;
 						foreach($sip_level_10 as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level10_income+= $row['sip_balance'] * $per;
						   $date   = date('Y-m-d',strtotime($row['final_sip_date']));
						   $date = date('Y-m-d', strtotime($date . ' +1 day'));

                            $date1 =  new DateTime($date);
                            $date2 = new DateTime(date('Y-m-d'));
                            $interval = $date1->diff($date2)->format("%a");

                        if($interval<30 && $interval>0)
							{
				    			$sip_level10_income = $sip_level10_income/30;
				    			$sip_level10_income =  round($sip_level10_income * $interval);
							}

							if($interval>30)
							{
				    			$sip_level10_income = $sip_level10_income/30;
				    			$sip_level10_income =  round($sip_level10_income * $interval);
							}

						   $team_sip+= $row['capital_aum'];
						}
						$team_count++;
						
					}
					$data['sip_10'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income + $sip_level10_income;
					$data['sip_team_income'] = $data['sip_10'];
					}

							}
						}
					}
			}
			
}
}



		$this->load->library('MYPDF');
	   $settings =  $this->setting_model->get_general_settings();
	   $data['setting'] =  $this->setting_model->get_general_settings();

		$my_team = $this->user_model->get_all_inactive($this->session->userdata('admin_id'));


		// set some text to print
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Total Active Account');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print

		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="20%" style="text-align:center">Date</th>
			<th  width="20%" style="text-align:center">Account No</th><th  width="20%" style="text-align:center">Username</th>
			<th  width="20%" style="text-align:center">SIP/Capital AUM</th>
			<th  width="20%" style="text-align:center">Team Income</th>
			</tr><tbody>';
			$capital_aum=0;$id=1;
			if(!empty($data['team_income_level1']))
			{

				foreach($data['team_income_level1'] as $row)
				{
					if(!empty($row['final_cap_date'])){
             
					$per              	  = ceil($setting['level1_incentive'])/100;

					 $level1_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level1_income = $level1_income/30;
				    			$level1_income =  bcdiv($level1_income * $interval,1,2);
					}
					if($interval>30)
					{
				    			$level1_income = $level1_income/30;
				    			$level1_income =  bcdiv($level1_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['capital_aum'].'</td>
					<td style="text-align:center">' .$level1_income.'</td>
					</tr>';
					$capital_aum+=$level1_income;

				$id++;
				}
			}
		}
			if(!empty($data['team_income_level2']))
			{

				foreach($data['team_income_level2'] as $row)
				{
					if(!empty($row['final_cap_date'])){
             
					$per              	  = ceil($setting['level2_incentive'])/100;

					 $level2_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level2_income = $level2_income/30;
				    			$level2_income =  bcdiv($level2_income * $interval,1,2);
					}

					 if($interval>30)
					{
				    			$level2_income = $level2_income/30;
				    			$level2_income =  bcdiv($level2_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['capital_aum'].'</td>
					<td style="text-align:center">' .$level2_income.'</td>
					</tr>';
					$capital_aum+=$level2_income;

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level3']))
			{

				foreach($data['team_income_level3'] as $row)
				{
					if(!empty($row['final_cap_date'])){
             
					$per       = ceil($setting['level3_incentive'])/100;

					$level3_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level3_income = $level3_income/30;
				    			$level3_income =  bcdiv($level3_income * $interval,1,2);
					}

					 if($interval>30)
					{
				    			$level3_income = $level3_income/30;
				    			$level3_income =  bcdiv($level3_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['capital_aum'].'</td>
					<td style="text-align:center">' .$level3_income.'</td>
					</tr>';
					$capital_aum+=$level3_income;

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level4']))
			{

				foreach($data['team_income_level4'] as $row)
				{
					if(!empty($row['final_cap_date'])){
             
					$per       = ceil($setting['level4_incentive'])/100;

					$level4_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30)
					{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  bcdiv($level4_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  bcdiv($level4_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['capital_aum'].'</td>
					<td style="text-align:center">' .$level4_income.'</td>
					</tr>';
					$capital_aum+=$level4_income;

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level5']))
			{

				foreach($data['team_income_level5'] as $row)
				{
					if(!empty($row['final_cap_date'])){
             
					$per       = ceil($setting['level5_incentive'])/100;

					 $level5_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  bcdiv($level5_income * $interval,1,2);
					}

					 if($interval>30)
					{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  bcdiv($level5_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['capital_aum'].'</td>
					<td style="text-align:center">' .$level5_income.'</td>
					</tr>';
					$capital_aum+=$level5_income;

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level6']))
			{

				foreach($data['team_income_level6'] as $row)
				{
					if(!empty($row['final_cap_date'])){
             
					$per       = ceil($setting['level6_incentive'])/100;

					 $level6_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  bcdiv($level6_income * $interval,1,2);
					}

					 if($interval>30)
					{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  bcdiv($level6_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['capital_aum'].'</td>
					<td style="text-align:center">' .$level6_income.'</td>
					</tr>';
					$capital_aum+=$level6_income;

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level7']))
			{

				foreach($data['team_income_level7'] as $row)
				{
					if(!empty($row['final_cap_date'])){
             
					$per       = ceil($setting['level7_incentive'])/100;

					$level7_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  bcdiv($level7_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  bcdiv($level7_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['capital_aum'].'</td>
					<td style="text-align:center">' .$level7_income.'</td>
					</tr>';
					$capital_aum+=$level7_income;

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level8']))
			{

				foreach($data['team_income_level8'] as $row)
				{
					if(!empty($row['final_cap_date'])){
             
					$per       = ceil($setting['level8_incentive'])/100;

					 $level8_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  bcdiv($level8_income * $interval,1,2);
					}

					 if($interval>30)
					{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  bcdiv($level8_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['capital_aum'].'</td>
					<td style="text-align:center">' .$level8_income.'</td>
					</tr>';
					$capital_aum+=$level8_income;

				$id++;
				}
			}
			}
			if(!empty($data['team_income_level9']))
			{

				foreach($data['team_income_level9'] as $row)
				{
					if(!empty($row['final_cap_date'])){
             
					$per       = ceil($setting['level9_incentive'])/100;

					 $level9_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  bcdiv($level9_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  bcdiv($level9_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['capital_aum'].'</td>
					<td style="text-align:center">' .$level9_income.'</td>
					</tr>';
					$capital_aum+=$level9_income;

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level10']))
			{

				foreach($data['team_income_level10'] as $row)
				{
					if(!empty($row['final_cap_date'])){
             
					$per       = ceil($setting['level10_incentive'])/100;

					 $level10_income = $row['capital_aum'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                 $date   = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  bcdiv($level10_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  bcdiv($level10_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['capital_aum'].'</td>
					<td style="text-align:center">' .$level10_income.'</td>
					</tr>';
					$capital_aum+=$level10_income;

				$id++;
				}
			}
		}



			if(!empty($data['sip_level1']))
			{

				foreach($data['sip_level1'] as $row)
				{
					if(!empty($row['sip_balance']))
					{
						if(!empty($row['final_sip_date'])){
             
					
				$per              	  = ceil($setting['level1_incentive'])/100;
					
				 $level1_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                 $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0) 
					{
				    			$level1_income = $level1_income/30;
				    			$level1_income =  bcdiv($level1_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level1_income = $level1_income/30;
				    			$level1_income =  bcdiv($level1_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level1_income.'</td>
					</tr>';
					$capital_aum+=$level1_income;
				$id++;
				}
			}
				}
			}
			if(!empty($sip_level_2))
			{

				foreach($sip_level_2 as $row)
				{
					if(!empty($row['sip_balance']))
					{
					if(!empty($row['final_sip_date'])){

					
				 $per              	  = ceil($setting['level2_incentive'])/100;
					
			     $level2_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1  =  new DateTime($date);
                 $date2  = new DateTime(date('Y-m-d'));
                 $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level2_income = $level2_income/30;
				    			$level2_income =  bcdiv($level2_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level2_income = $level2_income/30;
				    			$level2_income =  bcdiv($level2_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level2_income.'</td>
					</tr>';
					$capital_aum+=$level2_income;

				$id++;
				}
				}
			}
			}

			if(!empty($sip_level_3))
			{

				foreach($sip_level_3 as $row)
				{
					if(!empty($row['sip_balance']))
					{
					if(!empty($row['final_sip_date'])){


					
					$per              	  = ceil($setting['level3_incentive'])/100;
					$level3_income = $row['sip_balance'] * $per;
                                                   
                  $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                  $date = date('Y-m-d', strtotime($date . ' +1 day'));

                  $date1 =  new DateTime($date);
                  $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level3_income = $level3_income/30;
				    			$level3_income =  bcdiv($level3_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level3_income = $level3_income/30;
				    			$level3_income =  bcdiv($level3_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level3_income.'</td>
					</tr>';
					$capital_aum+=$level3_income;

				$id++;
				}
				}
			}
			}

			if(!empty($sip_level_4))
			{

				foreach($sip_level_4 as $row)
				{
					if(!empty($row['sip_balance']))
					{
					if(!empty($row['final_sip_date'])){

					
				  $per          = ceil($setting['level4_incentive'])/100;

				 $level4_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1  =  new DateTime($date);
                 $date2  = new DateTime(date('Y-m-d'));
                 $interval = $date1->diff($date2)->format("%a");  

                    if($interval<30 && $interval>0)
					{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  bcdiv($level4_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  bcdiv($level4_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level4_income.'</td>
					</tr>';
					$capital_aum+=$level4_income;

				$id++;
				}
			}
				}
			}

			if(!empty($sip_level_5))
			{

				foreach($sip_level_5 as $row)
				{
				if(!empty($row['sip_balance']))
					{
				if(!empty($row['final_sip_date'])){

					
				 $level5_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  bcdiv($level5_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  bcdiv($level5_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level5_income.'</td>
					</tr>';
					$capital_aum+=$level5_income;

				$id++;
				}
				}
			}
			}

			if(!empty($sip_level_6))
			{

				foreach($sip_level_6 as $row)
				{
					if(!empty($row['sip_balance']))
					{
				if(!empty($row['final_sip_date'])){

					
					 $level6_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                 $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  bcdiv($level6_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  bcdiv($level6_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level6_income.'</td>
					</tr>';
					$capital_aum+=$level6_income;

				$id++;
				}
			}
				}
			}

			if(!empty($sip_level_7))
			{

				foreach($sip_level_7 as $row)
				{
					if(!empty($row['sip_balance']))
					{
					if(!empty($row['final_sip_date'])){

					
					 $level7_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30)
					{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  bcdiv($level7_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  bcdiv($level7_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level7_income.'</td>
					</tr>';
					$capital_aum+=$level7_income;

				$id++;
				}
			}
				}
			}

			if(!empty($sip_level_8))
			{

				foreach($sip_level_8 as $row)
				{
					if(!empty($row['sip_balance']))
					{
					if(!empty($row['final_sip_date'])){

					
					 $level8_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1  =  new DateTime($date);
                 $date2  =  new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30)
					{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  bcdiv($level8_income * $interval,1,2);
					}

					 if($interval>30)
					{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  bcdiv($level8_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level8_income.'</td>
					</tr>';
					$capital_aum+=$level8_income;

				$id++;
				}
			}
				}
			}
			if(!empty($sip_level_9))
			{

				foreach($sip_level_9 as $row)
				{
					if(!empty($row['sip_balance']))
					{
					if(!empty($row['final_sip_date'])){

					
					 $level9_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  bcdiv($level9_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  bcdiv($level9_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level9_income.'</td>
					</tr>';
					$capital_aum+=$level9_income;

				$id++;
				}
			}
				}
			}

			if(!empty($sip_level_10))
			{

				foreach($sip_level_10 as $row)
				{
					if(!empty($row['sip_balance']))
					{
					if(!empty($row['final_sip_date'])){

					
					 $level10_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date   = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1  =  new DateTime($date);
                 $date2  = new DateTime(date('Y-m-d'));
                 $interval = $date1->diff($date2)->format("%a");  

                    if($interval<30 && $interval>0)
					{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  bcdiv($level10_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  bcdiv($level10_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level10_income.'</td>
					</tr>';
					$capital_aum+=$level10_income;
				$id++;
				}
			}
			}
			}


	$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="4" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="4" style="text-align:center">'.$words.'</th></tr><tr class="bo"><td colspan="6" class="bo">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="6" class="bo" style="text-align:center">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	$txt.='</body></html>';
	 

	$pdf->writeHTML($txt, true, false, true, false, '');
	ob_end_clean();

    $pdf->Output('total_royalty.pdf', 'I');
		
	}


	public function get_royalty_sip_income()
	{
		/* SIP TEAM INCOME START */
		$data['title'] = "Royalty SIP Income";
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
		$data['active_alert'] = $this->user_model->get_active_alert();
	   $setting =  $this->setting_model->get_general_settings();
	   $data['setting2'] =  $this->setting_model->get_general_settings();
		$team_count =0;
		$sip_level1_income =0;$sip_level2_income =0;$sip_level3_income =0;$sip_level4_income =0;$sip_level5_income =0;$sip_level6_income =0;
		$sip_level7_income =0;$sip_level8_income =0;$sip_level9_income =0;$sip_level10_income =0;$team_sip=0;
		if($data['user']['sip_balance']>='10000')
		{
			$capital_aum_date = $data['user']['final_sip_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			$data['sip_level1']	= $this->user_model->get_my_levels($this->session->userdata('admin_id'));
			foreach($data['sip_level1'] as $row)
			{
				if(!empty($row['sip_balance']))
				{
					$per              	  = ceil($setting['level1_incentive'])/100;
					$sip_level1_income   += $row['sip_balance'] * $per;
				}
					

			}
              $data['level_2'] = array();
			$data['sip_1'] 	= $sip_level1_income;
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=3)
					{
					foreach($data['sip_level1'] as $row)
					{
	
					$data['sip_level_2']	= $this->user_model->get_my_levels($row['id']);
                      
					if(!empty($data['sip_level_2']))
					{
						foreach($data['sip_level_2'] as $row)
						{
							if(!empty($row['sip_balance']))
							{
								$per          = ceil($setting['level2_incentive'])/100;
				
								$team_sip+= $row['sip_balance'];
 					
						   	$sip_level2_income+= $row['sip_balance'] * $per;
						   	$team_sip+= $row['sip_balance'];
                              array_push($data['level_2'],$row['id']);
							}
							$team_count++;
						
						}
						$data['sip_2'] = $sip_level1_income + $sip_level2_income;

				}	
			}

			}

		}
			
              $data['level_3'] = array();
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=5)
					{
							if(!empty($data['sip_level_2']))
						{
							foreach($data['sip_level_2'] as $row)
							{

								$data['sip_level_3']	= $this->user_model->get_my_levels($row['id']);
							if(!empty($data['sip_level_3']))
							{

							$per          = ceil($setting['level3_incentive'])/100;
				
							foreach($data['sip_level_3'] as $row)
							{
								
								if(!empty($row['sip_balance']))
								{

								   $sip_level3_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								}
								$team_count++;
                              array_push( $data['level_3'],$row['id']);
						
						}
							$data['sip_3'] = $sip_level1_income + $sip_level2_income + $sip_level3_income;
						}
					}	
				}

			}

			}
			
                $data['level_4'] = array();
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=9)
					{
							if(!empty($sip_level_3))
						{
							foreach($sip_level_3 as $row)
							{
								$data['sip_level_4']	= $this->user_model->get_my_levels($row['id']);
						if(!empty($data['sip_level_4']))
						{

						$per    = ceil($setting['level4_incentive'])/100;
				

 						foreach($data['sip_level_4'] as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level4_income+= $row['sip_balance'] * $per;
							   $team_sip+= $row['capital_aum'];
							}
                           array_push( $data['level_4'],$row['id']);
							$team_count++;
							
						}
					$data['sip_4'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income;
			
				}
			}
		}
		}
	}
					
			$data['level_5'] = array();
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=13)
					{
							if(!empty($data['sip_level_4']))
						{
							foreach($data['sip_level_4'] as $row)
							{

						$data['sip_level_5']	= $this->user_model->get_my_levels($row['id']);
						if(!empty($data['sip_level_5']))
						{

 						foreach($data['sip_level_5'] as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level5_income+= $row['sip_balance'] * $per;
						   $team_sip+= $row['capital_aum'];
						}
                          array_push( $data['level_5'],$row['id']);
						$team_count++;
						
						}
						$data['sip_5'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income ;
						}
					}
				}
					
			}
			
			}

              $data['level_6'] = array();
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=17)
					{
							if(!empty($data['sip_level_5']))
						{
							foreach($data['sip_level_5'] as $row)
							{
									$data['sip_level_6']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level_6']))
								{

									$per    = ceil($setting['level6_incentive'])/100;
								
					 					foreach($data['sip_level_6'] as $row)
										{
											if(!empty($row['sip_balance']))
											{

											   $sip_level6_income+= $row['sip_balance'] * $per;
											   $team_sip+= $row['capital_aum'];
											}
											$team_count++;
                                          array_push( $data['level_6'],$row['id']);
											
										}
										$data['sip_6'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income;
								}

							}
						}
					}
				}

			 $data['level_7'] = array();
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=21)
					{
							if(!empty($data['sip_level_6']))
						{
							foreach($data['sip_level_6'] as $row)
							{
								$data['sip_level_7']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level_7']))
								{

									$per    = ceil($setting['level7_incentive'])/100;
									
 								foreach($data['sip_level_7'] as $row)
								{
									if(!empty($row['sip_balance']))
									{

								   		$sip_level7_income+= $row['sip_balance'] * $per;
								   		$team_sip+= $row['capital_aum'];
									}
									$team_count++;
						array_push( $data['level_7'],$row['id']);
									}
									$data['sip_7'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income;
							}

							}
						}
					}
				}
			
 $data['level_8'] = array();
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=24)
					{
							if(!empty($data['sip_level_7']))
						{
							foreach($data['sip_level_7'] as $row)
							{
									$data['sip_level_8']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level_8']))
								{
									
											$per    = ceil($setting['level8_incentive'])/100;
										$data['sip_level8']	= $this->user_model->get_my_levels($row['id']);
						 					foreach($data['sip_level_8'] as $row)
											{
												if(!empty($row['sip_balance']))
												{

												   $sip_level8_income+= $row['sip_balance'] * $per;
												   $team_sip+= $row['capital_aum'];
												}
												$team_count++;
									array_push( $data['level_8'],$row['id']);

											}
											$data['sip_8'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income;
									}
									
								
								}
							}
						}
					}
				

 $data['level_9'] = array();

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=29)
					{
							if(!empty($data['sip_level_8']))
						{
							foreach($data['sip_level_8'] as $row)
							{
								$data['sip_level_9']	= $this->user_model->get_my_levels($row['id']);
						if(!empty($data['sip_level_9']))
						{

								$per    = ceil($setting['level9_incentive'])/100;
								$data['sip_level9']	= $this->user_model->get_my_levels($row['id']);
							foreach($data['sip_level9'] as $row){
								if(!empty($row['sip_balance']))
								{

								   $sip_level9_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								   $team_count++;
								}
                             array_push( $data['level_9'],$row['id']);

								
							}
								
								
							}
						$data['sip_9'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income;
					
				}
			}
		}
	}
			
		 $data['level_10'] = array();
	
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=33)
					{
							if(!empty($data['sip_level9']))
						{
							foreach($data['sip_level9'] as $row)
							{
								$data['sip_level10']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level10']))
							{

							$per    = ceil($setting['level10_incentive'])/100;
 						foreach($data['sip_level10'] as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level10_income+= $row['sip_balance'] * $per;
						   $team_sip+= $row['capital_aum'];
						}
						$team_count++;
                       array_push( $data['level_10'],$row['id']);

						
					}
					$data['sip_10'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income + $sip_level10_income;
					$data['sip_team_income'] = $data['sip_10'];
					}

							}
						}
					}
			}
			
}
}

		if(!empty($sip_level1_income) && empty($sip_level2_income))
			{
				$data['sip_team_income'] = $sip_level1_income;

			}else if(!empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level2_income + $sip_level1_income;

			}else if(!empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level3_income + $sip_level2_income+ $sip_level1_income;

			}
			else if(!empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;

			}else if(!empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
						{
					$data['sip_team_income'] = $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;
;

			}else if(!empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;
;
			}else if(!empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;
			}else if(!empty($sip_level8_income) && !empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))

			{
					$data['sip_team_income'] = $sip_level8_income + $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;

			}else if(!empty($sip_level9_income) && !empty($sip_level8_income) && !empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))

			{
					$data['sip_team_income'] = $sip_level9_income + $sip_level8_income + $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income + $sip_level1_income;

			}else if(!empty($sip_level10_income) && !empty($sip_level9_income) && !empty($sip_level8_income) && !empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))

			{
					$data['sip_team_income'] = $sip_level10_income + $sip_level9_income + $sip_level8_income + $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income + $sip_level1_income;

			}


		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/get_royalty_sip_history', $data);
		$this->load->view('admin/includes/_footer');
	

	}



	public function download_royalty_sip_income()
	{
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
		$data['active_alert'] = $this->user_model->get_active_alert();
	   $setting =  $this->setting_model->get_general_settings();
	   $data['setting2'] =  $this->setting_model->get_general_settings();
		$team_count =0;
		$sip_level1_income =0;$sip_level2_income =0;$sip_level3_income =0;$sip_level4_income =0;$sip_level5_income =0;$sip_level6_income =0;
		$sip_level7_income =0;$sip_level8_income =0;$sip_level9_income =0;$sip_level10_income =0;$team_sip=0;
		if($data['user']['sip_balance']>='10000')
		{
			$capital_aum_date = $data['user']['final_sip_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			$data['sip_level1']	= $this->user_model->get_my_levels($this->session->userdata('admin_id'));
			foreach($data['sip_level1'] as $row)
			{
				if(!empty($row['sip_balance']))
				{
					$per              	  = ceil($setting['level1_incentive'])/100;
					$sip_level1_income   += $row['sip_balance'] * $per;
				}
					

			}
			$data['sip_1'] 	= $sip_level1_income;
			if(!empty($data['sip_level1']))
			{
					if(count($data['sip_level1'])>=3)
					{
					foreach($data['sip_level1'] as $row)
					{

					$sip_level_2	= $this->user_model->get_my_levels($row['id']);
					if(!empty($sip_level_2))
					{
						foreach($sip_level_2 as $row)
						{
							if(!empty($row['sip_balance']))
							{
								$per          = ceil($setting['level2_incentive'])/100;
				
								$team_sip+= $row['sip_balance'];
 					
						   	$sip_level2_income+= $row['sip_balance'] * $per;
						   	$team_sip+= $row['capital_aum'];
							}
							$team_count++;
						
						}
						$data['sip_2'] = $sip_level1_income + $sip_level2_income;

				}	
			}

			}

		}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=5)
					{
							if(!empty($sip_level_2))
						{
							foreach($sip_level_2 as $row)
							{

								$sip_level_3	= $this->user_model->get_my_levels($row['id']);
							if(!empty($sip_level_3))
							{

							$per          = ceil($setting['level3_incentive'])/100;
				
							foreach($sip_level_3 as $row)
							{
								
								if(!empty($row['sip_balance']))
								{

								   $sip_level3_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								}
								$team_count++;
						
						}
							$data['sip_3'] = $sip_level1_income + $sip_level2_income + $sip_level3_income;
						}
					}	
				}

			}

			}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=9)
					{
							if(!empty($sip_level_3))
						{
							foreach($sip_level_3 as $row)
							{
								$sip_level_4	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_4))
						{

						$per    = ceil($setting['level4_incentive'])/100;
				

 						foreach($sip_level_4 as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level4_income+= $row['sip_balance'] * $per;
							   $team_sip+= $row['capital_aum'];
							}
							$team_count++;
							
						}
					$data['sip_4'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income;
			
				}
			}
		}
		}
	}
					
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=13)
					{
							if(!empty($sip_level_4))
						{
							foreach($sip_level_4 as $row)
							{

						$sip_level_5	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_5))
						{

 						foreach($sip_level_5 as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level5_income+= $row['sip_balance'] * $per;
						   $team_sip+= $row['capital_aum'];
						}
						$team_count++;
						
						}
						$data['sip_5'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income ;
						}
					}
				}
					
			}
			
			}

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=17)
					{
							if(!empty($sip_level_5))
						{
							foreach($sip_level_5 as $row)
							{
									$sip_level_6	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_6))
								{

									$per    = ceil($setting['level6_incentive'])/100;
								
					 					foreach($sip_level_6 as $row)
										{
											if(!empty($row['sip_balance']))
											{

											   $sip_level6_income+= $row['sip_balance'] * $per;
											   $team_sip+= $row['capital_aum'];
											}
											$team_count++;
											
										}
										$data['sip_6'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income;
								}

							}
						}
					}
				}

			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=21)
					{
							if(!empty($sip_level_6))
						{
							foreach($sip_level_6 as $row)
							{
								$sip_level_7	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_7))
								{

									$per    = ceil($setting['level7_incentive'])/100;
									
 								foreach($sip_level_7 as $row)
								{
									if(!empty($row['sip_balance']))
									{

								   $sip_level7_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
									}
								$team_count++;
						
									}
									$data['sip_7'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income;
							}

							}
						}
					}
				}
			

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=24)
					{
							if(!empty($sip_level_7))
						{
							foreach($sip_level_7 as $row)
							{
									$sip_level_8	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_8))
								{
									
											$per    = ceil($setting['level8_incentive'])/100;
										$data['sip_level8']	= $this->user_model->get_my_levels($row['id']);
						 					foreach($data['sip_level8'] as $row)
											{
												if(!empty($row['sip_balance']))
												{

												   $sip_level8_income+= $row['sip_balance'] * $per;
												   $team_sip+= $row['capital_aum'];
												}
												$team_count++;
												
											}
											$data['sip_8'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income;
									}
									
								
								}
							}
						}
					}
				


			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=29)
					{
							if(!empty($sip_level_8))
						{
							foreach($sip_level_8 as $row)
							{
								$sip_level_9	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_9))
						{

								$per    = ceil($setting['level9_incentive'])/100;
								$data['sip_level9']	= $this->user_model->get_my_levels($row['id']);
							foreach($data['sip_level9'] as $row){
								if(!empty($row['sip_balance']))
								{

								   $sip_level9_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								   $team_count++;
								}
								
							}
								
								
							}
						$data['sip_9'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income;
					
				}
			}
		}
	}
			
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=33)
					{
							if(!empty($sip_level_9))
						{
							foreach($sip_level_9 as $row)
							{
								$sip_level_10	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_10))
							{

							$per    = ceil($setting['level10_incentive'])/100;
 						foreach($sip_level_10 as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level10_income+= $row['sip_balance'] * $per;
						   $team_sip+= $row['capital_aum'];
						}
						$team_count++;
						
					}
					$data['sip_10'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income + $sip_level10_income;
					$data['sip_team_income'] = $data['sip_10'];
					}

							}
						}
					}
			}
			
}
}
		$this->load->library('MYPDF');
	   $settings =  $this->setting_model->get_general_settings();

		$my_team = $this->user_model->get_all_inactive($this->session->userdata('admin_id'));


		// set some text to print
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Total Active Account');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  		array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print

		// set some text to print
		$txt .= '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="20%" style="text-align:center">Date</th>
			<th  width="20%" style="text-align:center">Account No</th><th  width="20%" style="text-align:center">Username</th>
			<th  width="20%" style="text-align:center">SIP AUM</th>
			<th  width="20%" style="text-align:center">Team Income</th>
			</tr><tbody>';
			$capital_aum=0;$id=1;
			if(!empty($data['sip_level1']))
			{

				foreach($data['sip_level1'] as $row)
				{
					if(!empty($row['sip_balance']))
					{
						if(!empty($row['final_sip_date']))
					{

					$per              	  = ceil($setting['level1_incentive'])/100;
					
					 $level1_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level1_income = $level1_income/30;
				    			$level1_income =  bcdiv($level1_income * $interval,1,2);
					}
					 if($interval>30)
					{
				    			$level1_income = $level1_income/30;
				    			$level1_income =  bcdiv($level1_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level1_income.'</td>
					</tr>';
					$capital_aum+=$level1_income;

				$id++;
				}
				}
				}
			}
			if(!empty($$sip_level_2))
			{

				foreach($sip_level_2 as $row)
				{
					if(!empty($row['sip_balance']))
					{
						if(!empty($row['final_sip_date']))
					{
					
					$per              	  = ceil($setting['level2_incentive'])/100;
					
					 $level2_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level2_income = $level2_income/30;
				    			$level2_income =  bcdiv($level2_income * $interval,1,2);
					}
					 if($interval>30)
					{
				    			$level2_income = $level2_income/30;
				    			$level2_income =  bcdiv($level2_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level2_income.'</td>
					</tr>';
					$capital_aum+=$level2_income;

				$id++;
				}
				}
				}
			}

			if(!empty($sip_level_3))
			{

				foreach($sip_level_3 as $row)
				{
					if(!empty($row['sip_balance']))
					{
						if(!empty($row['final_sip_date']))
					{
					
					$per              	  = ceil($setting['level3_incentive'])/100;
					 $level3_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level3_income = $level3_income/30;
				    			$level3_income =  bcdiv($level3_income * $interval,1,2);
					}
					if($interval>30)
					{
				    			$level3_income = $level3_income/30;
				    			$level3_income =  bcdiv($level3_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level3_income.'</td>
					</tr>';
					$capital_aum+=$level3_income;

				$id++;
				}
				}
				}
			}

			if(!empty($sip_level_4))
			{

				foreach($sip_level_4 as $row)
				{
					if(!empty($row['sip_balance']))
					{
						if(!empty($row['final_sip_date']))
					{
					
				  $per              	  = ceil($setting['level4_incentive'])/100;

					 $level4_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  bcdiv($level4_income * $interval,1,2);
					}

					 if($interval>30)
					{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  bcdiv($level4_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level4_income.'</td>
					</tr>';
					$capital_aum+=$level4_income;

				$id++;
				}
				}
			}
		}

			if(!empty($sip_level_5))
			{

				foreach($sip_level_5 as $row)
				{
				if(!empty($row['sip_balance']))
					{
						if(!empty($row['final_sip_date']))
					{
					
				$level5_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  bcdiv($level5_income * $interval,1,2);
					}

					 if($interval>30)
					{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  bcdiv($level5_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level5_income.'</td>
					</tr>';
					$capital_aum+=$level5_income;

				$id++;
				}
			}
				}
			}

			if(!empty($sip_level_6))
			{

				foreach($sip_level_6 as $row)
				{
					if(!empty($row['sip_balance']))
					{
						if(!empty($row['final_sip_date']))
					{
					
					 $level6_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date   = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  bcdiv($level6_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  bcdiv($level6_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level6_income.'</td>
					</tr>';
					$capital_aum+=$level6_income;

				$id++;
				}
			}
				}
			}

			if(!empty($sip_level_7))
			{

				foreach($sip_level_7 as $row)
				{
					if(!empty($row['sip_balance']))
					{
						if(!empty($row['final_sip_date']))
					{
					
					 $level7_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  bcdiv($level7_income * $interval,1,2);
					}

					 if($interval>30)
					{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  bcdiv($level7_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level7_income.'</td>
					</tr>';
					$capital_aum+=$level7_income;

				$id++;
				}
			}
				}
			}

			if(!empty($sip_level_8))
			{

				foreach($sip_level_8 as $row)
				{
					if(!empty($row['sip_balance']))
					{
						if(!empty($row['final_sip_date']))
					{
					
					 $level8_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1  =  new DateTime($date);
                 $date2  =  new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  bcdiv($level8_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  bcdiv($level8_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level8_income.'</td>
					</tr>';
					$capital_aum+=$level8_income;

				$id++;
				}
			}
				}
			}
			if(!empty($sip_level_9))
			{

				foreach($sip_level_9 as $row)
				{
					if(!empty($row['sip_balance']))
					{
						if(!empty($row['final_sip_date']))
					{
					 $level9_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1 =  new DateTime($date);
                 $date2 = new DateTime(date('Y-m-d'));
                  $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30)
					{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  bcdiv($level9_income * $interval,1,2);
					}

					if($interval>30)
					{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  bcdiv($level9_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level9_income.'</td>
					</tr>';
					$capital_aum+=$level9_income;

				$id++;
				}
				}
			}
		}
			if(!empty($sip_level_10))
			{

				foreach($sip_level_10 as $row)
				{
					if(!empty($row['sip_balance']))
					{
					if(!empty($row['final_sip_date']))
					{
					 $level10_income = $row['sip_balance'] * $per;
                                                   
                 $date   = date('Y-m-d',strtotime($row['final_sip_date']));
                 $date = date('Y-m-d', strtotime($date . ' +1 day'));

                 $date1  =  new DateTime($date);
                 $date2  = new DateTime(date('Y-m-d'));
                 $interval = $date1->diff($date2)->format("%a");  

                      if($interval<30 && $interval>0)
					{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  bcdiv($level10_income * $interval,1,2);
					}

					 if($interval>30)
					{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  bcdiv($level10_income * $interval,1,2);
					}
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
					<td style="text-align:center">' .date_time($row['created_at']).'</td>
					<td style="text-align:center">'.$row['account_no'].'</td>
					<td style="text-align:center">' .$row['username'].'</td>
					<td style="text-align:center">' .$row['sip_balance'].'</td>
					<td style="text-align:center">' .$level10_income.'</td>
					</tr>';
					$capital_aum+=$level10_income;
				$id++;
				}
				}
				}
			}


	$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="4" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="4" style="text-align:center">'.$words.'</th></tr><tr class="bo"><td colspan="6" class="bo">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="6" class="bo">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

	$pdf->writeHTML($txt, true, false, true, false, '');
	ob_end_clean();

    $pdf->Output('Total_Royalty_Capital_History.pdf', 'I');
		

}

	public function set_amount_my_fund()
	{
		$data = $this->input->post();
		if(!empty($data['amount']))
		{
			$userdata = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
			$fund = $userdata['my_fund'] + $data['amount'];
			$arr  = array('my_fund'=>$fund);
			$this->user_model->edit_user($arr,$this->session->userdata('admin_id'));
			$remaining = $this->input->post('team_income') - $this->input->post('amount');
				$data = array(
					'user_id' 			=>  $this->session->userdata('admin_id'),
					'team_income'		=>	$this->input->post('team_income'),
					'withdraw_amount'   =>	$this->input->post('withdraw_amount'),
					'tds_amount'		=>	$this->input->post('tds'),
					'bank_type'			=>	"",
					'total_withdraw'	=>	$this->input->post('amount'),
					'remaining_team_income'=>$remaining,
					'created_at'  	    => date('Y-m-d h:i:s'),
					);
				$result = $this->user_model->save_team_income($data);
				if($result)
				{
					$data = array(
						'user_id' => $this->session->userdata('admin_id'),
						'amount'  => $this->input->post('amount'),
						'account_holder_name' => strtoupper($this->input->post('name')),
						'description' => "Withdraw Capital Team Income By"." ".$this->input->post('amount'),
						'created_at'  => date('Y-m-d : h:m:s'),
						'created_by'  => $this->session->userdata('admin_id'),
						'updated_at'  => date('Y-m-d : h:m:s'),
						'updated_by'  => $this->session->userdata('admin_id'),
						);
							$resultf = $this->fund_model->save('ci_funds',$data);


    					$total_companybalance  =  $this->input->post('opening_balance') - $this->input->post('amount') ;

						$totalcompanyammount=array('opening_balance'=>$total_companybalance);

						$this->fund_model->update_company_bal($totalcompanyammount);

    				$transaction = array(
					'credit' => $this->input->post('amount'),
					'debit'  => 0,
					'payment_mode'=> $this->input->post('payment_mode'),
					'description'=>"Withdraw Capital Team Income By"." ".$this->input->post('amount'),
					'transaction_date'  => date('Y-m-d'),
					);
					$this->fund_model->save('ci_transactions',$transaction);

		}

	}
	$res = array('status'=>true);
	echo json_encode($res);

	}

	public function set_amount_my_fund_sip()
	{
		$data = $this->input->post();
		if(!empty($data))
		{
			$userdata = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
			$fund = $userdata['my_fund'] + $data['sip_amount'];
			$arr  = array('my_fund'=>$fund);
			$this->user_model->edit_user($arr,$this->session->userdata('admin_id'));
			$remaining = $this->input->post('amount') - $this->input->post('sip_amount');

			$data1 = array(
					'user_id' 			=>  $this->session->userdata('admin_id'),
					'sip_team_income'	=>	$this->input->post('amount'),
					'withdraw_amount'   =>	$this->input->post('withdraw_amount'),
					'tds_amount'		=>	$this->input->post('tds'),
					'bank_type'			=>	" ",
					'total_withdraw'	=>	$this->input->post('sip_amount'),
					'remaining_team_income'=>$remaining,
					'created_at'  	=> date('Y-m-d h:m:s'),
						);

				if(!empty($this->input->post('sip_withdraw')))
					{
						$arrdata = array(
						'user_id'=>$this->session->userdata('admin_id'),
						'activity'=>'Withdraw SIP Team Income',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					}
		}
			$result = $this->user_model->save_sip_team_income($data1);

			
	    $res = array('status'=>true);
		echo json_encode($res);
	}
	public function get_total_royalty_count()
	{
		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income   = 0;$level9_income = 0;$level10_income = 0;
		$data['setting2']      	= $this->setting_model->get_general_settings();

	    $setting      	= $this->setting_model->get_general_settings();
	    $team_capital_aum =0;$team_count = 0;$team_self_capital = 0;
		$data['active_alert'] = $this->user_model->get_active_alert();
 		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
 		$data['team_income_level2']=0;$data['team_income_level3']=0;$data['team_income_level4'] =0;$data['team_income_level5']=0;$data['team_income_level6']=0;$data['team_income_level7']=0;$data['team_income_level8']=0;$data['team_income_level9']=0;$data['team_income_level10']=0;
		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
				    array_push($l1_arr,$row['id']);
				    
				}
					

			}
			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);

				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{


							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							   $data['level2_capital']+=$row['capital_aum']; 
	 						   $team_self_capital+= $row['self_capital'];;
								$team_count++;
								array_push($l2_arr,$row['id']);
							}
							
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						$data['level_2'] 		= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					    $data['team_income_level2']		= $data['team_income_level2'];

 					}
			}	

	}


			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level2'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								 $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
								

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
							}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								$per              = ceil($setting['level4_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

						       $team_count++;$team_self_capital++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per    = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

							 	$team_count++;$team_self_capital++;
							 	array_push($l5_arr,$row['id']);


							}
						}

						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;$team_self_capital++;

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								 $team_count++;$team_self_capital++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);


							
							 $team_count++;$team_self_capital++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income + $level6_income + $level5_income + $level4_income + $level3_income + $level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])==33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  

			}

		}


		}
      
     	if(!empty($data['l2_arr']))
		{

		$data['all_level2'] = $this->user_model->get_my_levels_arr_each($data['l2_arr']);
      }
		if(!empty($data['l3_arr']))
		{
			$data['all_level3'] = $this->user_model->get_my_levels_arr_each($data['l3_arr']);
		
		}
		if(!empty($data['l4_arr']))
		{
			$data['all_level4'] = $this->user_model->get_my_levels_arr_each($data['l4_arr']);
		
		}
		if(!empty($data['l5_arr']))
		{
			$data['all_level5'] = $this->user_model->get_my_levels_arr_each($data['l5_arr']);

		}

		if(!empty($data['l6_arr']))
		{
				$data['all_level6'] = $this->user_model->get_my_levels_arr_each($data['l6_arr']);
	
		}
		if(!empty($data['l7_arr']))
		{
			$data['all_level7'] = $this->user_model->get_my_levels_arr_each($data['l7_arr']);
	
		}

		if(!empty($data['l8_arr']))
		{
				$data['all_level8'] = $this->user_model->get_my_levels_arr_each($data['l8_arr']);
		}
		
	if(!empty($data['l9_arr']))
		{
				$data['all_level9'] = $this->user_model->get_my_levels_arr_each($data['l9_arr']);
		
		}

		if(!empty($data['l10_arr']))
		{
	
			$data['all_level10'] = $this->user_model->get_my_levels_arr_each($data['l10_arr']);
		}

		$data['setting']      = $this->setting_model->get_general_settings();
		$data['my_direct']		=  $this->user_model->get_my_directs($this->session->userdata('admin_id'));

		$data['my_team'] = $this->user_model->get_all_user_detail($user_id);
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/total_royalty_count', $data);
		$this->load->view('admin/includes/_footer');
	
	}

	public function download_total_royalty_count()
	{
		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income   = 0;$level9_income = 0;$level10_income = 0;
	   	$settings =  $this->setting_model->get_general_settings();

		$data['setting2']      	=  $this->setting_model->get_general_settings();

	    $setting      	= $this->setting_model->get_general_settings();
	    $team_capital_aum = 0;$team_count = 0;$team_self_capital = 0;
		$data['active_alert'] = $this->user_model->get_active_alert();
 		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
 		$data['team_income_level2']=0;$data['team_income_level3']=0;$data['team_income_level4'] =0;$data['team_income_level5']=0;$data['team_income_level6']=0;$data['team_income_level7']=0;$data['team_income_level8']=0;$data['team_income_level9']=0;$data['team_income_level10']=0;
		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
				    array_push($l1_arr,$row['id']);
				    
				}
					

			}
			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);

				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{


							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level2_capital']+=$row['capital_aum']; 
	 						   $team_self_capital+= $row['self_capital'];;
								$team_count++;
								array_push($l2_arr,$row['id']);
							}
							
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						  $data['level_2'] 				= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					    $data['team_income_level2']		= $data['team_income_level2'];

 					}
			}	

	}


			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level2'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								 $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
								

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
							}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								
								$per         = ceil($setting['level4_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

						       $team_count++;$team_self_capital++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						
						$per       = ceil($setting['level5_incentive'])/100;
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

							 	$team_count++;$team_self_capital++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;$team_self_capital++;

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								 $team_count++;$team_self_capital++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								array_push($l9_arr,$row['id']);


							
							 $team_count++;$team_self_capital++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])==33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  

			}

		}


		}
		$this->load->library('MYPDF');

		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Total Royalty Account');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  			array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print

		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="15%" style="text-align:center">Date</th>
			<th  width="15%" style="text-align:center">Account No</th><th  width="15%" style="text-align:center">Username</th>
			<th  width="15%" style="text-align:center">Capital AUM</th>
			<th  width="15%" style="text-align:center">Team Income</th>
			<th  width="15%" style="text-align:center">Sponsor</th>
			<th  width="10%" style="text-align:center">Level</th>

			</tr><tbody>';
			$id=1;$capital_aum=0;
			if(!empty($data['team_income_level1']))
			{
				foreach($data['team_income_level1'] as $row)
				{ $capital_aum+=$row['capital_aum'];

					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   = $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
    			    $sponsor = $this->user_model->get_user_by_id($row['is_parent']);

				     /* DAILY TEAM INCOME */ 
				    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));

				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30 && $interval>0)
						{
				    		$level1_income1 = $level1_income/30;
				    		$level1_income  =  bcdiv($level1_income1 * $interval,1,2);

						}if($interval>30)
						{
				    		$level1_income1 = $level1_income/30;
				    		$level1_income  =  bcdiv($level1_income1 * $interval,1,2);

						}
					
					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .bcdiv($level1_income,1,2).'</td>
			    <td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 1'.'</td>

				
				</tr>';

				$id++;}
				}
			if(!empty($data['team_income_level2']))
			{
				foreach($data['team_income_level2'] as $row)
				{ $capital_aum+=$row['capital_aum'];
					$per              = ceil($setting['level2_incentive'])/100;
					  $sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$level2_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));

				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30 && $interval>0)
						{
				    		$level2_income = $level2_income/30;
				    		$level2_income  +=  bcdiv($level2_income * $interval,1,2);

						}if($interval>30)
						{
				    		$level2_income = $level2_income/30;
				    		$level2_income  +=  bcdiv($level2_income * $interval,1,2);

						}
					
	
					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .bcdiv($level2_income,1,2).'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 2'.'</td>


				</tr>';

				$id++;}
				}
			if(!empty($data['team_income_level3']))
			{
				foreach($data['team_income_level3'] as $row)
				{ $capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level3_incentive'])/100;
					$level3_income   = $row['capital_aum'] * $per;
		 			$sponsor = $this->user_model->get_user_by_id($row['is_parent']);


				     /* DAILY TEAM INCOME */ 
				    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));

                    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30 && $interval>0)
						{
				    		$level3_income = $level3_income/30;
				    		$level3_income  +=  bcdiv($level3_income * $interval,1,2);

						}if($interval>30)
						{
				    		$level3_income = $level3_income/30;
				    		$level3_income  +=  bcdiv($level3_income * $interval,1,2);

						}					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .bcdiv($level3_income,1,2).'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 3'.'</td>

				</tr>';

				$id++;}
				}	

				if(!empty($data['team_income_level4']))
			{
				foreach($data['team_income_level4'] as $row)
				{ $capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level4_incentive'])/100;
					$level4_income   = $row['capital_aum'] * $per;
		 			$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

				     /* DAILY TEAM INCOME */ 
				    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
        
                    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30 && $interval>0)
						{
				    		$level4_income = $level4_income/30;
				    		$level4_income  +=  bcdiv($level4_income * $interval,1,2);

						}if($interval>30)
						{
				    		$level4_income = $level4_income/30;
				    		$level4_income  +=  bcdiv($level4_income * $interval,1,2);

						}				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .bcdiv($level4_income,1,2).'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 4'.'</td>


				</tr>';

				$id++;}
				}
			if(!empty($data['team_income_level5']))
			{
				foreach($data['team_income_level5'] as $row)
				{ 
		 			$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level5_incentive'])/100;
					$level5_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
      
                    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30 && $interval>0)
						{
				    		$level5_income = $level5_income/30;
				    		$level5_income  +=  bcdiv($level5_income * $interval,1,2);

						}if($interval>30)
						{
				    		$level5_income = $level5_income/30;
				    		$level5_income  +=  bcdiv($level5_income * $interval,1,2);

						}
					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .bcdiv($level5_income,1,2).'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 5'.'</td>

				</tr>';

				$id++;}
				}
		if(!empty($data['team_income_level6']))
			{
				foreach($data['team_income_level6'] as $row)
				{
		 			$sponsor = $this->user_model->get_user_by_id($row['is_parent']);


				    $capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level6_incentive'])/100;
					$level6_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
   
                    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30 && $interval>0)
						{
				    		$level6_income = $level6_income/30;
				    		$level6_income  +=  bcdiv($level6_income * $interval,1,2);

						}if($interval>30)
						{
				    		$level6_income = $level6_income/30;
				    		$level6_income  +=  bcdiv($level6_income * $interval,1,2);

						}
					
					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .bcdiv($level6_income,1,2).'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 6'.'</td>

				</tr>';

				$id++;}
				}
		if(!empty($data['team_income_level7']))
			{
				foreach($data['team_income_level7'] as $row)
				{ 
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level7_incentive'])/100;
					$level7_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
         
                    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30 && $interval>0)
						{
				    		$level7_income = $level7_income/30;
				    		$level7_income  +=  bcdiv($level7_income * $interval,1,2);

						}if($interval>30)
						{
				    		$level7_income = $level7_income/30;
				    		$level7_income  +=  bcdiv($level7_income * $interval,1,2);

						}
					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .bcdiv($level7_income,1,2).'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 7'.'</td>

				</tr>';

				$id++;}
				}
			if(!empty($data['team_income_level8']))
			{
				foreach($data['team_income_level8'] as $row)
				{ 
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level8_incentive'])/100;
					$level8_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
  
                    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30 && $interval>0)
						{
				    		$level8_income = $level8_income/30;
				    		$level8_income  +=  bcdiv($level8_income * $interval,1,2);

						}if($interval>30)
						{
				    		$level8_income = $level8_income/30;
				    		$level8_income  +=  bcdiv($level8_income * $interval,1,2);

						}
					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .bcdiv($level8_income,1,2).'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 8'.'</td>

				</tr>';

				$id++;}
				}
			if(!empty($data['team_income_level9']))
			{
				foreach($data['team_income_level9'] as $row)
				{ 
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level9_incentive'])/100;
					$level9_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				   $date = date('Y-m-d',strtotime($row['final_cap_date']));
                   $date = date('Y-m-d', strtotime($date . ' +1 day'));
  
                    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30 && $interval>0)
						{
				    		$level9_income = $level9_income/30;
				    		$level9_income  +=  bcdiv($level9_income * $interval,1,2);

						}if($interval>30)
						{
				    		$level9_income = $level9_income/30;
				    		$level9_income  +=  bcdiv($level9_income * $interval,1,2);

						}
					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">' .$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .bcdiv($level9_income,1,2).'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 9'.'</td>

				</tr>';

				$id++;}
				}
			if(!empty($data['team_income_level10']))
			{
				foreach($data['team_income_level10'] as $row)
				{ 
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level10_incentive'])/100;
					$level10_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
    
                    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30 && $interval>0)
						{
				    		$level10_income = $level10_income/30;
				    		$level10_income  +=  bcdiv($level10_income * $interval,1,2);

						}if($interval>30)
						{
				    		$level10_income = $level10_income/30;
				    		$level10_income  +=  bcdiv($level10_income * $interval,1,2);

						}
	
					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">' .$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .bcdiv($level10_income,1,2).'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 10'.'</td>

				</tr>';

				$id++;}
				}

				$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="6" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="6" style="text-align:center;">'.$words.'</th></tr><tr class="bo"><td colspan="8" class="bo" style="border:1px solid black;">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="8" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

	$pdf->writeHTML($txt, true, false, true, false, '');
	ob_end_clean();

    $pdf->Output('Total_Royalty_Count.pdf', 'I');
	
			


      
	}

	public function get_total_royalty_capital()
	{
		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income   = 0;$level9_income = 0;$level10_income = 0;
		$data['setting2']      	= $this->setting_model->get_general_settings();

	    $setting      	= $this->setting_model->get_general_settings();
	    $team_capital_aum =0;$team_count = 0;$team_self_capital = 0;
		$data['active_alert'] = $this->user_model->get_active_alert();
 		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
 		$data['team_income_level2']=0;$data['team_income_level3']=0;$data['team_income_level4'] =0;$data['team_income_level5']=0;$data['team_income_level6']=0;$data['team_income_level7']=0;$data['team_income_level8']=0;$data['team_income_level9']=0;$data['team_income_level10']=0;
		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
				    array_push($l1_arr,$row['id']);
				    
				}
			}
			$data['l1_arr'] = $l1_arr;

		    $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
		if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
			if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);

					$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{


							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level2_capital']+=$row['capital_aum']; 
	 						   $team_self_capital+= $row['self_capital'];;
								$team_count++;
								array_push($l2_arr,$row['id']);
							}
							
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						  $data['level_2'] 				= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					    $data['team_income_level2']		= $data['team_income_level2'];

 					}
			}	

	}


			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level3'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
							    $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						    $team_self_capital+= $row['self_capital'];;
								

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
							}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per    = ceil($setting['level4_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

						       $team_count++;$team_self_capital++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						$data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								

								$per    = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

							 	$team_count++;$team_self_capital++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;$team_self_capital++;

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								 $team_count++;$team_self_capital++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);


							
							 $team_count++;$team_self_capital++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])==33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  

			}

		}


		}
      
		if(!empty($data['l2_arr']))
		{
          		$data['all_level2'] = $this->user_model->get_my_levels_arr_each($data['l2_arr']);

        }
		if(!empty($data['l3_arr']))
		{
			$data['all_level3'] = $this->user_model->get_my_levels_arr_each($data['l3_arr']);
		
		}
		if(!empty($data['l4_arr']))
		{
			$data['all_level4'] = $this->user_model->get_my_levels_arr_each($data['l4_arr']);
		
		}
		if(!empty($data['l5_arr']))
		{
			$data['all_level5'] = $this->user_model->get_my_levels_arr_each($data['l5_arr']);

		}

		if(!empty($data['l6_arr']))
		{
				$data['all_level6'] = $this->user_model->get_my_levels_arr_each($data['l6_arr']);
	
		}
		if(!empty($data['l7_arr']))
		{
			$data['all_level7'] = $this->user_model->get_my_levels_arr_each($data['l7_arr']);
	
		}

		if(!empty($data['l8_arr']))
		{
				$data['all_level8'] = $this->user_model->get_my_levels_arr_each($data['l8_arr']);
		}
		
	if(!empty($data['l9_arr']))
		{
				$data['all_level9'] = $this->user_model->get_my_levels_arr_each($data['l9_arr']);
		
		}

		if(!empty($data['l10_arr']))
		{
	
			$data['all_level10'] = $this->user_model->get_my_levels_arr_each($data['l10_arr']);
		}

		$data['my_team'] = $this->user_model->get_all_user_detail($user_id);
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/total_royalty_capital', $data);
		$this->load->view('admin/includes/_footer');
	
	}

	public function download_total_royalty_capital()
	{
		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income = 0;$level9_income = 0;$level10_income = 0;
	   $team_capital_aum =0;$team_self_capital =0;$team_count=0;
	  
	  	$settings =  $this->setting_model->get_general_settings();

		$this->load->library('MYPDF');

		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Total Royalty Capital');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Total Active Capital', PDF_HEADER_STRING, array(0,64,255), 
  			array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);

		// add a page
		$pdf->AddPage();
		$id=1;

		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
				    array_push($l1_arr,$row['id']);
				    
				}
					

			}
			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);

				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{


							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level2_capital']+=$row['capital_aum']; 
	 						   $team_self_capital+= $row['self_capital'];;
								$team_count++;
								array_push($l2_arr,$row['id']);
							}
							
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						  $data['level_2'] 				= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					    $data['team_income_level2']		= $data['team_income_level2'];

 					}
			}	

	}


			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level2'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								 $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
								

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
							}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level4_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

						       $team_count++;$team_self_capital++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

							 	$team_count++;$team_self_capital++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
							array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;$team_self_capital++;

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								 $team_count++;$team_self_capital++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);


							
							 $team_count++;$team_self_capital++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])==33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  

			}

		}


		}


		// set some text to print

		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="15%" style="text-align:center">Date</th>
			<th  width="15%" style="text-align:center">Account No</th><th  width="20%" style="text-align:center">Username</th>
			<th  width="15%" style="text-align:center">Capital AUM</th>
			<th  width="15%" style="text-align:center">Sponsor</th>
			<th  width="10%" style="text-align:center">Level</th>

			</tr><tbody>';
			$id=0;$capital_aum=0;
			if(!empty($data['team_income_level1']))
			{
				foreach($data['team_income_level1'] as $row)
				{ 
					 if(!empty($row['final_cap_date'])){
        			  $date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
                    if(date('Y-m-d')>=$date){
					$capital_aum+=$row['capital_aum'];

					$per              = ceil($setting['level1_incentive'])/100;
					 $sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$level1_income   = $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 

				     /* DAILY TEAM INCOME */ 
				  
				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30)
						{
				    		$level1_income = $level1_income/30;
				    		$level1_income  =  bcdiv($level1_income * $interval,1,2);
						}
						if($interval>30)
						{
				    		$level1_income = $level1_income/30;
				    		$level1_income  =  bcdiv($level1_income * $interval,1,2);
						}
					
				$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
			    <td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 1'.'</td>

				</tr>';
				$capital_aum +=$level1_income1;

				$id++;
			}	
			}
			}
				}

			if(!empty($data['team_income_level2']))
			{
				foreach($data['team_income_level2'] as $row)
				{ 
					 if(!empty($row['final_cap_date'])){
        			$date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
                    if(date('Y-m-d')>=$date){
					 $sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$capital_aum+=$row['capital_aum'];
					$per              = ceil($setting['level2_incentive'])/100;
					$level2_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				   
				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30)
						{
				    		$level2_income = $level2_income/30;
				    		$level2_income  =  bcdiv($level2_income * $interval,1,2);
						}
						if($interval>30)
						{
				    		$level2_income = $level2_income/30;
				    		$level2_income  =  bcdiv($level2_income * $interval,1,2);
						}
					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 2'.'</td>

				</tr>';
				$capital_aum +=$level2_income;

				$id++;}
				}
				}
			}

			if(!empty($data['team_income_level3']))
			{
				foreach($data['team_income_level3'] as $row)
				{ 
					 if(!empty($row['final_cap_date'])){
        			$date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
                    if(date('Y-m-d')>=$date){
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level3_incentive'])/100;
					$level3_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				    
				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30)
						{
				    		$level3_income = $level3_income/30;
				    		$level3_income  =  bcdiv($level3_income * $interval,1,2);
						}
						if($interval>30)
						{
				    		$level3_income  = $level3_income/30;
				    		$level3_income  =  bcdiv($level3_income * $interval,1,2);
						}
					
				$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 3'.'</td>

				</tr>';
				$capital_aum +=$level3_income;

				$id++;}
			}
				}	
			}

				if(!empty($data['team_income_level4']))
			{
				foreach($data['team_income_level4'] as $row)
				{ 
					 if(!empty($row['final_cap_date'])){
        			$date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
                    if(date('Y-m-d')>=$date){
			       $sponsor = $this->user_model->get_user_by_id($row['is_parent']);


					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level4_incentive'])/100;
					$level4_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				    
				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30)
						{
				    		$level4_income = $level4_income/30;
				    		$level4_income  =  bcdiv($level4_income * $interval,1,2);
						}
						if($interval>30)
						{
				    		$level4_income  = $level4_income/30;
				    		$level4_income  =  bcdiv($level4_income * $interval,1,2);
						}

					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 4'.'</td>

				</tr>';
				$capital_aum +=$level4_income;

				$id++;}
			}
				}
			}
			if(!empty($data['team_income_level5']))
			{
				foreach($data['team_income_level5'] as $row)
				{ 	
					 if(!empty($row['final_cap_date'])){
        			$date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
                    if(date('Y-m-d')>=$date){	
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level5_incentive'])/100;
					$level5_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				  
				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30)
						{
				    		$level5_income = $level5_income/30;
				    		$level5_income  =  bcdiv($level5_income * $interval,1,2);
						}
						if($interval>30)
						{
				    		$level5_income  = $level5_income/30;
				    		$level5_income  =  bcdiv($level5_income * $interval,1,2);
						}

				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 5'.'</td>

				</tr>';
				$capital_aum +=$level5_income;

				$id++;}
			}
			}
				}
		if(!empty($data['team_income_level6']))
			{
				foreach($data['team_income_level6'] as $row)
				{ 
					 if(!empty($row['final_cap_date'])){
        			$date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
                    if(date('Y-m-d')>=$date){
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level6_incentive'])/100;
					$level6_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				    
				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30)
						{
				    		$level6_income = $level6_income/30;
				    		$level6_income  =  bcdiv($level6_income * $interval,1,2);
						}
						if($interval>30)
						{
				    		$level6_income  = $level6_income/30;
				    		$level6_income  =  bcdiv($level6_income * $interval,1,2);
						}
					
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 6'.'</td>

				</tr>';
				$capital_aum +=$level6_income;

				$id++;}
			}
				}
				}
		if(!empty($data['team_income_level7']))
			{
				foreach($data['team_income_level7'] as $row)
				{ 
					 if(!empty($row['final_cap_date'])){
        			$date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
                    if(date('Y-m-d')>=$date){
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level7_incentive'])/100;
					$level7_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				   
				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30)
						{
				    		$level7_income = $level7_income/30;
				    		$level7_income  =  bcdiv($level7_income * $interval,1,2);
						}
						if($interval>30)
						{
				    		$level7_income  = $level7_income/30;
				    		$level7_income  =  bcdiv($level7_income * $interval,1,2);
						}
					
				$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 7'.'</td>

				</tr>';
				$capital_aum +=$level7_income;

				$id++;}
			}
		}
			}
				
			if(!empty($data['team_income_level8']))
			{
				foreach($data['team_income_level8'] as $row)
				{ 
					 if(!empty($row['final_cap_date'])){
        			$date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
                    if(date('Y-m-d')>=$date){
					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level8_incentive'])/100;
					$level8_income   = $row['capital_aum'] * $per;
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

				     /* DAILY TEAM INCOME */ 
				   
				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30)
						{
				    		$level8_income = $level8_income/30;
				    		$level8_income  =  bcdiv($level8_income * $interval,1,2);
						}
						if($interval>30)
						{
				    		$level8_income  = $level8_income/30;
				    		$level8_income  =  bcdiv($level8_income * $interval,1,2);
						}
					
				$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 8'.'</td>

				</tr>';
				$capital_aum +=$level8_income;

				$id++;}
				}
				}
				}
			if(!empty($data['team_income_level9']))
			{
				foreach($data['team_income_level9'] as $row)
				{    
					 if(!empty($row['final_cap_date'])){
        			$date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
                    if(date('Y-m-d')>=$date){	
					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level9_incentive'])/100;
					$level9_income   = $row['capital_aum'] * $per;
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

				     /* DAILY TEAM INCOME */ 
				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30)
						{
				    		$level9_income = $level9_income/30;
				    		$level9_income  =  bcdiv($level9_income * $interval,1,2);
						}
						if($interval>30)
						{
				    		$level9_income  = $level9_income/30;
				    		$level9_income  =  bcdiv($level9_income * $interval,1,2);
						}
				$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 9'.'</td>

				</tr>';
				$capital_aum +=$level9_income;

				$id++;} }
			}
				}
			if(!empty($data['team_income_level10']))
			{
				foreach($data['team_income_level10'] as $row)
				{ 
					 if(!empty($row['final_cap_date'])){
        			$date = date('Y-m-d',strtotime($row['final_cap_date']));
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
                    if(date('Y-m-d')>=$date){
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$capital_aum+=$row['capital_aum'];
					$per             = ceil($setting['level10_incentive'])/100;
					$level10_income   = $row['capital_aum'] * $per;

				     /* DAILY TEAM INCOME */ 
				   
				    $date1 =  new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
					$interval = $date1->diff($date2)->format("%a");  
						if($interval<30)
						{
				    		$level10_income = $level10_income/30;
				    		$level10_income  =  bcdiv($level10_income * $interval,1,2);
						}
						if($interval>30)
						{
				    		$level10_income  = $level10_income/30;
				    		$level10_income  =  bcdiv($level10_income * $interval,1,2);
						}
					
				$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['created_at']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				<td style="text-align:center">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
				<td style="text-align:center">' .'Level 10'.'</td>

				</tr>';
				$capital_aum +=$level10_income;
				$id++;
			}
			}
		}
				}

				$words = $this->getIndianCurrency($capital_aum);
	$txt.='</tbody><tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="5" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="5" style="text-align:center">'.$words.'</th></tr><tr class="bo"><td colspan="7" class="bo" style="text-align:center;">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="7" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';

	


	$txt.='</body></html>';
	 

	$pdf->writeHTML($txt, true, false, true, false, '');
	ob_end_clean();

    $pdf->Output('Total_Royalty_Capital.pdf', 'I');
	}

	public function total_royalty_self_capital()
	{
		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income   = 0;$level9_income = 0;$level10_income = 0;
		$data['setting2']      	= $this->setting_model->get_general_settings();

	    $setting      	= $this->setting_model->get_general_settings();
	    $team_capital_aum =0;$team_count = 0;$team_self_capital = 0;
		$data['active_alert'] = $this->user_model->get_active_alert();
 		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
 		$data['team_income_level2']=0;$data['team_income_level3']=0;$data['team_income_level4'] =0;$data['team_income_level5']=0;$data['team_income_level6']=0;$data['team_income_level7']=0;$data['team_income_level8']=0;$data['team_income_level9']=0;$data['team_income_level10']=0;
		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
				    array_push($l1_arr,$row['id']);
				    
				}
					

			}
			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);

				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{


							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level2_capital']+=$row['capital_aum']; 
	 						   $team_self_capital+= $row['self_capital'];;
								$team_count++;
								array_push($l2_arr,$row['id']);
							}
							
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						  $data['level_2'] 				= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					    $data['team_income_level2']		= $data['team_income_level2'];

 					}
			}	

	}


			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level3'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								 $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
								

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
							}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level4_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

						       $team_count++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

							 	$team_count++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								array_push($l6_arr,$row['id']);
							 	$team_count++;



							}
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								 $team_count++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);
								$team_count++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])==33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);
 								$team_count++;


							}
							
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  

			}

		}


		}
      
		if(!empty($data['l2_arr']))
		{
          		$data['all_level2'] = $this->user_model->get_my_levels_arr_each($data['l2_arr']);

        }
		if(!empty($data['l3_arr']))
		{
			$data['all_level3'] = $this->user_model->get_my_levels_arr_each($data['l3_arr']);
		
		}
		if(!empty($data['l4_arr']))
		{
			$data['all_level4'] = $this->user_model->get_my_levels_arr_each($data['l4_arr']);
		
		}
		if(!empty($data['l5_arr']))
		{
			$data['all_level5'] = $this->user_model->get_my_levels_arr_each($data['l5_arr']);

		}

		if(!empty($data['l6_arr']))
		{
				$data['all_level6'] = $this->user_model->get_my_levels_arr_each($data['l6_arr']);
	
		}
		if(!empty($data['l7_arr']))
		{
			$data['all_level7'] = $this->user_model->get_my_levels_arr_each($data['l7_arr']);
	
		}

		if(!empty($data['l8_arr']))
		{
				$data['all_level8'] = $this->user_model->get_my_levels_arr_each($data['l8_arr']);
		}
		
	if(!empty($data['l9_arr']))
		{
				$data['all_level9'] = $this->user_model->get_my_levels_arr_each($data['l9_arr']);
		
		}

		if(!empty($data['l10_arr']))
		{
	
			$data['all_level10'] = $this->user_model->get_my_levels_arr_each($data['l10_arr']);
		}

		$data['my_team'] = $this->user_model->get_all_user_detail($user_id);
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/total_royalty_self_capital', $data);
		$this->load->view('admin/includes/_footer');
	
	}

	public function download_total_royalty_self_capital()
	{
		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income   = 0;$level9_income = 0;$level10_income = 0;
		$data['setting2']      	= $this->setting_model->get_general_settings();

	    $setting      	= $this->setting_model->get_general_settings();
	    $team_capital_aum =0;$team_count = 0;$team_self_capital = 0;
		$data['active_alert'] = $this->user_model->get_active_alert();
 		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
 		$data['team_income_level2']=0;$data['team_income_level3']=0;$data['team_income_level4'] =0;$data['team_income_level5']=0;$data['team_income_level6']=0;$data['team_income_level7']=0;$data['team_income_level8']=0;$data['team_income_level9']=0;$data['team_income_level10']=0;
		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
				    array_push($l1_arr,$row['id']);
				    
				}
					

			}
			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);

				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{


							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level2_capital']+=$row['capital_aum']; 
	 						   $team_self_capital+= $row['self_capital'];;
								$team_count++;
								array_push($l2_arr,$row['id']);
							}
							
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						  $data['level_2'] 				= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					    $data['team_income_level2']		= $data['team_income_level2'];

 					}
			}	

	}


			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level2'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								 $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
								

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
							}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level4_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

						       $team_count++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

							 	$team_count++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								array_push($l6_arr,$row['id']);
							 	$team_count++;



							}
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								 $team_count++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);
								$team_count++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])==33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);
 								$team_count++;


							}
							
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  

			}

		}
	}


		$this->load->library('MYPDF');
	   $settings =  $this->setting_model->get_general_settings();

		$my_team = $this->user_model->get_all_inactive($this->session->userdata('admin_id'));


		// set some text to print
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Total Active Account');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  		array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print

		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="20%" style="text-align:center">Date</th>
			<th  width="15%" style="text-align:center">Account No</th>
			<th  width="15%" style="text-align:center">Username</th>
			<th  width="15%" style="text-align:center">Self Capital</th>
			<th  width="15%" style="text-align:center">Sponsor</th>
			<th  width="15%" style="text-align:center">Level</th>
			</tr><tbody>';
			$capital_aum=0;$id=1;
			if(!empty($data['team_income_level1']))
			{

				foreach($data['team_income_level1'] as $row)
				{
					if(!empty($row['final_cap_date'])){
				$sponsor = $this->user_model->get_user_by_id($row['is_parent']);

					$capital_aum+=$row['self_capital'];
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['self_capital'].'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					<td style="text-align:center;">'.'Level 1'.'</td>
					</tr>';

				$id++;
				}
			}
			}
			if(!empty($data['team_income_level2']))
			{

				foreach($data['team_income_level2'] as $row)
				{
					if(!empty($row['final_cap_date'])){

					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$capital_aum+=$row['self_capital'];

					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['self_capital'].'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					<td style="text-align:center;">'.'Level 2'.'</td>
					
					</tr>';

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level3']))
			{

				foreach($data['team_income_level3'] as $row)
				{
					if(!empty($row['final_cap_date'])){

					$capital_aum+=$row['self_capital'];
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['self_capital'].'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					<td style="text-align:center;">'.'Level 3'.'</td>
					
					</tr>';

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level4']))
			{

				foreach($data['team_income_level4'] as $row)
				{
					if(!empty($row['final_cap_date'])){

				$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
				  $capital_aum+=$row['self_capital'];

					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['self_capital'].'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					<td style="text-align:center;">'.'Level 4'.'</td>
					
					</tr>';

				$id++;
				}
			}
		}

			if(!empty($data['team_income_level5']))
			{

				foreach($data['team_income_level5'] as $row)
				{
						if(!empty($row['final_cap_date'])){

					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$capital_aum+=$row['self_capital'];

					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['self_capital'].'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					<td style="text-align:center;">'.'Level 5'.'</td>
					
					</tr>';

				$id++;
				}
			}
		}

			if(!empty($data['team_income_level6']))
			{

				foreach($data['team_income_level6'] as $row)
				{
				if(!empty($row['final_cap_date'])){

					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$capital_aum+=$row['self_capital'];

					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['self_capital'].'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					<td style="text-align:center;">'.'Level 6'.'</td>
					
					</tr>';

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level7']))
			{

				foreach($data['team_income_level7'] as $row)
				{
				if(!empty($row['final_cap_date'])){

					$capital_aum+=$row['self_capital'];
					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['self_capital'].'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					<td style="text-align:center;">'.'Level 7'.'</td>
					
					</tr>';

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level8']))
			{

				foreach($data['team_income_level8'] as $row)
				{
					if(!empty($row['final_cap_date'])){

					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$capital_aum+=$row['self_capital'];

					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['self_capital'].'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					<td style="text-align:center;">'.'Level 8'.'</td>
					
					</tr>';

				$id++;
				}
			}
			}
			if(!empty($data['team_income_level9']))
			{

				foreach($data['team_income_level9'] as $row)
				{
				if(!empty($row['final_cap_date'])){

					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$capital_aum+=$row['self_capital'];

					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['self_capital'].'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					<td style="text-align:center;">'.'Level 9'.'</td>
					
					</tr>';

				$id++;
				}
			}
			}

			if(!empty($data['team_income_level10']))
			{

				foreach($data['team_income_level10'] as $row)
				{
				if(!empty($row['final_cap_date'])){

					$sponsor = $this->user_model->get_user_by_id($row['is_parent']);
					$capital_aum+=$row['self_capital'];

					$txt.=	'<tr>
					<td style="text-align:center;">' .$id.'</td>
					<td style="text-align:center;">' .date_time($row['created_at']).'</td>
					<td style="text-align:center;">'.$row['account_no'].'</td>
					<td style="text-align:center;">' .$row['username'].'</td>
					<td style="text-align:center;">' .$row['self_capital'].'</td>
					<td style="text-align:center;">' .$sponsor['username']." ".$sponsor['account_no'].'</td>
					<td style="text-align:center;">'.'Level 10'.'</td>
					</tr>';

				$id++;
				}
			}
			}


	$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="5" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="5" style="text-align:center;">'.$words.'</th></tr><tr class="bo"><td colspan="7" class="bo" style="text-align:center;">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="7" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

	$pdf->writeHTML($txt, true, false, true, false, '');
	ob_end_clean();

    $pdf->Output('Total_Royalty_Self_Capital.pdf', 'I');
		


	}

	public function account_history_active_capital()
	{

		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income   = 0;$level9_income = 0;$level10_income = 0;
		$data['setting2']   = $this->setting_model->get_general_settings();

	    $setting      	= $this->setting_model->get_general_settings();
	    $team_capital_aum =0;$team_count = 0;$team_self_capital = 0;
		$data['active_alert'] = $this->user_model->get_active_alert();
 		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
 		$data['team_income_level2']=0;$data['team_income_level3']=0;$data['team_income_level4'] =0;$data['team_income_level5']=0;$data['team_income_level6']=0;$data['team_income_level7']=0;$data['team_income_level8']=0;$data['team_income_level9']=0;$data['team_income_level10']=0;
		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
				    array_push($l1_arr,$row['id']);
				    
				}
					

			}
			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 
			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);

				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{


							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level2_capital']+=$row['capital_aum']; 
	 						   $team_self_capital+= $row['self_capital'];;
								$team_count++;
								array_push($l2_arr,$row['id']);
							}
							
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						  $data['level_2'] 				= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					    $data['team_income_level2']		= $data['team_income_level2'];

 					}
			}	

	}


			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level3'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
							    $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
								

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
						}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level4_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

						       $team_count++;$team_self_capital++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

							 	$team_count++;$team_self_capital++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
							array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;$team_self_capital++;

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								 $team_count++;$team_self_capital++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								
								$per              = ceil($setting['level9_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);


							
							 $team_count++;$team_self_capital++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])==33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  

			}

		}


		}
      
		if(!empty($data['l2_arr']))
		{
          		$data['all_level2'] = $this->user_model->get_my_levels_arr_each($data['l2_arr']);

        }
		if(!empty($data['l3_arr']))
		{
			$data['all_level3'] = $this->user_model->get_my_levels_arr_each($data['l3_arr']);
		
		}
		if(!empty($data['l4_arr']))
		{
			$data['all_level4'] = $this->user_model->get_my_levels_arr_each($data['l4_arr']);
		
		}
		if(!empty($data['l5_arr']))
		{
			$data['all_level5'] = $this->user_model->get_my_levels_arr_each($data['l5_arr']);

		}

		if(!empty($data['l6_arr']))
		{
				$data['all_level6'] = $this->user_model->get_my_levels_arr_each($data['l6_arr']);
	
		}
		if(!empty($data['l7_arr']))
		{
			$data['all_level7'] = $this->user_model->get_my_levels_arr_each($data['l7_arr']);
	
		}

		if(!empty($data['l8_arr']))
		{
				$data['all_level8'] = $this->user_model->get_my_levels_arr_each($data['l8_arr']);
		}
		
	if(!empty($data['l9_arr']))
		{
				$data['all_level9'] = $this->user_model->get_my_levels_arr_each($data['l9_arr']);
		
		}

		if(!empty($data['l10_arr']))
		{
	
			$data['all_level10'] = $this->user_model->get_my_levels_arr_each($data['l10_arr']);
		}

		$data['my_team'] = $this->user_model->get_all_user_detail($user_id);
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/royalty_active_capital', $data);
		$this->load->view('admin/includes/_footer');
	}


	public function account_history_sip_balance()
	{
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
	  $data['active_alert'] = $this->user_model->get_active_alert();
		$setting = $this->setting_model->get_general_settings();
		$data['setting2'] = $this->setting_model->get_general_settings();

		/* SIP TEAM INCOME START */
		$team_capital_aum =0;$team_count = 0;$team_self_capital = 0;

		$sip_level1_income =0;$sip_level2_income =0;$sip_level3_income =0;$sip_level4_income =0;$sip_level5_income =0;$sip_level6_income =0;
		$sip_level7_income =0;$sip_level8_income =0;$sip_level9_income =0;$sip_level10_income =0;$team_sip=0;

		if($data['user']['sip_balance']>='10000')
		{
			$capital_aum_date = $data['user']['final_sip_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			$data['sip_level1']	= $this->user_model->get_my_levels($this->session->userdata('admin_id'));
			foreach($data['sip_level1'] as $row)
			{
				if(!empty($row['sip_balance']))
				{
					$per              	  = ceil($setting['level1_incentive'])/100;
					$sip_level1_income   += $row['sip_balance'] * $per;

					$date = date('Y-m-d',strtotime($row['final_sip_date']));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    			$sip_level1_income = $sip_level1_income/30;
				    			$sip_level1_income =  round($sip_level1_income * $interval);
							}
							if($interval>30)
							{
								$sip_level1_income = $sip_level1_income/30;
				    			$sip_level1_income =  round($sip_level1_income * $interval);
						
							}
					

				}
					

			}
			$data['sip_1'] 	= $sip_level1_income;
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=3)
					{
					foreach($data['sip_level1'] as $row)
					{

					$data['sip_level_2']	= $this->user_model->get_my_levels($row['id']);
					if(!empty($data['sip_level_2']))
					{
						$l1_arr = array();
						foreach($data['sip_level_2'] as $row)
						{
							if(!empty($row['sip_balance']))
							{
								$per          = ceil($setting['level2_incentive'])/100;
				
								$team_sip+= $row['sip_balance'];
 					
						   	$sip_level2_income+= $row['sip_balance'] * $per;
						   	$date = date('Y-m-d',strtotime($row['final_sip_date']));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    			$sip_level2_income = $sip_level2_income/30;
				    			$sip_level2_income =  round($sip_level2_income * $interval);
							}
							if($interval>30)
							{
								$sip_level2_income = $sip_level2_income/30;
				    			$sip_level2_income =  round($sip_level2_income * $interval);
						
							}

						   	$team_sip+= $row['capital_aum'];
						    array_push($l1_arr,$row['id']);

							}
							$team_count++;
						
						}
						$data['sip_2'] = $sip_level1_income + $sip_level2_income;

				}	
			}

			}

		}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=5)
					{
							if(!empty($data['sip_level_2']))
						{
							$l2_arr = array();
							foreach($data['sip_level_2'] as $row)
							{

								$data['sip_level_3']	= $this->user_model->get_my_levels($row['id']);
							if(!empty($data['sip_level_3']))
							{

							$per          = ceil($setting['level3_incentive'])/100;
				
							foreach($data['sip_level_3'] as $row)
							{
								
								if(!empty($row['sip_balance']))
								{

							$sip_level3_income+= $row['sip_balance'] * $per;
							$date = date('Y-m-d',strtotime($row['final_sip_date']));

							$date1 = new DateTime($date);
							$date2 = new DateTime(date('Y-m-d'));
						
							$interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    			$sip_level3_income = $sip_level3_income/30;
				    			$sip_level3_income =  round($sip_level3_income * $interval);
							}
							if($interval>30)
							{
								$sip_level3_income = $sip_level3_income/30;
				    			$sip_level3_income =  round($sip_level3_income * $interval);
							}
		

								   $team_sip+= $row['capital_aum'];
								   array_push($l2_arr,$row['id']);

								}
								$team_count++;
						
						}
							$data['sip_3'] = $sip_level1_income + $sip_level2_income + $sip_level3_income;
						}
					}	
				}

			}

			}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=9)
					{
							if(!empty($data['sip_level_3']))
						{
							foreach($data['sip_level_3'] as $row)
							{
								$data['sip_level_4']	= $this->user_model->get_my_levels($row['id']);
						if(!empty($data['sip_level_4']))
						{
							$l3_arr = array();
						$per    = ceil($setting['level4_incentive'])/100;
				

 						foreach($data['sip_level_4'] as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level4_income+= $row['sip_balance'] * $per;

							   $date = date('Y-m-d',strtotime($row['final_sip_date']));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    			$sip_level4_income = $sip_level4_income/30;
				    			$sip_level4_income =  round($sip_level4_income * $interval);
							}
							if($interval>30)
							{
								$sip_level4_income = $sip_level4_income/30;
				    			$sip_level4_income =  round($sip_level4_income * $interval);
							}
							   $team_sip+= $row['capital_aum'];
							   array_push($l3_arr,$row['id']);

							}
							$team_count++;
							
						}
					$data['sip_4'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income;
			
				}
			}
		}
		}
	}
					
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=13)
					{
							if(!empty($data['sip_level_4']))
						{
							foreach($data['sip_level_4'] as $row)
							{

						$data['sip_level_5']	= $this->user_model->get_my_levels($row['id']);
						if(!empty($data['sip_level_5']))
						{
							$l4_arr = array();
 						foreach($data['sip_level_5'] as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level5_income+= $row['sip_balance'] * $per;
						   $date = date('Y-m-d',strtotime($row['final_sip_date']));

						   $date1 = new DateTime($date);
						   $date2 = new DateTime(date('Y-m-d'));
						
						   $interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    			$sip_level5_income = $sip_level5_income/30;
				    			$sip_level5_income =  round($sip_level5_income * $interval);
							}
							if($interval>30)
							{
								$sip_level5_income = $sip_level5_income/30;
				    			$sip_level5_income =  round($sip_level5_income * $interval);
							}

						   $team_sip+= $row['capital_aum'];
						   array_push($l4_arr,$row['id']);

						}
						$team_count++;
						
						}
						$data['sip_5'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income ;
						}
					}
				}
					
			}
			
			}

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=17)
					{
							if(!empty($data['sip_level_5']))
						{
							foreach($data['sip_level_5'] as $row)
							{
									$data['sip_level_6']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level_6']))
								{
									$l5_arr = array();
									$per    = ceil($setting['level6_incentive'])/100;
								
					 					foreach($data['sip_level_6'] as $row)
										{
											if(!empty($row['sip_balance']))
											{

											   $sip_level6_income+= $row['sip_balance'] * $per;

											   $date = date('Y-m-d',strtotime($row['final_sip_date']));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    			$sip_level6_income = $sip_level6_income/30;
				    			$sip_level6_income =  round($sip_level6_income * $interval);
							}
							if($interval>30)
							{
								$sip_level6_income = $sip_level6_income/30;
				    			$sip_level6_income =  round($sip_level6_income * $interval);
							}
											   $team_sip+= $row['capital_aum'];
											   	array_push($l5_arr,$row['id']);

											}
											$team_count++;
											
										}
										$data['sip_6'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income;
								}

							}
						}
					}
				}

			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=21)
					{
							if(!empty($data['sip_level_6']))
						{
							foreach($data['sip_level_6'] as $row)
							{
								$data['sip_level_7']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level_7']))
								{
									$l6_arr = array();
									$per    = ceil($setting['level7_incentive'])/100;
									
 								foreach($data['sip_level_7'] as $row)
								{
									if(!empty($row['sip_balance']))
									{

								   $sip_level7_income+= $row['sip_balance'] * $per;

								     $date = date('Y-m-d',strtotime($row['final_sip_date']));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    			$sip_level7_income = $sip_level7_income/30;
				    			$sip_level7_income =  round($sip_level7_income * $interval);
							}
							if($interval>30)
							{
								$sip_level7_income = $sip_level7_income/30;
				    			$sip_level7_income =  round($sip_level7_income * $interval);
							}
								   $team_sip+= $row['capital_aum'];
								   array_push($l6_arr,$row['id']);

						}
								$team_count++;
						
									}
									$data['sip_7'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income;
							}

							}
						}
					}
				}
			

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=24)
					{
							if(!empty($data['sip_level_7']))
						{
							foreach($data['sip_level_7'] as $row)
							{
									$data['sip_level_8']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level_8']))
								{
									$l7_arr = array();
									
											$per    = ceil($setting['level8_incentive'])/100;
										$data['sip_level8']	= $this->user_model->get_my_levels($row['id']);
						 					foreach($data['sip_level_8'] as $row)
											{
												if(!empty($row['sip_balance']))
												{

												   $sip_level8_income+= $row['sip_balance'] * $per;
								$date = date('Y-m-d',strtotime($row['final_sip_date']));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    			$sip_level8_income = $sip_level8_income/30;
				    			$sip_level8_income =  round($sip_level8_income * $interval);
							}
							if($interval>30)
							{
								$sip_level8_income = $sip_level8_income/30;
				    			$sip_level8_income =  round($sip_level8_income * $interval);
							}

												   $team_sip+= $row['capital_aum'];
												   array_push($l7_arr,$row['id']);

												}
												$team_count++;
												
											}
											$data['sip_8'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income;
									}
									
								
								}
							}
						}
					}
				


			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=29)
					{
							if(!empty($data['sip_level_8']))
						{
							foreach($data['sip_level_8'] as $row)
							{
								$data['sip_level_9']	= $this->user_model->get_my_levels($row['id']);
						if(!empty($data['sip_level_9']))
						{ 
							$l8_arr = array();

								$per    = ceil($setting['level9_incentive'])/100;
								$data['sip_level_9']	= $this->user_model->get_my_levels($row['id']);
							foreach($data['sip_level_9'] as $row)
							{
								if(!empty($row['sip_balance']))
								{

								   $sip_level9_income+= $row['sip_balance'] * $per;
								   $date = date('Y-m-d',strtotime($row['final_sip_date']));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    			$sip_level9_income = $sip_level9_income/30;
				    			$sip_level9_income =  round($sip_level9_income * $interval);
							}
							if($interval>30)
							{
								$sip_level9_income = $sip_level9_income/30;
				    			$sip_level9_income =  round($sip_level9_income * $interval);
							}
								   $team_sip+= $row['capital_aum'];
								   	array_push($l8_arr,$row['id']);
									$team_count++;
								}
								
							}
								
								
							}
						$data['sip_9'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income;
					
				}
			}
		}
	}
			
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=33)
					{
							if(!empty($data['sip_level_9']))
						{
							foreach($data['sip_level_9'] as $row)
							{
								$data['sip_level_10']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level_10']))
							{
								$l9_arr = array();
							$per    = ceil($setting['level10_incentive'])/100;
 						foreach($data['sip_level_10'] as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level10_income+= $row['sip_balance'] * $per;

						   $date = date('Y-m-d',strtotime($row['final_sip_date']));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    			$sip_level10_income = $sip_level10_income/30;
				    			$sip_level10_income =  round($sip_level10_income * $interval);
							}
							if($interval>30)
							{
								$sip_level10_income = $sip_level10_income/30;
				    			$sip_level10_income =  round($sip_level10_income * $interval);
							}

						   $team_sip+= $row['capital_aum'];
						   array_push($l9_arr,$row['id']);
							$team_count++;
						}
						
						
					}
					$data['sip_10'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income + $sip_level10_income;
					$data['sip_team_income'] = $data['sip_10'];
					}

							}
						}
					}
			}
			
		}
	}

			if(!empty($data['all_level2']))
			{
				$data['all_level2'] = $this->user_model->get_my_levels_arr_each($data['l1_arr']);
		
			}
		if(!empty($data['l3_arr']))
		{
			$data['all_level3'] = $this->user_model->get_my_levels_arr_each($data['l2_arr']);
		
		}
		if(!empty($data['l4_arr']))
		{
			$data['all_level4'] = $this->user_model->get_my_levels_arr_each($data['l3_arr']);
		
		}
		if(!empty($data['l5_arr']))
		{
			$data['all_level5'] = $this->user_model->get_my_levels_arr_each($data['l4_arr']);

		}

		if(!empty($data['l6_arr']))
		{
				$data['all_level6'] = $this->user_model->get_my_levels_arr_each($data['l5_arr']);
	
		}
		if(!empty($data['l7_arr']))
		{
			$data['all_level7'] = $this->user_model->get_my_levels_arr_each($data['l6_arr']);
	
		}

		if(!empty($data['l8_arr']))
		{
				$data['all_level8'] = $this->user_model->get_my_levels_arr_each($data['l7_arr']);
		}
		
	if(!empty($data['l9_arr']))
		{
				$data['all_level9'] = $this->user_model->get_my_levels_arr_each($data['l8_arr']);
		
		}

		if(!empty($data['l10_arr']))
		{
	
			$data['all_level10'] = $this->user_model->get_my_levels_arr_each($data['l9_arr']);
		}
			$user_id = $this->session->userdata('admin_id');

		$data['my_team'] = $this->fund_model->get_active_sip_rows($user_id);

		$data['my_team'] = $this->user_model->get_all_user_detail($user_id);
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/royalty_active_sip', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function download_account_his_sip()
	{

		 $this->load->library('MYPDF');
	   $settings =  $this->setting_model->get_general_settings();

			$my_team = $this->dashboard_model->get_active_capital_aum_ac();


		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('SIP Account History');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  		array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);
 	
		// add a page
		$pdf->AddPage();
		$id=1;$capital_aum=0;
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
	  $data['active_alert'] = $this->user_model->get_active_alert();
		$setting = $this->setting_model->get_general_settings();
		$data['setting2'] = $this->setting_model->get_general_settings();

		/* SIP TEAM INCOME START */
		$team_capital_aum =0;$team_count = 0;$team_self_capital = 0;

		$sip_level1_income =0;$sip_level2_income =0;$sip_level3_income =0;$sip_level4_income =0;$sip_level5_income =0;$sip_level6_income =0;
		$sip_level7_income =0;$sip_level8_income =0;$sip_level9_income =0;$sip_level10_income =0;$team_sip=0;

		if($data['user']['sip_balance']>='10000')
		{
			$capital_aum_date = $data['user']['final_sip_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			$data['sip_level1']	= $this->user_model->get_my_levels($this->session->userdata('admin_id'));
			foreach($data['sip_level1'] as $row)
			{
				if(!empty($row['sip_balance']))
				{
					$per              	  = ceil($setting['level1_incentive'])/100;
					$sip_level1_income   += $row['sip_balance'] * $per;

				    $data['level1_capital']+=$row['capital_aum']; 

				     /* DAILY TEAM INCOME */ 

				    $date = date('Y-m-d',strtotime($row['final_sip_date']));

					$date1 = new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
						
					$interval = $date1->diff($date2)->format("%a");  
					if($interval<30)
					{
				    	$sip_level1_income = $sip_level1_income/30;
				    	$sip_level1_income =  round($sip_level1_income * $interval);
					}
					if($interval>30)
					{
						$sip_level1_income = $sip_level1_income/30;
				    	$sip_level1_income =  round($sip_level1_income * $interval);
						
					}
						
					
				}
					

			}

			$data['sip_1'] 	= $sip_level1_income;
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=3)
					{
					foreach($data['sip_level1'] as $row)
					{

					$sip_level_2	= $this->user_model->get_my_levels($row['id']);
					if(!empty($sip_level_2))
					{
						$l1_arr = array();
						foreach($sip_level_2 as $row)
						{
							if(!empty($row['sip_balance']))
							{
								$per          = ceil($setting['level2_incentive'])/100;
				
								$team_sip+= $row['sip_balance'];
 					
						   	$sip_level2_income+= $row['sip_balance'] * $per;

						   	$date = date('Y-m-d',strtotime($row['final_sip_date']));

					$date1 = new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
						
					$interval = $date1->diff($date2)->format("%a");  
					if($interval<30)
					{
				    	$sip_level2_income = $sip_level2_income/30;
				    	$sip_level2_income =  round($sip_level2_income * $interval);
					}
					if($interval>30)
					{
						$sip_level2_income = $sip_level2_income/30;
				    	$sip_level2_income =  round($sip_level2_income * $interval);
						
					}



						   	$team_sip+= $row['capital_aum'];
						    array_push($l1_arr,$row['id']);

							}
							$team_count++;
						
						}
						$data['sip_2'] = $sip_level1_income + $sip_level2_income;

				}	
			}

			}

		}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=5)
					{
							if(!empty($sip_level_2))
						{
							$l2_arr = array();
							foreach($sip_level_2 as $row)
							{

								$sip_level_3	= $this->user_model->get_my_levels($row['id']);
							if(!empty($sip_level_3))
							{

							$per          = ceil($setting['level3_incentive'])/100;
				
							foreach($sip_level_3 as $row)
							{
								
								if(!empty($row['sip_balance']))
								{

								   $sip_level3_income+= $row['sip_balance'] * $per;
								   $date = date('Y-m-d',strtotime($row['final_sip_date']));

					$date1 = new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
						
					$interval = $date1->diff($date2)->format("%a");  
					if($interval<30)
					{
				    	$sip_level3_income = $sip_level3_income/30;
				    	$sip_level3_income =  round($sip_level3_income * $interval);
					}
					if($interval>30)
					{
						$sip_level3_income = $sip_level3_income/30;
				    	$sip_level3_income =  round($sip_level3_income * $interval);
						
					}
								   $team_sip+= $row['capital_aum'];
								   array_push($l2_arr,$row['id']);

								}
								$team_count++;
						
						}
							$data['sip_3'] = $sip_level1_income + $sip_level2_income + $sip_level3_income;
						}
					}	
				}

			}

			}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=9)
					{
							if(!empty($sip_level_3))
						{
							foreach($sip_level_3 as $row)
							{
								$sip_level_4	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_4))
						{
							$l3_arr = array();
						$per    = ceil($setting['level4_incentive'])/100;
				

 						foreach($sip_level_4 as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level4_income+= $row['sip_balance'] * $per;
							 	$date = date('Y-m-d',strtotime($row['final_sip_date']));

					$date1 = new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
						
					$interval = $date1->diff($date2)->format("%a");  
					if($interval<30)
					{
				    	$sip_level4_income = $sip_level4_income/30;
				    	$sip_level4_income =  round($sip_level4_income * $interval);
					}
					if($interval>30)
					{
						$sip_level4_income = $sip_level4_income/30;
				    	$sip_level4_income =  round($sip_level4_income * $interval);
						
					}

							   $team_sip+= $row['capital_aum'];
							   array_push($l3_arr,$row['id']);

							}
							$team_count++;
							
						}
					$data['sip_4'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income;
			
				}
			}
		}
		}
	}
					
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=13)
					{
							if(!empty($sip_level_4))
						{
							foreach($sip_level_4 as $row)
							{

						$sip_level_5	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_5))
						{
							$l4_arr = array();
 						foreach($sip_level_5 as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level5_income+= $row['sip_balance'] * $per;
				    $date = date('Y-m-d',strtotime($row['final_sip_date']));

					$date1 = new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
						
					$interval = $date1->diff($date2)->format("%a");  
					if($interval<30)
					{
				    	$sip_level5_income = $sip_level5_income/30;
				    	$sip_level5_income =  round($sip_level5_income * $interval);
					}
					if($interval>30)
					{
						$sip_level5_income = $sip_level5_income/30;
				    	$sip_level5_income =  round($sip_level5_income * $interval);
						
					}
						   $team_sip+= $row['capital_aum'];
						   array_push($l4_arr,$row['id']);

						}
						$team_count++;
						
						}
						$data['sip_5'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income ;
						}
					}
				}
					
			}
			
			}

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=17)
					{
							if(!empty($sip_level_5))
						{
							foreach($sip_level_5 as $row)
							{
									$sip_level_6	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_6))
								{
									$l5_arr = array();
									$per    = ceil($setting['level6_incentive'])/100;
								
					 					foreach($sip_level_6 as $row)
										{
											if(!empty($row['sip_balance']))
											{

											   $sip_level6_income+= $row['sip_balance'] * $per;
					$date = date('Y-m-d',strtotime($row['final_sip_date']));

					$date1 = new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
						
					$interval = $date1->diff($date2)->format("%a");  
					if($interval<30)
					{
				    	$sip_level6_income = $sip_level6_income/30;
				    	$sip_level6_income =  round($sip_level6_income * $interval);
					}
					if($interval>30)
					{
						$sip_level6_income = $sip_level6_income/30;
				    	$sip_level6_income =  round($sip_level6_income * $interval);
						
					}

											   $team_sip+= $row['capital_aum'];
											   	array_push($l5_arr,$row['id']);

											}
											$team_count++;
											
										}
										$data['sip_6'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income;
								}

							}
						}
					}
				}

			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=21)
					{
							if(!empty($sip_level_6))
						{
							foreach($sip_level_6 as $row)
							{
								$sip_level_7	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_7))
								{
									$l6_arr = array();
									$per    = ceil($setting['level7_incentive'])/100;
									
 								foreach($sip_level_7 as $row)
								{
									if(!empty($row['sip_balance']))
									{

								   $sip_level7_income+= $row['sip_balance'] * $per;
					$date = date('Y-m-d',strtotime($row['final_sip_date']));

					$date1 = new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
						
					$interval = $date1->diff($date2)->format("%a");  
					if($interval<30)
					{
				    	$sip_level7_income = $sip_level7_income/30;
				    	$sip_level7_income =  round($sip_level7_income * $interval);
					}
					if($interval>30)
					{
						$sip_level7_income = $sip_level7_income/30;
				    	$sip_level7_income =  round($sip_level7_income * $interval);
						
					}
								   $team_sip+= $row['capital_aum'];
								   array_push($l6_arr,$row['id']);

									}
								$team_count++;
						
									}
									$data['sip_7'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income;
							}

							}
						}
					}
				}
			

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=24)
					{
							if(!empty($sip_level_7))
						{
							foreach($sip_level_7 as $row)
							{
									$sip_level_8	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_8))
								{
									$l7_arr = array();
									
											$per    = ceil($setting['level8_incentive'])/100;
										$data['sip_level8']	= $this->user_model->get_my_levels($row['id']);
						 					foreach($sip_level_8 as $row)
											{
												if(!empty($row['sip_balance']))
												{

												   $sip_level8_income+= $row['sip_balance'] * $per;
					$date = date('Y-m-d',strtotime($row['final_sip_date']));

					$date1 = new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
						
					$interval = $date1->diff($date2)->format("%a");  
					if($interval<30)
					{
				    	$sip_level8_income = $sip_level8_income/30;
				    	$sip_level8_income =  round($sip_level8_income * $interval);
					}
					if($interval>30)
					{
						$sip_level8_income = $sip_level8_income/30;
				    	$sip_level8_income =  round($sip_level8_income * $interval);
						
					}							   

												   $team_sip+= $row['capital_aum'];
												   array_push($l7_arr,$row['id']);

												}
												$team_count++;
												
											}
											$data['sip_8'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income;
									}
									
								
								}
							}
						}
					}
				


			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=29)
					{
							if(!empty($sip_level_8))
						{
							foreach($sip_level_8 as $row)
							{
								$sip_level_9	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_9))
						{ 
							$l8_arr = array();

								$per    = ceil($setting['level9_incentive'])/100;
								$sip_level_9	= $this->user_model->get_my_levels($row['id']);
							foreach($sip_level_9 as $row)
							{
								if(!empty($row['sip_balance']))
								{

								   $sip_level9_income+= $row['sip_balance'] * $per;
					$date = date('Y-m-d',strtotime($row['final_sip_date']));

					$date1 = new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
						
					$interval = $date1->diff($date2)->format("%a");  
					if($interval<30)
					{
				    	$sip_level9_income = $sip_level9_income/30;
				    	$sip_level9_income =  round($sip_level9_income * $interval);
					}
					if($interval>30)
					{
						$sip_level9_income = $sip_level9_income/30;
				    	$sip_level9_income =  round($sip_level9_income * $interval);
						
					}							   
					
								   $team_sip+= $row['capital_aum'];
								   	array_push($l8_arr,$row['id']);
									$team_count++;
					}
								
							}
								
								
							}
						$data['sip_9'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income;
					
				}
			}
		}
	}
			
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=33)
					{
							if(!empty($sip_level_9))
						{
							foreach($sip_level_9 as $row)
							{
								$sip_level_10	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_10))
							{
								$l9_arr = array();
							$per    = ceil($setting['level10_incentive'])/100;
 						foreach($sip_level_10 as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level10_income+= $row['sip_balance'] * $per;
						   $date = date('Y-m-d',strtotime($row['final_sip_date']));

					$date1 = new DateTime($date);
					$date2 = new DateTime(date('Y-m-d'));
						
					$interval = $date1->diff($date2)->format("%a");  
					if($interval<30)
					{
				    	$sip_level10_income = $sip_level10_income/30;
				    	$sip_level10_income =  round($sip_level10_income * $interval);
					}
					if($interval>30)
					{
						$sip_level10_income = $sip_level10_income/30;
				    	$sip_level10_income =  round($sip_level10_income * $interval);
						
					}
						   $team_sip+= $row['capital_aum'];
						   array_push($l9_arr,$row['id']);
							$team_count++;
						}
						
						
					}
					$data['sip_10'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income + $sip_level10_income;
					$data['sip_team_income'] = $data['sip_10'];
					}

							}
						}
					}
			}
			
		}
	}

			if(!empty($data['all_level2']))
			{
				$data['all_level2'] = $this->user_model->get_my_levels_arr_each($data['l1_arr']);
		
			}
		if(!empty($data['l3_arr']))
		{
			$data['all_level3'] = $this->user_model->get_my_levels_arr_each($data['l2_arr']);
		
		}
		if(!empty($data['l4_arr']))
		{
			$data['all_level4'] = $this->user_model->get_my_levels_arr_each($data['l3_arr']);
		
		}
		if(!empty($data['l5_arr']))
		{
			$data['all_level5'] = $this->user_model->get_my_levels_arr_each($data['l4_arr']);

		}

		if(!empty($data['l6_arr']))
		{
				$data['all_level6'] = $this->user_model->get_my_levels_arr_each($data['l5_arr']);
	
		}
		if(!empty($data['l7_arr']))
		{
			$data['all_level7'] = $this->user_model->get_my_levels_arr_each($data['l6_arr']);
	
		}

		if(!empty($data['l8_arr']))
		{
				$data['all_level8'] = $this->user_model->get_my_levels_arr_each($data['l7_arr']);
		}
		
	if(!empty($data['l9_arr']))
		{
				$data['all_level9'] = $this->user_model->get_my_levels_arr_each($data['l8_arr']);
		
		}

		if(!empty($data['l10_arr']))
		{
	
			$data['all_level10'] = $this->user_model->get_my_levels_arr_each($data['l9_arr']);
		}
		


		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="20%" style="text-align:center">Date</th>
			<th  width="20%" style="text-align:center">Account No</th><th  width="20%" style="text-align:center">Username</th>
			<th  width="20%" style="text-align:center">SIP AUM</th>
			</tr><tbody>';
			$sip_balance=0;
			  $i=1; if(!empty($data['sip_level1'])) 
            { foreach($data['sip_level1'] as $row){ 
                if(!empty($row['sip_balance']))
                {
                	$sip_balance +=$row['sip_balance'];
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
			<td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .$row['username'].'</td>
			<td style="text-align:center">' .$row['sip_balance'].'</td>
			</tr>';

			$i++;} }}
			 if(!empty($sip_level2)) 
            { foreach($sip_level2 as $row){ 
                if(!empty($row['sip_balance']))
                {
                	$sip_balance +=$row['sip_balance'];
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
			<td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .$row['username'].'</td>
			<td style="text-align:center">' .$row['sip_balance'].'</td>
			</tr>';

			$i++;} }}
			 if(!empty($sip_level3)) 
            { foreach($sip_level3 as $row){ 
                if(!empty($row['sip_balance']))
                {
                	$sip_balance +=$row['sip_balance'];
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
			<td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .$row['username'].'</td>
			<td style="text-align:center">' .$row['sip_balance'].'</td>
			</tr>';

			$i++;} }}
			if(!empty($sip_level4)) 
            { foreach($sip_level4 as $row){ 
                if(!empty($row['sip_balance']))
                {
                	$sip_balance +=$row['sip_balance'];
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
			<td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .$row['username'].'</td>
			<td style="text-align:center">' .$row['sip_balance'].'</td>
			</tr>';

			$i++;} }}
			if(!empty($sip_level5)) 
            { foreach($sip_level5 as $row){ 
                if(!empty($row['sip_balance']))
                {
                	$sip_balance +=$row['sip_balance'];
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
			<td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .$row['username'].'</td>
			<td style="text-align:center">' .$row['sip_balance'].'</td>
			</tr>';

			$i++;} }}
			if(!empty($sip_level6)) 
            { foreach($sip_level6 as $row){ 
                if(!empty($row['sip_balance']))
                {
                	$sip_balance +=$row['sip_balance'];
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
			<td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .$row['username'].'</td>
			<td style="text-align:center">' .$row['sip_balance'].'</td>
			</tr>';

			$i++;} }}
			if(!empty($sip_level7)) 
            { foreach($sip_level7 as $row){ 
                if(!empty($row['sip_balance']))
                {
                	$sip_balance +=$row['sip_balance'];
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
			<td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .$row['username'].'</td>
			<td style="text-align:center">' .$row['sip_balance'].'</td>
			</tr>';

			$i++;} }}
			if(!empty($sip_level8)) 
            { foreach($sip_level8 as $row){ 
                if(!empty($row['sip_balance']))
                {
                	$sip_balance +=$row['sip_balance'];
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
			<td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .$row['username'].'</td>
			<td style="text-align:center">' .$row['sip_balance'].'</td>
			</tr>';

			$i++;} }}
			if(!empty($sip_level9)) 
            { foreach($sip_level9 as $row){ 
                if(!empty($row['sip_balance']))
                {
                    $sip_balance +=$row['sip_balance'];
	
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
			<td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .$row['username'].'</td>
			<td style="text-align:center">' .$row['sip_balance'].'</td>
			</tr>';

			$i++;} }}
			if(!empty($sip_level10)) 
            { foreach($sip_level10 as $row){ 
                if(!empty($row['sip_balance']))
                {
                	$sip_balance +=$row['sip_balance'];
			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
			<td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .$row['username'].'</td>
			<td style="text-align:center">' .$row['sip_balance'].'</td>
			</tr>';

			$i++;} }}

	$words = $this->getIndianCurrency($sip_balance);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="3" style="text-align:center">'.$sip_balance.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="3" style="text-align:center;">'.$words.'</th></tr><tr class="bo"><td colspan="6" class="bo" style="text-align:center;">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="6" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

			$pdf->writeHTML($txt, true, false, true, false, '');
		// print a block of text using Write()

		// ---------------------------------------------------------
		 ob_clean();
		//Close and output PDF document
		$pdf->Output('Active_SIP_Balance.pdf', 'I');

	}

public function total_active_account()
	{
		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		

		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
	  	$data['active_alert'] = $this->user_model->get_active_alert();
		$setting = $this->setting_model->get_general_settings();
		$data['setting2'] = $this->setting_model->get_general_settings();
		$team_capital_aum =0;$team_self_capital =0;

		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income = 0;$level9_income = 0;$level10_income = 0;
	    $setting      	= $this->setting_model->get_general_settings();
	    $team_count     =0;
		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
		$user_id      = $this->session->userdata('admin_id');
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
				$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			
				$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
					$team_capital_aum += $row['capital_aum'];
	 				$team_self_capital+= $row['self_capital'];;

				    $data['level1_capital']+=$row['capital_aum']; 
				    array_push($l1_arr,$row['id']);
				    
				}
					

			}


			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);
				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{

							  $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income     += $row['capital_aum'] * $per;
							   $team_capital_aum  += $row['capital_aum'];
							    $data['level2_capital']+=$row['capital_aum']; 
	 						   $team_self_capital+= $row['self_capital'];;
								$team_count++;
								array_push($l2_arr,$row['id']);
							}
							
						
 						}

 						$data['l2_arr']= $l2_arr;

					}
							$data['level_2'] 		= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					   
 					}
			}	
	}
			if(count($data['team_income_level1'])>=5)
			{

				if(!empty(count($data['team_income_level2'])))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level3'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

							    $per              = ceil($setting['level3_incentive'])/100;
							   $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
								

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
							}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}

			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level4_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

						        $team_count++;$team_self_capital++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])<=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						
							$per       = ceil($setting['level5_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								
								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

							 	$team_count++;$team_self_capital++;
							 	array_push($l5_arr,$row['id']);

							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])<=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
							    array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])<=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;$team_self_capital++;

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])<=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								 $team_count++;$team_self_capital++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])<=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);


							
							 $team_count++;$team_self_capital++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])<=33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
							foreach($data['team_income_level10'] as $row)
							{
								$per       = ceil($setting['level10_incentive'])/100;

								if(!empty($row['capital_aum']))
								{
									

									$per              = ceil($setting['level10_incentive'])/100;
									$level10_income   += $row['capital_aum'] * $per;
									$team_capital_aum+= $row['capital_aum'];
								   	$data['level10_capital']+=$row['capital_aum']; 

									$team_self_capital+= $row['self_capital'];;
									 array_push($l10_arr,$row['id']);



								}
							 	$team_count++;$team_self_capital++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income + $level1_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 
				 	 	$data['team_income_bal'] = $data['level_10'];

					}
				}  

			}

		
			}
		}	

		if(!empty($data['l2_arr']))
		{
			$data['all_level2'] = $this->user_model->get_my_levels_arr_each($data['l2_arr']);
		
		}
		if(!empty($data['l3_arr']))
		{
			$data['all_level3'] = $this->user_model->get_my_levels_arr_each($data['l3_arr']);
		
		}
		if(!empty($data['l4_arr']))
		{
			$data['all_level4'] = $this->user_model->get_my_levels_arr_each($data['l4_arr']);
		
		}
		if(!empty($data['l5_arr']))
		{
			$data['all_level5'] = $this->user_model->get_my_levels_arr_each($data['l5_arr']);

		}

		if(!empty($data['l6_arr']))
		{
				$data['all_level6'] = $this->user_model->get_my_levels_arr_each($data['l6_arr']);
	
		}
		if(!empty($data['l7_arr']))
		{
			$data['all_level7'] = $this->user_model->get_my_levels_arr_each($data['l7_arr']);
	
		}

		if(!empty($data['l8_arr']))
		{
				$data['all_level8'] = $this->user_model->get_my_levels_arr_each($data['l8_arr']);
		}
		
	if(!empty($data['l9_arr']))
		{
				$data['all_level9'] = $this->user_model->get_my_levels_arr_each($data['l9_arr']);
		
		}

		if(!empty($data['l10_arr']))
		{
	
			$data['all_level10'] = $this->user_model->get_my_levels_arr_each($data['l10_arr']);
		}




		/* SIP TEAM INCOME START */
		$team_capital_aum =0;$team_count = 0;$team_self_capital = 0;

		$sip_level1_income =0;$sip_level2_income =0;$sip_level3_income =0;$sip_level4_income =0;$sip_level5_income =0;$sip_level6_income =0;
		$sip_level7_income =0;$sip_level8_income =0;$sip_level9_income =0;$sip_level10_income =0;$team_sip=0;

		if($data['user']['sip_balance']>='10000')
		{
			$capital_aum_date = $data['user']['final_sip_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
				$data['sip_level1']	= $this->user_model->get_my_levels($this->session->userdata('admin_id'));
				foreach($data['sip_level1'] as $row)
				{
					if(!empty($row['sip_balance']))
					{
						$per              	  = ceil($setting['level1_incentive'])/100;
						$sip_level1_income   += $row['sip_balance'] * $per;
					}
						

				}
			$data['sip_1'] 	= $sip_level1_income;
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=3)
					{
					foreach($data['sip_level1'] as $row)
					{

					$sip_level_2	= $this->user_model->get_my_levels($row['id']);
					if(!empty($sip_level_2))
					{
						$s2_arr = array();
						foreach($sip_level_2 as $row)
						{
							if(!empty($row['sip_balance']))
							{
								$per          = ceil($setting['level2_incentive'])/100;
				
								$team_sip+= $row['sip_balance'];
 					
						   		$sip_level2_income+= $row['sip_balance'] * $per;
						   		$team_sip+= $row['capital_aum'];
						    	array_push($s2_arr,$row['id']);
						    	$team_count++;
							}
							
						
						}
						$data['sip_2'] = $sip_level1_income + $sip_level2_income;

				}	
			}

			}

		}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=5)
					{
							if(!empty($data['sip_level_2']))
						{
							$s3_arr = array();
							foreach($data['sip_level_2'] as $row)
							{

							$data['sip_level_3']	= $this->user_model->get_my_levels($row['id']);
							if(!empty($data['sip_level_3']))
							{

					$per          = ceil($setting['level3_incentive'])/100;
				
							foreach($data['sip_level_3'] as $row)
							{
								
								if(!empty($row['sip_balance']))
								{

								   $sip_level3_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								   array_push($s3_arr,$row['id']);
									$team_count++;
								}
								
						
						}
							$data['sip_3'] = $sip_level1_income + $sip_level2_income + $sip_level3_income;
						}
					}	
				}

			}

			}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=9)
					{
							if(!empty($data['sip_level_3']))
						{
							foreach($data['sip_level_3'] as $row)
							{
								$data['sip_level_4']	= $this->user_model->get_my_levels($row['id']);
						if(!empty($data['sip_level_4']))
						{
							$s4_arr = array();
						$per    = ceil($setting['level4_incentive'])/100;
				

 						foreach($data['sip_level_4'] as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level4_income+= $row['sip_balance'] * $per;
							   $team_sip+= $row['capital_aum'];
							   array_push($s4_arr,$row['id']);
							   $team_count++;
							}
							
							
						}
					$data['sip_4'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income;
			
				}
			}
		}
		}
	}
					
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=13)
					{
							if(!empty($data['sip_level_4']))
						{
							foreach($data['sip_level_4'] as $row)
							{

						$data['sip_level_5']	= $this->user_model->get_my_levels($row['id']);
						if(!empty($data['sip_level_5']))
						{
							$s5_arr = array();
 						foreach($data['sip_level_5'] as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level5_income+= $row['sip_balance'] * $per;
							   $team_sip+= $row['capital_aum'];
							   array_push($s5_arr,$row['id']);
							   $team_count++;
							}
						
						
						}
						$data['sip_5'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income ;
						}
					}
				}
					
			}
			
			}

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=17)
					{
							if(!empty($data['sip_level_5']))
						{
							foreach($data['sip_level_5'] as $row)
							{
									$data['sip_level_6']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level_6']))
								{
									$s6_arr = array();
									$per    = ceil($setting['level6_incentive'])/100;
								
					 					foreach($data['sip_level_6'] as $row)
										{
											if(!empty($row['sip_balance']))
											{

											   $sip_level6_income+= $row['sip_balance'] * $per;
											   $team_sip+= $row['capital_aum'];
											   	array_push($s6_arr,$row['id']);
											$team_count++;
											}
											
											
										}
										$data['sip_6'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income;
								}

							}
						}
					}
				}

			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=21)
					{
							if(!empty($data['sip_level_6']))
						{
							foreach($data['sip_level_6'] as $row)
							{
								$data['sip_level_7']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level_7']))
								{
									$s7_arr = array();
									$per    = ceil($setting['level7_incentive'])/100;
									
 								foreach($data['sip_level_7'] as $row)
								{
									if(!empty($row['sip_balance']))
									{

								   $sip_level7_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								   array_push($s7_arr,$row['id']);
									$team_count++;

									}
						
								}
									$data['sip_7'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income;
							}

							}
						}
					}
				}
			

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=24)
					{
							if(!empty($data['sip_level_7']))
						{
							foreach($data['sip_level_7'] as $row)
							{
									$data['sip_level_8']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level_8']))
								{
									$s8_arr = array();
									
									$per    = ceil($setting['level8_incentive'])/100;
									$data['sip_level8']	= $this->user_model->get_my_levels($row['id']);
						 		foreach($data['sip_level8'] as $row)
								{
										if(!empty($row['sip_balance']))
											{

												   $sip_level8_income+= $row['sip_balance'] * $per;
												   $team_sip+= $row['capital_aum'];
												   array_push($s8_arr,$row['id']);
												$team_count++;
											}
												
												
									}
											$data['sip_8'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income;
									}
									
								
								}
							}
						}
					}
				


			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=29)
					{
							if(!empty($data['sip_level_8']))
						{
							foreach($data['sip_level_8'] as $row)
							{
								$data['sip_level_9']	= $this->user_model->get_my_levels($row['id']);
						if(!empty($data['sip_level_9']))
						{ 
							$s9_arr = array();

								$per    = ceil($setting['level9_incentive'])/100;
								$data['sip_level_9']	= $this->user_model->get_my_levels($row['id']);
								foreach($data['sip_level_9'] as $row)
								{
									if(!empty($row['sip_balance']))
									{

									   $sip_level9_income+= $row['sip_balance'] * $per;
									   $team_sip+= $row['capital_aum'];
									   	array_push($s9_arr,$row['id']);
									   	$team_count++;
									}
								}
								
								
								
							}
						$data['sip_9'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income;
					
				}
			}
		}
	}
			
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=33)
					{
							if(!empty($data['sip_level_9']))
						{
							foreach($data['sip_level_9'] as $row)
							{
								$data['sip_level_10']	= $this->user_model->get_my_levels($row['id']);
								if(!empty($data['sip_level_10']))
							{
								$s10_arr = array();
							$per    = ceil($setting['level10_incentive'])/100;
 						foreach($data['sip_level_10'] as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level10_income+= $row['sip_balance'] * $per;
							   $team_sip+= $row['capital_aum'];
							   array_push($s10_arr,$row['id']);
								$team_count++;
							}
						
						
						}
					$data['sip_10'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income + $sip_level10_income;
					$data['sip_team_income'] = $data['sip_10'];
					}

							}
						}
					}
			}
			
		}
	}

			if(!empty($data['s2_arr']))
			{
				$data['all_level_sip2'] = $this->user_model->get_my_levels_arr_each($data['s2_arr']);
		
			}
		if(!empty($data['s3_arr']))
		{
			$data['all_level_sip3'] = $this->user_model->get_my_levels_arr_each($data['s3_arr']);
		
		}
		if(!empty($data['s4_arr']))
		{
			$data['all_level_sip4'] = $this->user_model->get_my_levels_arr_each($data['s4_arr']);
		
		}
		if(!empty($data['s5_arr']))
		{
			$data['all_level_sip5'] = $this->user_model->get_my_levels_arr_each($data['s5_arr']);

		}

		if(!empty($data['s6_arr']))
		{
				$data['all_level_sip6'] = $this->user_model->get_my_levels_arr_each($data['s6_arr']);
	
		}
		if(!empty($data['s7_arr']))
		{
			$data['all_level_sip7'] = $this->user_model->get_my_levels_arr_each($data['s7_arr']);
	
		}

		if(!empty($data['s8_arr']))
		{
				$data['all_level_sip8'] = $this->user_model->get_my_levels_arr_each($data['s8_arr']);
		}
		
	if(!empty($data['s9_arr']))
		{
				$data['all_level_sip9'] = $this->user_model->get_my_levels_arr_each($data['s9_arr']);
		
		}

		if(!empty($data['s10_arr']))
		{
	
			$data['all_level_sip10'] = $this->user_model->get_my_levels_arr_each($data['s10_arr']);
		}

		$user_id        = $this->session->userdata('admin_id');
		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));

		$data['my_team'] = $this->user_model->get_all_active($user_id);
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/total_active_account', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function total_inactive_account()
	{
		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
		$data['active_alert'] = $this->user_model->get_active_alert();

		$data['my_team'] = $this->user_model->get_all_inactive($this->session->userdata('admin_id'));
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/total_inactive_account', $data);
		$this->load->view('admin/includes/_footer');
	
	}

	public function download_inactive_account()
	{
		$this->load->library('MYPDF');
	   $settings =  $this->setting_model->get_general_settings();

		$my_team = $this->user_model->get_all_inactive($this->session->userdata('admin_id'));


		// set some text to print
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Total Inactive Account');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print

		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="20%" style="text-align:center">Date</th>
			<th  width="20%" style="text-align:center">Account No</th><th  width="20%" style="text-align:center">Username</th>
			<th  width="20%" style="text-align:center">Capital AUM</th>
			<th  width="20%" style="text-align:center">SIP AUM</th>
			</tr><tbody>';
			$capital_aum=0;
			foreach($my_team as $row)
			{
			$txt.=	'<tr>
				<td>' .$id.'</td>
			<td>' .date_time($row['created_at']).'</td>
			<td>'.$row['account_no'].'</td>
			<td>' .$row['username'].'</td>
			<td>' .$row['capital_aum'].'</td>
			<td>' .$row['sip_balance'].'</td>
			</tr>';

			$id++;}


	$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="4" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="4" style="text-align:center;">'.$words.'</th></tr><tr class="bo"><td colspan="6" class="bo" style="text-align:center;">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["logo"].'" style="height: 106px;margin-left: 40px;width: 106px;color:#fff;"></td></tr><tr><td colspan="6" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

	$pdf->writeHTML($txt, true, false, true, false, '');
			

		// print a block of text using Write()

		// ---------------------------------------------------------
		 ob_clean();
		//Close and output PDF document
		$pdf->Output('Total_Inactive_Account.pdf', 'I');
	}

public function download_total_active_account()
	{
		$this->load->library('MYPDF');
	   $settings =  $this->setting_model->get_general_settings();

		$my_team = $this->user_model->get_all_active($this->session->userdata('admin_id'));

		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		

		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
	  $data['active_alert'] = $this->user_model->get_active_alert();
		$setting = $this->setting_model->get_general_settings();
		$data['setting2'] = $this->setting_model->get_general_settings();
		$team_capital_aum =0;$team_self_capital =0;

		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income = 0;$level9_income = 0;$level10_income = 0;
	    $setting      	= $this->setting_model->get_general_settings();
	    $team_count     =0;
		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
		$user_id      = $this->session->userdata('admin_id');
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
				$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			
				$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per               = ceil($setting['level1_incentive'])/100;
					$level1_income    += $row['capital_aum'] * $per;
					$team_capital_aum += $row['capital_aum'];
	 				$team_self_capital+= $row['self_capital'];;

				    $data['level1_capital']+=$row['capital_aum']; 
				    array_push($l1_arr,$row['id']);
				    
				}
					

			}

			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);
				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{

								 $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level2_capital']+=$row['capital_aum']; 
	 						   $team_self_capital+= $row['self_capital'];;
								$team_count++;
								array_push($l2_arr,$row['id']);
							}
							
						
 						}

 						$data['l2_arr']= $l2_arr;

					}
							$data['level_2'] 		= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					   
 					}
			}	
	}
			if(count($data['team_income_level1'])>=5)
			{

				if(!empty(count($data['team_income_level2'])))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level2'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								 $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
								

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
							}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}

			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level4_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

						       $team_count++;$team_self_capital++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])<=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

							 	$team_count++;$team_self_capital++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])<=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
							array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])<=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;$team_self_capital++;

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])<=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								 $team_count++;$team_self_capital++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])<=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);


							
							 $team_count++;$team_self_capital++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])<=33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
							foreach($data['team_income_level10'] as $row)
							{
								$per       = ceil($setting['level10_incentive'])/100;

								if(!empty($row['capital_aum']))
								{
									

									$per              = ceil($setting['level10_incentive'])/100;
									$level10_income   += $row['capital_aum'] * $per;
									$team_capital_aum+= $row['capital_aum'];
								   	$data['level10_capital']+=$row['capital_aum']; 

									$team_self_capital+= $row['self_capital'];;
									 array_push($l10_arr,$row['id']);



								}
							 	$team_count++;$team_self_capital++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income + $level1_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 
				 	 	$data['team_income_bal'] = $data['level_10'];

					}
				}  

			}

		
			}
		}	
		if(!empty($data['l2_arr']))
		{
			$data['all_level2'] = $this->user_model->get_my_levels_arr_each($data['l2_arr']);

		}

		if(!empty($data['l3_arr']))
		{
			$data['all_level3'] = $this->user_model->get_my_levels_arr_each($data['l3_arr']);
		
		}
		if(!empty($data['l4_arr']))
		{
			$data['all_level4'] = $this->user_model->get_my_levels_arr_each($data['l4_arr']);
		
		}
		if(!empty($data['l5_arr']))
		{
			$data['all_level5'] = $this->user_model->get_my_levels_arr_each($data['l5_arr']);

		}

		if(!empty($data['l6_arr']))
		{
				$data['all_level6'] = $this->user_model->get_my_levels_arr_each($data['l6_arr']);
	
		}
		if(!empty($data['l7_arr']))
		{
			$data['all_level7'] = $this->user_model->get_my_levels_arr_each($data['l7_arr']);
	
		}

		if(!empty($data['l8_arr']))
		{
				$data['all_level8'] = $this->user_model->get_my_levels_arr_each($data['l8_arr']);
		}
		
	if(!empty($data['l9_arr']))
		{
				$data['all_level9'] = $this->user_model->get_my_levels_arr_each($data['l9_arr']);
		
		}

		if(!empty($data['l10_arr']))
		{
	
			$data['all_level10'] = $this->user_model->get_my_levels_arr_each($data['l10_arr']);
		}




		/* SIP TEAM INCOME START */
		$team_capital_aum =0;$team_count = 0;$team_self_capital = 0;

		$sip_level1_income =0;$sip_level2_income =0;$sip_level3_income =0;$sip_level4_income =0;$sip_level5_income =0;$sip_level6_income =0;
		$sip_level7_income =0;$sip_level8_income =0;$sip_level9_income =0;$sip_level10_income =0;$team_sip=0;

		if($data['user']['sip_balance']>='10000')
		{
			$capital_aum_date = $data['user']['final_sip_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
				$data['sip_level1']	= $this->user_model->get_my_levels($this->session->userdata('admin_id'));
				foreach($data['sip_level1'] as $row)
				{
					if(!empty($row['sip_balance']))
					{
						$per              	  = ceil($setting['level1_incentive'])/100;
						$sip_level1_income   += $row['sip_balance'] * $per;
					}
						

				}
			$data['sip_1'] 	= $sip_level1_income;
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=3)
					{
					foreach($data['sip_level1'] as $row)
					{

					$sip_level_2	= $this->user_model->get_my_levels($row['id']);
					if(!empty($sip_level_2))
					{
						$s2_arr = array();
						foreach($sip_level_2 as $row)
						{
							if(!empty($row['sip_balance']))
							{
								$per          = ceil($setting['level2_incentive'])/100;
				
								$team_sip+= $row['sip_balance'];
 					
						   		$sip_level2_income+= $row['sip_balance'] * $per;
						   		$team_sip+= $row['capital_aum'];
						    	array_push($s2_arr,$row['id']);
						    	$team_count++;
							}
							
						
						}
						$data['sip_2'] = $sip_level1_income + $sip_level2_income;

				}	
			}

			}

		}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=5)
					{
							if(!empty($sip_level_2))
						{
							$s3_arr = array();
							foreach($sip_level_2 as $row)
							{

							$sip_level_3	= $this->user_model->get_my_levels($row['id']);
							if(!empty($sip_level_3))
							{

					$per          = ceil($setting['level3_incentive'])/100;
				
							foreach($sip_level_3 as $row)
							{
								
								if(!empty($row['sip_balance']))
								{

								   $sip_level3_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								   array_push($s3_arr,$row['id']);
									$team_count++;
								}
								
						
						}
							$data['sip_3'] = $sip_level1_income + $sip_level2_income + $sip_level3_income;
						}
					}	
				}

			}

			}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=9)
					{
							if(!empty($sip_level_3))
						{
							foreach($sip_level_3 as $row)
							{
								$sip_level_4	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_4))
						{
							$s4_arr = array();
						$per    = ceil($setting['level4_incentive'])/100;
				

 						foreach($sip_level_4 as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level4_income+= $row['sip_balance'] * $per;
							   $team_sip+= $row['capital_aum'];
							   array_push($s4_arr,$row['id']);
							   $team_count++;
							}
							
							
						}
					$data['sip_4'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income;
			
				}
			}
		}
		}
	}
					
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=13)
					{
							if(!empty($sip_level_4))
						{
							foreach($sip_level_4 as $row)
							{

						$sip_level_5	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_5))
						{
							$s5_arr = array();
 						foreach($sip_level_5 as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level5_income+= $row['sip_balance'] * $per;
							   $team_sip+= $row['capital_aum'];
							   array_push($s5_arr,$row['id']);
							   $team_count++;
							}
						
						
						}
						$data['sip_5'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income ;
						}
					}
				}
					
			}
			
			}

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=17)
					{
							if(!empty($sip_level_5))
						{
							foreach($sip_level_5 as $row)
							{
									$sip_level_6	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_6))
								{
									$s6_arr = array();
									$per    = ceil($setting['level6_incentive'])/100;
								
					 					foreach($sip_level_6 as $row)
										{
											if(!empty($row['sip_balance']))
											{

											   $sip_level6_income+= $row['sip_balance'] * $per;
											   $team_sip+= $row['capital_aum'];
											   	array_push($s6_arr,$row['id']);
											$team_count++;
											}
											
											
										}
										$data['sip_6'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income;
								}

							}
						}
					}
				}

			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=21)
					{
							if(!empty($sip_level_6))
						{
							foreach($sip_level_6 as $row)
							{
								$sip_level_7	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_7))
								{
									$s7_arr = array();
									$per    = ceil($setting['level7_incentive'])/100;
									
 								foreach($sip_level_7 as $row)
								{
									if(!empty($row['sip_balance']))
									{

								   $sip_level7_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								   array_push($s7_arr,$row['id']);
									$team_count++;

									}
						
								}
									$data['sip_7'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income;
							}

							}
						}
					}
				}
			

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=24)
					{
							if(!empty($sip_level_7))
						{
							foreach($sip_level_7 as $row)
							{
									$sip_level_8	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_8))
								{
									$s8_arr = array();
									
									$per    = ceil($setting['level8_incentive'])/100;
									$data['sip_level8']	= $this->user_model->get_my_levels($row['id']);
						 		foreach($data['sip_level8'] as $row)
								{
										if(!empty($row['sip_balance']))
											{

												   $sip_level8_income+= $row['sip_balance'] * $per;
												   $team_sip+= $row['capital_aum'];
												   array_push($s8_arr,$row['id']);
												$team_count++;
											}
												
												
									}
											$data['sip_8'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income;
									}
									
								
								}
							}
						}
					}
				


			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=29)
					{
							if(!empty($sip_level_8))
						{
							foreach($sip_level_8 as $row)
							{
								$sip_level_9	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_9))
						{ 
							$s9_arr = array();

								$per    = ceil($setting['level9_incentive'])/100;
								$data['sip_level9']	= $this->user_model->get_my_levels($row['id']);
								foreach($data['sip_level9'] as $row)
								{
									if(!empty($row['sip_balance']))
									{

									   $sip_level9_income+= $row['sip_balance'] * $per;
									   $team_sip+= $row['capital_aum'];
									   	array_push($s9_arr,$row['id']);
									   	$team_count++;
									}
								}
								
								
								
							}
						$data['sip_9'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income;
					
				}
			}
		}
	}
			
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=33)
					{
							if(!empty($sip_level_9))
						{
							foreach($sip_level_9 as $row)
							{
								$sip_level_10	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_10))
							{
								$s10_arr = array();
							$per    = ceil($setting['level10_incentive'])/100;
 						foreach($sip_level_10 as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level10_income+= $row['sip_balance'] * $per;
							   $team_sip+= $row['capital_aum'];
							   array_push($s10_arr,$row['id']);
								$team_count++;
							}
						
						
						}
					$data['sip_10'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income + $sip_level10_income;
					$data['sip_team_income'] = $data['sip_10'];
					}

							}
						}
					}
			}
			
		}
	}

			if(!empty($data['s2_arr']))
			{
				$data['all_level_sip2'] = $this->user_model->get_my_levels_arr_each($data['s2_arr']);
		
			}
		if(!empty($data['s3_arr']))
		{
			$data['all_level_sip3'] = $this->user_model->get_my_levels_arr_each($data['s3_arr']);
		
		}
		if(!empty($data['s4_arr']))
		{
			$data['all_level_sip4'] = $this->user_model->get_my_levels_arr_each($data['s4_arr']);
		
		}
		if(!empty($data['s5_arr']))
		{
			$data['all_level_sip5'] = $this->user_model->get_my_levels_arr_each($data['s5_arr']);

		}

		if(!empty($data['s6_arr']))
		{
				$data['all_level_sip6'] = $this->user_model->get_my_levels_arr_each($data['s6_arr']);
	
		}
		if(!empty($data['s7_arr']))
		{
			$data['all_level_sip7'] = $this->user_model->get_my_levels_arr_each($data['s7_arr']);
	
		}

		if(!empty($data['s8_arr']))
		{
				$data['all_level_sip8'] = $this->user_model->get_my_levels_arr_each($data['s8_arr']);
		}
		
	if(!empty($data['s9_arr']))
		{
				$data['all_level_sip9'] = $this->user_model->get_my_levels_arr_each($data['s9_arr']);
		
		}

		if(!empty($data['s10_arr']))
		{
	
			$data['all_level_sip10'] = $this->user_model->get_my_levels_arr_each($data['s10_arr']);
		}


		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Total Active Account');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
	 $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 	'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  	array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print

		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="20%" style="text-align:center">Date</th>
			<th  width="20%" style="text-align:center">Account No</th><th  width="20%" style="text-align:center">Username</th>
			<th  width="20%" style="text-align:center">Capital AUM/SIP AUM</th>
			</tr><tbody>';
			$id=1;$capital_aum=0;
			if(!empty($data['team_income_level1']))
			{
				foreach($data['team_income_level1'] as $row)
				{ if(!empty($row['capital_aum']))
					{

					$capital_aum+=$row['capital_aum'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				</tr>';

				$id++;}}
				}
			
			if(!empty($data['team_income_level2']))
			{
				foreach($data['team_income_level2'] as $row)
				{ 
					if(!empty($row['capital_aum']))
					{

					$capital_aum+=$row['capital_aum'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				</tr>';

				$id++;}}
				}
			if(!empty($data['team_income_level3']))
			{
				foreach($data['team_income_level3'] as $row)
				{ 
					if(!empty($row['capital_aum']))
					{

					$capital_aum+=$row['capital_aum'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				</tr>';

				$id++;} }
				}	

				if(!empty($data['team_income_level4']))
			{
				foreach($data['team_income_level4'] as $row)
				{ 
					if(!empty($row['capital_aum']))
					{

					$capital_aum+=$row['capital_aum'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				</tr>';

				$id++;}}
				}
			if(!empty($data['team_income_level5']))
			{
				foreach($data['team_income_level5'] as $row)
				{ 
					if(!empty($row['capital_aum']))
					{


					$capital_aum+=$row['capital_aum'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				</tr>';

				$id++;}}
				}
		if(!empty($data['team_income_level6']))
			{
				foreach($data['team_income_level6'] as $row)
				{ 
					if(!empty($row['capital_aum']))
					{

					$capital_aum+=$row['capital_aum'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				</tr>';

				$id++;}}
				}
		if(!empty($data['team_income_level7']))
			{
				foreach($data['team_income_level7'] as $row)
				{ 
					if(!empty($row['capital_aum']))
					{

					$capital_aum+=$row['capital_aum'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				</tr>';

				$id++;}}
				}
			if(!empty($data['team_income_level8']))
			{
				foreach($data['team_income_level8'] as $row)
				{ 
					if(!empty($row['capital_aum']))
					{

					$capital_aum+=$row['capital_aum'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				</tr>';

				$id++;}}
				}
			if(!empty($data['team_income_level9']))
			{
				foreach($data['team_income_level9'] as $row)
				{ 
					if(!empty($row['capital_aum']))
					{

					$capital_aum+=$row['capital_aum'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				</tr>';

				$id++;}}
				}
			if(!empty($data['team_income_level10']))
			{
				foreach($data['team_income_level10'] as $row)
				{ 
					if(!empty($row['capital_aum']))
					{

					$capital_aum+=$row['capital_aum'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['capital_aum'].'</td>
				</tr>';

				$id++;}
				}
				}
			if(!empty($data['sip_level1']))	
			{
				foreach($data['sip_level1'] as $row)
				{ 
					if(!empty($row['sip_balance']))
					{

					$capital_aum+=$row['sip_balance'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['sip_balance'].'</td>
				</tr>';

				$id++;}
			}
				
			}

			if(!empty($sip_level_2))	
			{
				foreach($sip_level_2 as $row)
				{ 
					if(!empty($row['sip_balance']))
					{

					$capital_aum+=$row['sip_balance'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['sip_balance'].'</td>
				</tr>';

				$id++;}
				}
				
			}
			if(!empty($sip_level_3))	
			{
				foreach($sip_level_3 as $row)
				{ 
					if(!empty($row['sip_balance']))
					{

					$capital_aum+=$row['sip_balance'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['sip_balance'].'</td>
				</tr>';

				$id++;}}
				
			}
			if(!empty($sip_level_4))	
			{
				foreach($sip_level_4 as $row)
				{ 
					if(!empty($row['sip_balance']))
					{

					$capital_aum+=$row['sip_balance'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['sip_balance'].'</td>
				</tr>';

				$id++;}}
				
			}
			if(!empty($sip_level_5))	
			{
				foreach($sip_level_5 as $row)
				{ if(!empty($row['sip_balance']))
					{

					$capital_aum+=$row['sip_balance'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['sip_balance'].'</td>
				</tr>';

				$id++;}}
				
			}
			if(!empty($sip_level_6))	
			{
				foreach($sip_level_6 as $row)
				{ if(!empty($row['sip_balance']))
					{

					$capital_aum+=$row['sip_balance'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['sip_balance'].'</td>
				</tr>';

				$id++;}
			}
				
			}
			if(!empty($sip_level_7))	
			{
				foreach($sip_level_7 as $row)
				{ 
					if(!empty($row['sip_balance']))
					{

					$capital_aum+=$row['sip_balance'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['sip_balance'].'</td>
				</tr>';

				$id++;} 
				}
				
			}
			if(!empty($sip_level_8))	
			{
				foreach($sip_level_8 as $row)
				{ 
					if(!empty($row['sip_balance']))
					{

					$capital_aum+=$row['sip_balance'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['sip_balance'].'</td>
				</tr>';

				$id++;}
				}
				
			}

			if(!empty($sip_level_9))	
			{
				foreach($sip_level_9 as $row)
				{ 
					if(!empty($row['sip_balance']))
					{

					$capital_aum+=$row['sip_balance'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['sip_balance'].'</td>
				</tr>';

				$id++;} }
				
			}

			if(!empty($sip_level_10))	
			{
				foreach($sip_level_10 as $row)
				{ 
					if(!empty($row['sip_balance']))
					{

					$capital_aum+=$row['sip_balance'];
				$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				<td style="text-align:center">' .date_time($row['final_sip_date']).'</td>
				<td style="text-align:center">'.$row['account_no'].'</td>
				<td style="text-align:center">' .$row['username'].'</td>
				<td style="text-align:center">' .$row['sip_balance'].'</td>
				</tr>';

				$id++;} }
				
			}

	$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="3" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="3" style="text-align:center;">'.$words.'</th></tr><tr class="bo"><td colspan="6" class="bo">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="6" class="bo">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

			$pdf->writeHTML($txt, true, false, true, false, '');
		// print a block of text using Write()

		// ---------------------------------------------------------
		 ob_clean();
		//Close and output PDF document
		$pdf->Output('Total_Active_Account.pdf', 'I');
		
	}


	public function download_active_capital()
	{
		 $this->load->library('MYPDF');
	   	 $settings =  $this->setting_model->get_general_settings();

		 $my_team = $this->dashboard_model->get_active_capital_aum_ac();
		 $level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income   = 0;$level9_income = 0;$level10_income = 0;
		$data['setting2']   = $this->setting_model->get_general_settings();

	    $setting      	= $this->setting_model->get_general_settings();
	    $team_capital_aum =0;$team_count = 0;$team_self_capital = 0;
		$data['active_alert'] = $this->user_model->get_active_alert();
 		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
 		$data['team_income_level2']=0;$data['team_income_level3']=0;$data['team_income_level4'] =0;$data['team_income_level5']=0;$data['team_income_level6']=0;$data['team_income_level7']=0;$data['team_income_level8']=0;$data['team_income_level9']=0;$data['team_income_level10']=0;
		$user_id = $this->session->userdata('admin_id');
		$data['user_id'] = $user_id;
		$data['user']	= $this->user_model->get_user_detail($user_id);
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			
			$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
                  	
                   $date = date('Y-m-d',strtotime($row['final_cap_date']));

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<30)
							{
				    		$level1_income = $level1_income/30;
				    		$level1_income =  round($level1_income * $interval);
							}
				    array_push($l1_arr,$row['id']);
				    
				}
					

			}
			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);
				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row1)
 						{
 							if(!empty($row1['capital_aum']))
							{


							   $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row1['capital_aum'] * $per;
							   $team_capital_aum += $row1['capital_aum'];
							    $data['level2_capital']+=$row1['capital_aum']; 
	 						   $team_self_capital+= $row1['self_capital'];
                              	$date   = date('Y-m-d',strtotime($row1['final_cap_date']));
                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    		$level2_income = $level2_income/30;
				    		$level2_income =  round($level2_income * $interval);
							}
								$team_count++;
								array_push($l2_arr,$row1['id']);
							}
							
						
 						}
						
 						$data['l2_arr']= $l2_arr;


					}
						$data['level_2'] 		= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 

 					}
			}	

	}

			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level2'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								 $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
							   $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    		$level3_income = $level3_income/30;
				    		$level3_income =  round($level3_income * $interval);
							}

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
						}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level4_incentive'])/100;
								$level4_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								$date   = date('Y-m-d',strtotime($row['final_cap_date']));
                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level4_income = $level4_income/30;
				    			$level4_income =  round($level4_income * $interval);
							}
						       $team_count++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								$date   = date('Y-m-d',strtotime($row['final_cap_date']));
                               $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level5_income = $level5_income/30;
				    			$level5_income =  round($level5_income * $interval);
							}
							 	$team_count++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];
                              
                              $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level6_income = $level6_income/30;
				    			$level6_income =  round($level6_income * $interval);
							}
							array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;
                          
                           	   $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level7_income = $level7_income/30;
				    			$level7_income =  round($level7_income * $interval);
							}

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level8_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

                              $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level8_income = $level8_income/30;
				    			$level8_income =  round($level8_income * $interval);
							}
								 $team_count++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level9_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);
							  $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level9_income = $level9_income/30;
				    			$level9_income =  round($level9_income * $interval);
							}

							
							 $team_count++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])==33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level10_incentive'])/100;
								$level10_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level10_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l10_arr,$row['id']);
								 $date   = date('Y-m-d',strtotime($row['final_cap_date']));
                              $date1 =  new DateTime($date);
                              $date2 = new DateTime(date('Y-m-d'));
                              $interval = $date1->diff($date2)->format("%a");  

                              if($interval<30)
							{
				    			$level10_income = $level10_income/30;
				    			$level10_income =  round($level10_income * $interval);
							}
				

							}
							 $team_count++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 

					}
				}  

			}

		}


		}

		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Total Active Account');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  	array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);
 	
		// add a page
		$pdf->AddPage();
		$id=1;$capital_aum=0;
		// set some text to print
		$txt = '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th><th width="20%" style="text-align:center">Date</th>
			<th  width="20%" style="text-align:center">Account No</th><th  width="20%" style="text-align:center">Username</th>
			<th  width="20%" style="text-align:center">Capital AUM</th>
			</tr><tbody>';
			
			/*foreach($my_team as $row)
			{ $capital_aum+=$row['capital_aum'];

			$txt.=	'<tr>
				<td style="text-align:center">' .$id.'</td>
			<td style="text-align:center">' .date_time($row['created_at']).'</td>
			<td style="text-align:center">'.$row['account_no'].'</td>
			<td style="text-align:center">' .$row['username'].'</td>
			<td style="text-align:center">' .$row['capital_aum'].'</td>
			</tr>';

			$id++;}*/
			$id=1;
			if(!empty($data['team_income_level1']))
						{
						foreach($data['team_income_level1'] as $row)
						{
							$per       = ceil($setting['level1_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
						if(!empty($row['final_cap_date']))
						{

								$capital_aum+=$row['capital_aum'];

						$txt.=	'<tr>
							<td style="text-align:center">' .$id.'</td>
						<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
						<td style="text-align:center">'.$row['account_no'].'</td>
						<td style="text-align:center">' .$row['username'].'</td>
						<td style="text-align:center">' .$row['capital_aum'].'</td>
						</tr>';
						$id++;
						}
					}
				}
			}

				if(!empty($data['team_income_level2']))
						{
						foreach($data['team_income_level2'] as $row)
						{
							$per       = ceil($setting['level2_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
						if(!empty($row['final_cap_date']))
						{		
								$capital_aum+=$row['capital_aum'];

						$txt.=	'<tr>
							<td style="text-align:center">' .$id.'</td>
						<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
						<td style="text-align:center">'.$row['account_no'].'</td>
						<td style="text-align:center">' .$row['username'].'</td>
						<td style="text-align:center">' .$row['capital_aum'].'</td>
						</tr>';
						$id++;
						}
					}
					}
				}
				if(!empty($data['team_income_level3']))
						{
						foreach($data['team_income_level3'] as $row)
						{
							$per       = ceil($setting['level3_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
							if(!empty($row['final_cap_date']))
						{	
								$capital_aum+=$row['capital_aum'];

						$txt.=	'<tr>
							<td style="text-align:center">' .$id.'</td>
						<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
						<td style="text-align:center">'.$row['account_no'].'</td>
						<td style="text-align:center">' .$row['username'].'</td>
						<td style="text-align:center">' .$row['capital_aum'].'</td>
						</tr>';
						$id++;
						}
					}
					}
				}
			if(!empty($data['team_income_level4']))
						{
						foreach($data['team_income_level4'] as $row)
						{
							$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
						if(!empty($row['final_cap_date']))
						{		
								$capital_aum+=$row['capital_aum'];

						$txt.=	'<tr>
							<td style="text-align:center">' .$id.'</td>
						<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
						<td style="text-align:center">'.$row['account_no'].'</td>
						<td style="text-align:center">' .$row['username'].'</td>
						<td style="text-align:center">' .$row['capital_aum'].'</td>
						</tr>';
						$id++;
						}
					}
					}
				}
			if(!empty($data['team_income_level5']))
						{
						foreach($data['team_income_level5'] as $row)
						{
							$per       = ceil($setting['level5_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
						if(!empty($row['final_cap_date']))
						{		
								$capital_aum+=$row['capital_aum'];

						$txt.=	'<tr>
							<td style="text-align:center">' .$id.'</td>
						<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
						<td style="text-align:center">'.$row['account_no'].'</td>
						<td style="text-align:center">' .$row['username'].'</td>
						<td style="text-align:center">' .$row['capital_aum'].'</td>
						</tr>';
						$id++;
						}
					}
					}
				}

					if(!empty($data['team_income_level6']))
					{
						foreach($data['team_income_level6'] as $row)
						{
							$per       = ceil($setting['level6_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
						if(!empty($row['final_cap_date']))
						{		
								$capital_aum+=$row['capital_aum'];

						$txt.=	'<tr>
							<td style="text-align:center">' .$id.'</td>
						<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
						<td style="text-align:center">'.$row['account_no'].'</td>
						<td style="text-align:center">' .$row['username'].'</td>
						<td style="text-align:center">' .$row['capital_aum'].'</td>
						</tr>';
						$id++;
						}
					}
					}
				}

				if(!empty($data['team_income_level7']))
					{
						foreach($data['team_income_level7'] as $row)
						{
							$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
						if(!empty($row['final_cap_date']))
						{		
								$capital_aum+=$row['capital_aum'];

						$txt.=	'<tr>
							<td style="text-align:center">' .$id.'</td>
						<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
						<td style="text-align:center">'.$row['account_no'].'</td>
						<td style="text-align:center">' .$row['username'].'</td>
						<td style="text-align:center">' .$row['capital_aum'].'</td>
						</tr>';
						$id++;
						}
					}
					}
				}

				if(!empty($data['team_income_level8']))
					{
						foreach($data['team_income_level8'] as $row)
						{
							$per       = ceil($setting['level8_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
						if(!empty($row['final_cap_date']))
						{		
								$capital_aum+=$row['capital_aum'];

						$txt.=	'<tr>
							<td style="text-align:center">' .$id.'</td>
						<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
						<td style="text-align:center">'.$row['account_no'].'</td>
						<td style="text-align:center">' .$row['username'].'</td>
						<td style="text-align:center">' .$row['capital_aum'].'</td>
						</tr>';
						$id++;
						}
					}
					}
				}
				if(!empty($data['team_income_level9']))
					{
						foreach($data['team_income_level9'] as $row)
						{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
						if(!empty($row['final_cap_date']))
						{		
								$capital_aum+=$row['capital_aum'];

						$txt.=	'<tr>
							<td style="text-align:center">' .$id.'</td>
						<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
						<td style="text-align:center">'.$row['account_no'].'</td>
						<td style="text-align:center">' .$row['username'].'</td>
						<td style="text-align:center">' .$row['capital_aum'].'</td>
						</tr>';
						$id++;
						}
					}
					}
				}

				if(!empty($data['team_income_level10']))
					{
						foreach($data['team_income_level10'] as $row)
						{
							$per       = ceil($setting['level10_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
						if(!empty($row['final_cap_date']))
						{		
								$capital_aum+=$row['capital_aum'];

						$txt.=	'<tr>
							<td style="text-align:center">' .$id.'</td>
						<td style="text-align:center">' .date_time($row['final_cap_date']).'</td>
						<td style="text-align:center">'.$row['account_no'].'</td>
						<td style="text-align:center">' .$row['username'].'</td>
						<td style="text-align:center">' .$row['capital_aum'].'</td>
						</tr>';
						$id++;
						}
					}
					}
				}
						
	$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="3" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2" style="text-align:center;">Total In Words:</th><th colspan="3" style="text-align:center;">'.$words.'</th></tr><tr class="bo"><td colspan="6" class="bo">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["stamp"].'" style="height: 56px;margin-left: 40px;width: 76px;color:#fff;"></td></tr><tr><td colspan="6" class="bo">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	


	$txt.='</body></html>';
	 

			$pdf->writeHTML($txt, true, false, true, false, '');
		// print a block of text using Write()

		// ---------------------------------------------------------
		 ob_clean();
		//Close and output PDF document
		$pdf->Output('Active_Capital.pdf', 'I');
	}


function getIndianCurrency(float $number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}

	/* END  DASHBOARD REPORTS */

	public function search_result()
	{
		$search_string  = $this->input->get('search_string');
		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
		$data['active_alert'] = $this->user_model->get_active_alert();

		$data['search_result']	= $this->user_model->get_search_result($search_string);
		if(!empty($data['search_result']))
		{
			$data['result'] = $data['search_result'];
		}else
		{
			$data['result'] = "";
		}

		$this->load->view('admin/includes/_header',$data);
		$this->load->view('admin/users/search_result');
		$this->load->view('admin/includes/_footer');
	}


	/* Activity Log */
	public function activity()
	{
		// $records = $this->activity_model->get_activity_log();
		// var_dump($records);exit();

		$data['title'] = 'User Activity Log';
		$data['title'] = 'User Activity Log';
		if(!empty($this->input->post('from_date')) && !empty($this->input->post('to_date')))
		{
			$data['user_activity'] = $this->activity_model->get_activity_log($this->input->post('from_date') ,$this->input->post('to_date'));

		}else
		{
			$data['user_activity'] = $this->activity_model->get_activity_log(0,0);	
		}
		
		$data['active_alert'] = $this->user_model->get_active_alert();

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/activity/activity-list', $data);
		$this->load->view('admin/includes/_footer');
	}
	/* END Activity Log */

	public function dashboard()
	{
		
		$data['title'] = 'Dashboard';
		
		$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
			$data['title'] = 'Dashboard';
			$data['all_users'] = $this->dashboard_model->get_all_users(0,0);
			$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
			$data['active_alert'] = $this->user_model->get_active_alert();
			$data['check_verify'] = $this->user_model->check_self_Capital_verify($this->session->userdata('admin_id'));
			$data['check_verify_sip'] = $this->user_model->check_sip_verify($this->session->userdata('admin_id'));
 			$data['fixed_capital'] = $this->dashboard_model->get_fixed_capital($this->session->userdata('admin_id'));

			$data['active_users'] 	= $this->dashboard_model->get_active_users(0,0);

			$data['deactive_users'] = $this->dashboard_model->get_deactive_users(0,0);

	   	$data['my_fund'] 		= $this->dashboard_model->get_my_fund($this->session->userdata('admin_id'));

	   	$data['team_income'] 	= $this->dashboard_model->get_my_fund($this->session->userdata('admin_id'));

	   	$data['team_income']	= $this->user_model->get_team($this->session->userdata('admin_id'));
	   	$data['setting']      = $this->setting_model->get_general_settings();
	   	$team_capital_aum =0;$team_self_capital =0;

			$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income = 0;$level9_income = 0;$level10_income = 0;
	    $setting      	= $this->setting_model->get_general_settings();
	    $team_count     =0;
			$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
 		$data['withdraw_cap'] = $this->fund_model->capital_withdraw($this->session->userdata('admin_id'));
 		$data['withdraw_sip'] = $this->fund_model->sip_withdraw($this->session->userdata('admin_id'));
 		$todays_level1_in=0;$todays_level2_in=0;$todays_level3_in=0;$todays_level4_in=0;$todays_level5_in=0;$todays_level6_in=0;
 		$todays_level7_in=0;$todays_level8_in=0;$todays_level9_in=0;$todays_level10_in=0;
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));

			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{

				$user_id  = $this->session->userdata('admin_id');
				$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();$level1_incomea=0;
			foreach($data['team_income_level1'] as $row)
			{
				$level1_incomes=0;
				if(!empty($row['capital_aum']))
				{
                  
                  
					if(!empty($row['final_cap_date']))
				   {	
									
					$per               = round($setting['level1_incentive'],2)/100;
					$level1_income    = $row['capital_aum'] * $per;
					$data['level1_capital']+=$row['capital_aum'];
				    $level1_incomes    = $row['capital_aum'] * $per;
				    /* DAILY TEAM INCOME */ 
				    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                  	$date = date('Y-m-d', strtotime($date . ' +1 day'));
                  	if(date('Y-m-d') > $date)
                  		{
                  			$team_capital_aum += $row['capital_aum'];
	 			    		$team_self_capital+= $row['self_capital'];;
				    
				    	$date1 = new DateTime($date);
						$date2 = new DateTime(date('Y-m-d'));
						$interval = $date1->diff($date2)->format("%a");  
						if($interval<=30 && $interval>0)
						{
							
				    		$level1_income  = $level1_incomes/30;

				    		$level1_income  =  bcdiv($level1_income * $interval,1,2);

				    	}
                         // echo $level1_income;die();

						if($interval>30)
						{
							
				    		$level1_income = $level1_incomes/30;
				    		$level1_income =  bcdiv($level1_income * $interval,1,2);
						}

						$level1_incomea += $level1_income ;
                        $tlevel1_income   = $row['capital_aum'] * $per;

						if($interval>0)
				    		{
				    			$todays_level1_in = bcdiv($tlevel1_income/30,1,2);
							}
					
                  		array_push($l1_arr,$row['id']);

				   	$team_count++;
				   }
 				}
				}

			}


		   $data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3   )
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);

				$per          = ceil($setting['level2_incentive'])/100;
				$level2_incomea=0;
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							$level2_incomes=0;
 							if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
									{	
									
							   $per              = round($setting['level2_incentive'],2)/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $level2_incomes   = $row['capital_aum'] * $per;
							    $data['level2_capital']+=$row['capital_aum']; 
								/* DAILY TEAM INCOME*/
                              
                                $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
							if(date('Y-m-d')>$date)
							{
							  $team_capital_aum += $row['capital_aum'];
							  $team_self_capital+= $row['self_capital'];;
								//$team_count++;

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
					    		$level2_income = $level2_incomes/30;
					    		$level2_income =  bcdiv($level2_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $level2_income  = $level2_incomes/30;
			                  $level2_income  =  bcdiv($level2_income * $interval,1,2);
			              	}
			              	$level2_incomea += $level2_income ;
                            $tlevel2_income   = $row['capital_aum'] * $per;
   
							if($interval>0)
				    		{
				    			$todays_level2_in = bcdiv($tlevel2_income /30,1,2);
							}
								array_push($l2_arr,$row['id']);
						       $team_count++;
						    }   
							}
							
						}	
 						}

 						$data['l2_arr']= $l2_arr;

					}
						$data['level_2'] 		= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					   
 					}
			}	
	}

			if(count($data['team_income_level1'])>=5 )
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;
						$level3_incomea=0;
						foreach($data['team_income_level2'] as $row)
 						{
 							$level3_incomes=0;
							if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
									{	
									

								$per              = round($setting['level3_incentive'],2)/100;
								 $level3_income   += $row['capital_aum'] * $per;
								 $level3_incomes  = $row['capital_aum'] * $per;
							    $data['level3_capital']+=$row['capital_aum']; 

								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d')>$date)
								{
							   $team_self_capital+= $row['self_capital'];;

						       $team_capital_aum += $row['capital_aum'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$level3_income = $level3_incomes/30;
				    		$level3_income =  bcdiv($level3_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $level3_income  = $level3_incomes/30;
			                  $level3_income  =  bcdiv($level3_income * $interval,1,2);
			              	}

							$level3_incomea += $level3_income ;
                            $tlevel3_income   = $row['capital_aum'] * $per;
        
						   if($interval>0)
				    		{
				    			$todays_level3_in = bcdiv($tlevel3_income /30,1,2);
							}
							 $team_count++;
							 array_push($l3_arr,$row['id']);
							}
							}
							}
						 	$data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;
				 	 }	
					}
				}  

			}


			if(count($data['team_income_level1'])>=9 )
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();

					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);
						$level4_incomea=0;
						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						$level4_incomes=0;

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
							if(!empty($row['final_cap_date']))
									{	
										

								$per              = round($setting['level4_incentive'],2)/100;
								$level4_income   += $row['capital_aum'] * $per;
								$level4_incomes  = $row['capital_aum'] * $per;

							  $data['level4_capital']+=$row['capital_aum']; 


								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d')>$date)
								{

								$team_capital_aum+= $row['capital_aum'];
								$team_self_capital+= $row['self_capital'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
                            {
				    			$level4_income = $level4_incomes/30;
				    			$level4_income =  bcdiv($level4_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $level4_income  = $level4_incomes/30;
			                  $level4_income  =  bcdiv($level4_income * $interval,1,2);
			              	}
			              	$level4_incomea += $level4_income ;
                           $tlevel4_income   = $row['capital_aum'] * $per;
        
			              	if($interval>0)
				    		{
				    			$todays_level4_in = bcdiv($tlevel4_income /30,1,2);

				    		}

						       $team_count++;
							 	array_push($l4_arr,$row['id']);
							} 	
							} 	
							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13 )
			{

				if(!empty($data['team_income_level4']))
				{
					$level5_incomea=0;
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;

							$level5_incomes =0;
							if(!empty($row['capital_aum']))
							{
								
								if(!empty($row['final_cap_date']))
									{	
									
								$per              = round($setting['level5_incentive'],2)/100;
								$level5_income   += $row['capital_aum'] * $per;
								$level5_incomes  = $row['capital_aum'] * $per;

							   	$data['level5_capital']+=$row['capital_aum']; 


								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d')>$date)
								{	

								$team_capital_aum+= $row['capital_aum'];
								$team_self_capital+= $row['self_capital'];;

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    			$level5_income  = $level5_incomes/30;
				    			$level5_income =  bcdiv($level5_income * $interval,1,2);
							}

							if($interval>30 && $interval>0)
              				{
			                  $level5_income   = $level5_incomes/30;
			                  $level5_income  =  bcdiv($level5_income * $interval,1,2);
			              	}
							$level5_incomea += $level5_income ;
                            $tlevel5_income   = $row['capital_aum'] * $per;
       
							if($interval>0)
				    		{
				    			$todays_level5_in = bcdiv($tlevel5_income /30,1,2);

				    		}
							 	$team_count++;
							 	array_push($l5_arr,$row['id']);
							} 	
							} 	
							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])>=17 )
			{

				if(!empty($data['team_income_level5']))
				{
					$level6_incomea=0;
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							$level6_incomes=0;
							if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
									{	
										

								$per              = round($setting['level6_incentive'],2)/100;
								$level6_income   += $row['capital_aum'] * $per;
								$level6_incomes  = $row['capital_aum'] * $per;

							   	$data['level6_capital']+=$row['capital_aum']; 

							   	/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d')>$date)
							{
								$team_capital_aum+= $row['capital_aum'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$level6_income = $level6_incomes/30;
				    		$level6_income =  bcdiv($level6_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $level6_income   = $level6_incomes/30;
			                  $level6_income  =  bcdiv($level6_income * $interval,1,2);
			              	}
							$level6_incomea += $level6_income ;
                           $tlevel6_income   = $row['capital_aum'] * $per;
         
							if($interval>0)
				    		{
				    			$todays_level6_in = bcdiv($tlevel6_income /30,1,2);

				    		}
								$team_self_capital+= $row['self_capital'];;
								array_push($l6_arr,$row['id']);

 							$team_count++;
 						}
							}
							}	
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21 )
			{

				if(!empty($data['team_income_level6']))
				{
					$level7_incomea=0;
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							$level7_incomes=0;
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								
								if(!empty($row['final_cap_date']))
									{	
									
								$per              = round($setting['level7_incentive'],2)/100;
								$level7_income   += $row['capital_aum'] * $per;
								$level7_incomes   = $row['capital_aum'] * $per;

							   	$data['level7_capital']+=$row['capital_aum']; 


									/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d')>$date)
								{
								$team_capital_aum+= $row['capital_aum'];
								$team_self_capital+= $row['self_capital'];;

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30)
							{
				    		$level7_income = $level7_incomes/30;
				    		$level7_income =  bcdiv($level7_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $level7_income  = $level7_incomes/30;
			                  $level7_income  =  bcdiv($level7_income * $interval,1,2);
			              	}
			              	$level7_incomea += $level7_income ;
                           $tlevel7_income   = $row['capital_aum'] * $per;
      
			              	if($interval>0)
				    		{
				    			$todays_level7_in = bcdiv($tlevel7_income /30,1,2);

				    		}

								$team_count++;

								array_push($l7_arr,$row['id']);
							}	
							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 
				 	 }	
					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$level8_incomea=0;
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{
							$level8_incomes=0;
							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								
								if(!empty($row['final_cap_date']))
									{	
									
								$per              = round($setting['level8_incentive'],2)/100;
								$level8_income   += $row['capital_aum'] * $per;
								$level8_incomes   = $row['capital_aum'] * $per;

							   	$data['level8_capital']+=$row['capital_aum']; 


								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
									$date = date('Y-m-d', strtotime($date . ' +1 day'));
					if(date('Y-m-d')>$date)
					{
								$team_capital_aum+= $row['capital_aum'];
								$team_self_capital+= $row['self_capital'];;

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30)
							{
				    		$level8_income = $level8_incomes/30;
				    		$level8_income =  bcdiv($level8_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $level8_income  = $level8_incomes/30;
			                  $level8_income  =  bcdiv($level8_income * $interval,1,2);
			              	}
			              	$level8_incomea += $level8_income ;
                            $tlevel8_income   = $row['capital_aum'] * $per;

			              	if($interval>0)
				    		{
				    			$todays_level8_in = bcdiv($tlevel8_income /30,1,2);

				    		}
								 $team_count++;
								 array_push($l8_arr,$row['id']);

							}	 
							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 
				 	 }	
					}
				}  

			}


			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$level9_incomea=0;
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							$level9_incomes=0;
							foreach($data['team_income_level9'] as $row)
							{
							$per       = round($setting['level9_incentive'],2)/100;

						if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
									{	
									

								$per              = round($setting['level9_incentive'],2)/100;
								$level9_income   += $row['capital_aum'] * $per;
							   	$data['level9_capital']+=$row['capital_aum']; 
							   	$level9_incomes = $row['capital_aum'] * $per;

								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
							$date = date('Y-m-d', strtotime($date . ' +1 day'));
							if(date('Y-m-d')>$date)
							{
								$team_capital_aum+= $row['capital_aum'];
								$team_self_capital+= $row['self_capital'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30)
							{
				    		$level9_income = $level9_incomes/30;
				    		$level9_income =  bcdiv($level9_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $level9_income  = $level9_incomes/30;
			                  $level9_income  =  bcdiv($level9_income * $interval,1,2);
			              	}

			              	$level9_incomea += $level9_income ;
                            $tlevel9_income   = $row['capital_aum'] * $per;

			              	if($interval>0)
				    		{
				    			$todays_level9_in = bcdiv($tlevel9_income /30,1,2);

				    		}
								 array_push($l9_arr,$row['id']);


							
							 $team_count++;
							}
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 
				 	 }	
					}
				}  



			if(count($data['team_income_level1'])>=33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();$level10_incomea=0;
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
							foreach($data['team_income_level10'] as $row)
							{
								$per       = round($setting['level10_incentive'],2)/100;

								if(!empty($row['capital_aum']))
								{
									if(!empty($row['final_cap_date']))
									{	
									$per              = round($setting['level10_incentive'],2)/100;
									$level10_income   += $row['capital_aum'] * $per;
								   	$data['level10_capital']+=$row['capital_aum']; 
							   	   $level10_incomes = $row['capital_aum'] * $per;


									/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d')>$date)
								{
								 $team_capital_aum+= $row['capital_aum'];
								 $team_self_capital+= $row['self_capital'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30)
							{
				    		$level10_income = $level10_incomes/30;
				    		$level10_income =  bcdiv($level10_income * $interval,1,2);
							}
                                  
							$tlevel10_income   = $row['capital_aum'] * $per;

							if($interval>0)
				    		{
				    			$todays_level10_in = bcdiv($tlevel10_income /30,1,2);

				    		}

							if($interval>30)
              				{
			                  $level10_income  = $level10_incomes/30;
			                  $level10_income  =  bcdiv($level10_income * $interval,1,2);
			              	}
							$level10_incomea +=$level10_income;

						   array_push($l10_arr,$row['id']);

							$team_count++;
						}	
						}
						}	 	
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 
				 	 	$data['team_income_bal'] = $data['level_10'];

					}
				}  

			}

		
			}
		}


		if(!empty($level1_incomea) && empty($level2_incomea))
		{
			$data['team_income_bal']  = $level1_incomea;
		}
		else if(!empty($level2_incomea) && !empty($level1_incomea) && empty($level3_incomea))
		{
			$data['team_income_bal']  = $level1_incomea + $level2_incomea;

		}else if(!empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level4_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea;

		}else if(!empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level5_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea;
		}else if(!empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level6_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea;

		}else if(!empty($level6_incomea) && !empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level7_incomea))
		{
			$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea + $level6_incomea;

		}
		else if(!empty($level7_incomea) && !empty($level6_incomea) && !empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level8_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea + $level6_incomea + $level7_incomea;
		}else if(!empty($level8_incomea) && !empty($level7_incomea) && !empty($level6_incomea) && !empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level9_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea + $level6_incomea +  $level7_incomea + $level8_incomea;

		}else if(!empty($level9_incomea) && !empty($level8_incomea) && !empty($level7_incomea) && !empty($level6_incomea) && !empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level10_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea + $level6_incomea +  $level7_incomea + $level8_incomea + $level9_incomea;
		}else if(!empty($level10_incomea) && !empty($level9_incomea) && !empty($level8_incomea) && !empty($level7_incomea) && !empty($level6_incomea) && !empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea + $level6_incomea +  $level7_incomea + $level8_incomea + $level9_incomea +  $level10_incomea;

		}




		if(!empty($data['team_income_bal']))
		{
			$data['team_income_bal'] = $data['team_income_bal'];
		}else if(!empty($data['user']['team_income']))
		{
			$data['team_income_bal'] = $data['user']['team_income'];
		}else
		{
			$data['team_income_bal']=0;
		}

		$todays_sip_level1_in =0;$todays_sip_level2_in=0;$todays_sip_leve3_in=0;$todays_sip_level4_in=0;$todays_sip_level5_in=0;$todays_sip_level6_in=0;$todays_sip_level7_in=0;$todays_sip_level8_in=0;$todays_sip_level9_in=0;$todays_sip_level10_in=0;

		/* SIP TEAM INCOME START */
		$sip_level1_income =0;$sip_level2_income =0;$sip_level3_income =0;$sip_level4_income =0;$sip_level5_income =0;$sip_level6_income =0;
		$sip_level7_income =0;$sip_level8_income =0;$sip_level9_income =0;$sip_level10_income =0;$team_sip=0;
		$sip_level1_incomea=0;$sip_level2_incomea=0;$sip_level3_incomea=0;$sip_level4_incomea=0;$sip_level5_incomea=0;$sip_level6_incomea=0;
$sip_level7_incomea=0;$sip_level8_incomea=0;$sip_level9_incomea=0;$sip_level10_incomea=0;
	$todays_sip_level1_in=0;$todays_sip_level2_in=0;$todays_sip_level3_in=0;$todays_sip_level4_in=0;$todays_sip_level5_in=0;$todays_sip_level6_in=0;$todays_sip_level7_in=0;$todays_sip_level8_in=0;$todays_sip_level9_in=0;$todays_sip_level10_in=0;

		if($data['user']['sip_balance']>='10000')
		{
			$capital_aum_date 	= $data['user']['final_sip_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			$data['sip_level1']	= $this->user_model->get_my_levels($this->session->userdata('admin_id'));
			$sip_active = 0;
			foreach($data['sip_level1'] as $row)
			{
				if(!empty($row['sip_balance']))
				{
					if(!empty($row['final_sip_date']))
					{
					$per              	  = round($setting['level1_incentive'],2)/100;
					$sip_level1_income   += $row['sip_balance'] * $per;
					$sip_level1_incomes   = $row['sip_balance'] * $per;
						/* DAILY TEAM INCOME*/
			$date = date('Y-m-d',strtotime($row['final_sip_date']));
			$date = date('Y-m-d', strtotime($date . ' +1 day'));
			if(date('Y-m-d')>$date)
			{
								$team_sip+= $row['sip_balance'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$sip_level1_income = $sip_level1_incomes/30;
				    		$sip_level1_income =  bcdiv($sip_level1_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $sip_level1_income  = $sip_level1_incomes/30;
			                  $sip_level1_income  =  bcdiv($sip_level1_income * $interval,1,2);
			              	}
			              	$sip_level1_incomea += $sip_level1_income;
              				$tsip_level1_income = $row['sip_balance'] * $per;
			              	if($interval>0)
			              	{
			              		$todays_sip_level1_in=bcdiv($tsip_level1_income/30,1,2);
			              	}
                            $sip_active++;

			    }          	
				}
				}	

			}
			$data['sip_1'] 	= $sip_level1_income;
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=3)
					{
                          
					foreach($data['sip_level1'] as $row)
					{

					$sip_level_2	= $this->user_model->get_my_levels($row['id']);
					if(!empty($sip_level_2))
					{
						$sip_level2_incomea=0;
						foreach($sip_level_2 as $row)
						{
							if(!empty($row['sip_balance']))
							{
								if(!empty($row['final_sip_date']))
								{
								$per          = round($setting['level2_incentive'],2)/100;
				
 					
						   	$sip_level2_income+= $row['sip_balance'] * $per;
						   	$sip_level2_incomes   = $row['sip_balance'] * $per;

						   		/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_sip_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
							if(date('Y-m-d')>$date)
							{
								$team_sip+= $row['sip_balance'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$sip_level2_income = $sip_level2_incomes/30;
				    		$sip_level2_income =  bcdiv($sip_level2_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $sip_level2_income  = $sip_level2_incomes/30;
			                  $sip_level2_income      =  bcdiv($sip_level2_income * $interval,1,2);
			              	}
			              	$sip_level2_incomea +=$sip_level2_income;
                            $tsip_level2_income = $row['sip_balance'] * $per;   
			              	if($interval>0)
			              	{
			              		$todays_sip_level2_in=bcdiv($tsip_level2_income/30,1,2);
			              	}
                            $sip_active++;
						}
							
						}
						}
						$data['sip_2'] = $sip_level1_income + $sip_level2_income;
					}
				}	
			}

			}

		}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=5)
					{
							if(!empty($sip_level_2))
						{
							foreach($sip_level_2 as $row)
							{

								$sip_level_3	= $this->user_model->get_my_levels($row['id']);
							if(!empty($sip_level_3))
							{

							$per          = round($setting['level3_incentive'],2)/100;
							$sip_level3_incomea=0;
							foreach($sip_level_3 as $row)
							{
								
								if(!empty($row['sip_balance']))
								{
									if(!empty($row['final_sip_date']))
									{
								   $sip_level3_income+= $row['sip_balance'] * $per;
							 		$sip_level3_incomes = $row['sip_balance'] * $per;	   

								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_sip_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d')>$date)
							{
								$team_sip+= $row['capital_aum'];


								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$sip_level3_income = $sip_level3_incomes/30;
				    		$sip_level3_income =  bcdiv($sip_level3_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $sip_level3_income  = $sip_level3_incomes/30;
			                  $sip_level3_income  =  bcdiv($sip_level3_income * $interval,1,2);
			              	}
			              	if($interval>0)
			              	{
                                 $tsip_level3_income = $row['sip_balance'] * $per;   

			              		if(!empty($tsip_level3_income))
			              		{
			              			$todays_sip_level3_in = bcdiv($tsip_level3_income/30,1,2);
			              		}
			              		
			              	}else
			              	{
			              		$todays_sip_level3_in = 0;
			              	}
			              	$sip_level3_incomea+=$sip_level3_income ;
                            $sip_active++;
			            }  	
						}
						}		
						
						}
							$data['sip_3'] = $sip_level1_income + $sip_level2_income + $sip_level3_income;
						}
					}	
				}

			}

			}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=9)
					{
							if(!empty($sip_level_3))
						{
							foreach($sip_level_3 as $row)
							{
								$sip_level_4	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_4))
						{

						$per    = round($setting['level4_incentive'],2)/100;
						$sip_level4_incomea =0;

 						foreach($sip_level_4 as $row)
						{
							if(!empty($row['sip_balance']))
							{
								if(!empty($row['final_sip_date']))
								{
							   $sip_level4_income+= $row['sip_balance'] * $per;
							   $sip_level4_incomes = $row['sip_balance'] * $per;
							   /* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_sip_date']));
							$date = date('Y-m-d', strtotime($date . ' +1 day'));
							if(date('Y-m-d')>$date)
							{
								$team_sip+= $row['capital_aum'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$sip_level4_income = $sip_level4_incomes/30;
				    		$sip_level4_income =  bcdiv($sip_level4_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $sip_level4_income  = $sip_level4_incomes/30;
			                  $sip_level4_income  =  bcdiv($sip_level4_income * $interval,1,2);
			              	}
                               $tsip_level4_income = $row['sip_balance'] * $per; 
			                if($interval>0)
			              	{
			              		$todays_sip_level4_in=bcdiv($tsip_level4_income/30,1,2);
			              	}
							$sip_level4_incomea+=$sip_level4_income ;
                            $sip_active++;
						}
							}
							}	
							
						}
					$data['sip_4'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income;
			
				}
			}
		}
		}
	}
					
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=13)
					{
							if(!empty($sip_level_4))
						{
							foreach($sip_level_4 as $row)
							{
						$per    = round($setting['level5_incentive'],2)/100;
						$sip_level_5	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_5))
						{
							$sip_level5_incomea=0;
                          
 						foreach($sip_level_5 as $row)
						{
						if(!empty($row['sip_balance']))
						{
							if(!empty($row['final_sip_date']))
							{
						   $sip_level5_income+= $row['sip_balance'] * $per;
						   $sip_level5_incomes = $row['sip_balance'] * $per;

						    /* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_sip_date']));
							$date = date('Y-m-d', strtotime($date . ' +1 day'));
							if(date('Y-m-d')>$date)
							{
						      $team_sip+= $row['capital_aum'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$sip_level5_income = $sip_level5_incomes/30;
				    		$sip_level5_income =  bcdiv($sip_level5_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $sip_level5_income  = $sip_level5_incomes/30;
			                  $sip_level5_income  =  bcdiv($sip_level5_income * $interval,1,2);
			              	}
							$sip_level5_incomea+=$sip_level5_income ;
                           $tsip_level5_income = $row['sip_balance'] * $per; 
   
							 if($interval>0)
			              	{
			              		$todays_sip_level5_in=bcdiv($tsip_level5_income/30,1,2);
			              	}
                            $sip_active++;
			             } 	
						}
						
						}
						}
						$data['sip_5'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income ;
						}
					}
				}
					
			}
			
			}

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=17)
					{
							if(!empty($sip_level_5))
						{
							foreach($sip_level_5 as $row)
							{
									$sip_level_6	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_6))
								{
									$sip_level6_incomea=0;

									$per    = round($setting['level6_incentive'],2)/100;
								
					 					foreach($sip_level_6 as $row)
										{
											if(!empty($row['sip_balance']))
											{
											if(!empty($row['final_sip_date']))
											{

							    $sip_level6_income+= $row['sip_balance'] * $per;
								$sip_level6_incomes= $row['sip_balance'] * $per;
											   
											   /* DAILY TEAM INCOME*/
		    $date = date('Y-m-d',strtotime($row['final_sip_date']));
			$date = date('Y-m-d', strtotime($date . ' +1 day'));
			if(date('Y-m-d')>$date)
			{


			 $team_sip += $row['capital_aum'];


             $date1 = new DateTime($date);
             $date2 = new DateTime(date('Y-m-d'));

             $interval = $date1->diff($date2)->format("%a");  

                                        if($interval<=30 && $interval>0)
												{
									    		$sip_level6_income = $sip_level6_incomes/30;
									    		$sip_level6_income =  bcdiv($sip_level6_income * $interval,1,2);
												}
							if($interval>30)
              				{
			                  $sip_level6_income  = $sip_level6_incomes/30;
			                  $sip_level6_income  =  bcdiv($sip_level6_income * $interval,1,2);
			              	}
			                 $sip_level6_incomea+=$sip_level6_income ;
                            $tsip_level6_income = $row['sip_balance'] * $per; 

 							if($interval>0)
			              	{
			              		$todays_sip_level6_in=bcdiv($tsip_level6_income/30,1,2);
			              	}
             				 $sip_active++;
			              }
											}
										}		
											
										}
										$data['sip_6'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income;
								}

							}
						}
					}
				}

			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=21)
					{
							if(!empty($sip_level_6))
						{
							foreach($sip_level_6 as $row)
							{
								$sip_level_7	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_7))
								{

									$per    = round($setting['level7_incentive'],2)/100;
								  $sip_level7_incomea=0;

									
 								foreach($sip_level_7 as $row)
								{
									if(!empty($row['sip_balance']))
									{
										if(!empty($row['final_sip_date']))
									{
								   $sip_level7_income+= $row['sip_balance'] * $per;
							 $sip_level7_incomes= $row['sip_balance'] * $per;
	   
								   /* DAILY TEAM INCOME*/
		    $date = date('Y-m-d',strtotime($row['final_sip_date']));
			$date = date('Y-m-d', strtotime($date . ' +1 day'));
			if(date('Y-m-d')>$date)
			{
												   $team_sip+= $row['capital_aum'];


                                                    $date1 = new DateTime($date);
                                                    $date2 = new DateTime(date('Y-m-d'));

                                                    $interval = $date1->diff($date2)->format("%a");  

												if($interval<=30 && $interval>0)
												{
									    		$sip_level7_income = $sip_level7_incomes/30;
									    		$sip_level7_income =  bcdiv($sip_level7_income * $interval,1,2);
												}
							if($interval>30)
              				{
			                  $sip_level7_income  = $sip_level7_incomes/30;
			                  $sip_level7_income  =  bcdiv($sip_level7_income * $interval,1,2);
			              	}
			              	$sip_level7_incomea+=$sip_level7_income ;
                           $tsip_level7_income = $row['sip_balance'] * $per; 

			              	if($interval>0)
			              	{
			              		$todays_sip_level7_in=bcdiv($tsip_level7_income/30,1,2);
			              	}
              				$sip_active++;


			              }
					}
						
				}
									$data['sip_7'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income;
							}
						}
							}
						}
					}
				}
			

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=24)
					{
							if(!empty($sip_level_7))
						{
							foreach($sip_level_7 as $row)
							{
									$sip_level_8	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_8))
								{
									$sip_level8_incomea=0;
											$per    = round($setting['level8_incentive'],2)/100;
										$data['sip_level8']	= $this->user_model->get_my_levels($row['id']);
						 					foreach($sip_level_8 as $row)
											{
												if(!empty($row['sip_balance']))
												{
												if(!empty($row['final_sip_date']))
												{

								$sip_level8_income+= $row['sip_balance'] * $per;
								$sip_level8_incomes = $row['sip_balance'] * $per;

												   /* DAILY TEAM INCOME*/
													 $date = date('Y-m-d',strtotime($row['final_sip_date']));
			$date = date('Y-m-d', strtotime($date . ' +1 day'));
			if(date('Y-m-d')>$date)
			{
					 $team_sip+= $row['capital_aum'];

                                                    $date1 = new DateTime($date);
                                                    $date2 = new DateTime(date('Y-m-d'));

                                                    $interval = $date1->diff($date2)->format("%a");  

                                                if($interval<=30 && $interval>0)
												{
									    		$sip_level8_income = $sip_level8_incomes/30;
									    		$sip_level8_income =  bcdiv($sip_level8_income * $interval,1,2);
												}
							if($interval>30)
              				{
			                  $sip_level8_income  = $sip_level8_incomes/30;
			                  $sip_level8_income  =  bcdiv($sip_level8_income * $interval,1,2);
			              	}
			              	$sip_level8_incomea+= $sip_level8_income;
                            $tsip_level8_income = $row['sip_balance'] * $per; 

			              	if($interval>0)
			              	{
			              		$todays_sip_level8_in=bcdiv($tsip_level8_income/30,1,2);
			              	}
              				$sip_active++;
			              }
												
						}
												
												
					}
											$data['sip_8'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income;
									}
									
								
								}
							}
						}
					}
					}


			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=29)
					{
							if(!empty($sip_level_8))
						{
							foreach($sip_level_8 as $row)
							{
								$sip_level_9	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_9))
						{
							$sip_level9_incomea=0;

								$per    = round($setting['level9_incentive'],2)/100;
								$sip_level_9	= $this->user_model->get_my_levels($row['id']);
							foreach($sip_level_9 as $row)
						{
								if(!empty($row['sip_balance']))
								{
									if(!empty($row['final_sip_date']))
									{
								   $sip_level9_income+= $row['sip_balance'] * $per;
								 $sip_level9_incomes = $row['sip_balance'] * $per;
								     /* DAILY TEAM INCOME*/
		    $date = date('Y-m-d',strtotime($row['final_sip_date']));
			$date = date('Y-m-d', strtotime($date . ' +1 day'));
			if(date('Y-m-d')>$date)
			{
												   $team_sip+= $row['capital_aum'];


                                                    $date1 = new DateTime($date);
                                                    $date2 = new DateTime(date('Y-m-d'));

                                                    $interval = $date1->diff($date2)->format("%a");  

                                                if($interval<=30 && $interval>0)
												{
									    		$sip_level9_income = $sip_level9_incomes/30;
									    		$sip_level9_income =  bcdiv($sip_level9_income * $interval,1,2);
												}
							if($interval>30)
              				{
			                  $sip_level9_income  = $sip_level9_incomes/30;
			                  $sip_level9_income  =  bcdiv($sip_level9_income * $interval,1,2);
			              	}
                           $tsip_level9_income = $row['sip_balance'] * $per; 

			              	if($interval>0)
			              	{
			              		$todays_sip_level9_in=bcdiv($tsip_level9_income/30,1,2);
			              	}
			              	$sip_level9_incomea+= $sip_level9_income;
              				$sip_active++;
			            }  	
					}
								
                     }	
				}
						$data['sip_9'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income;
				}	
				}
			}
		}
	}
			
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=33)
					{
							if(!empty($sip_level_9))
						{
							foreach($sip_level_9 as $row)
							{
								$sip_level_10	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_10))
							{
								$sip_level10_incomea=0;

							$per    = round($setting['level10_incentive'],2)/100;
 						foreach($sip_level_10 as $row)
						{
						if(!empty($row['sip_balance']))
						{
							if(!empty($row['final_sip_date']))
							{
						   $sip_level10_income+= $row['sip_balance'] * $per;
						   $sip_level10_incomes = $row['sip_balance'] * $per;
						    /* DAILY TEAM INCOME*/
		    $date = date('Y-m-d',strtotime($row['final_sip_date']));
			$date = date('Y-m-d', strtotime($date . ' +1 day'));
			if(date('Y-m-d')>$date)
			{
						   $team_sip+= $row['capital_aum'];


                                                    $date1 = new DateTime($date);
                                                    $date2 = new DateTime(date('Y-m-d'));

                                                    $interval = $date1->diff($date2)->format("%a");  

                                                if($interval<=30 && $interval>0)
												{
									    		$sip_level10_income = $sip_level10_incomes/30;
									    		$sip_level10_income =  bcdiv($sip_level10_income * $interval,1,2);
												}
							if($interval>30)
              				{
			                  $sip_level10_income  = $sip_level10_incomes/30;
			                  $sip_level10_income  =  bcdiv($sip_level10_income * $interval,1,2);
			              	}				
			              	$sip_level10_incomea+= $sip_level10_income;
                            $tsip_level10_income = $row['sip_balance'] * $per; 

			              	if($interval>0)
			              	{
			              		$todays_sip_level10_in=bcdiv($tsip_level10_income/30,1,2);
			              	}
              				$sip_active++;
			            }  	
						}
						}
						
					}

					$data['sip_10'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income + $sip_level10_income;
					$data['sip_team_income'] = $data['sip_10'];
					}

							}
						}
					}
			}
              
			if(!empty($sip_level1_incomea) && empty($sip_level2_incomea))
			{
				$data['sip_team_income'] = $sip_level1_incomea;
              //echo $sip_level1_incomea;

			}else if(!empty($sip_level1_incomea) && !empty($sip_level2_incomea) && empty($sip_level3_incomea))
			{
					$data['sip_team_income'] = $sip_level2_incomea + $sip_level1_incomea;

			}else if(!empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level4_incomea))
			{
					$data['sip_team_income'] = $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;

			}
			else if(!empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level5_incomea))
			{
					$data['sip_team_income'] = $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;


			}else if(!empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level6_incomea))
						{
					$data['sip_team_income'] = $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;

			}else if(!empty($sip_level6_incomea) && !empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level7_incomea))
			{
					$data['sip_team_income'] = $sip_level6_incomea + $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;

;
			}else if(!empty($sip_level7_incomea) && !empty($sip_level6_incomea) && !empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level8_incomea))
			{
					$data['sip_team_income'] = $sip_level7_incomea + $sip_level6_incomea + $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;
			}else if(!empty($sip_level8_incomea) && !empty($sip_level7_incomea) && !empty($sip_level6_incomea) && !empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level9_incomea))

			{
					$data['sip_team_income'] = $sip_level8_incomea + $sip_level7_incomea + $sip_level6_incomea + $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;

			}else if(!empty($sip_level9_incomea) && !empty($sip_level8_incomea) && !empty($sip_level7_incomea) && !empty($sip_level6_incomea) && !empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level10_incomea))

			{
					$data['sip_team_income'] = $sip_level9_incomea + $sip_level8_incomea + $sip_level7_incomea + $sip_level6_incomea + $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;


			}else if(!empty($sip_level10_incomea) && !empty($sip_level9_incomea) && !empty($sip_level8_incomea) && !empty($sip_level7_incomea) && !empty($sip_level6_incomea) && !empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea))

			{
					$data['sip_team_income'] = $sip_level10_incomea + $sip_level9_incomea + $sip_level8_incomea + $sip_level7_incomea + $sip_level6_incomea + $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;


			}
			

			if(!empty($data['sip_team_income']))
		{
			$data['sip_team_income'] = $data['sip_team_income'];
		}else if(!empty($data['user']['sip_team_income']))
		{
			$data['sip_team_income'] = $data['user']['sip_team_income'];
		}else
		{
			$data['sip_team_income'] = 0;
		}

		}
	}
      
      
      if(!empty($data['sip_team_income']))
          {
           						$date = date('Y-m-d',strtotime($data['user']['final_sip_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a"); 
                            $data['todays_sip_team_income'] =    bcdiv($data['sip_team_income']/  $interval,1,2);
                                  
                            } else{
                                  
                                  $data['todays_sip_team_income'] =0;
                                }    
        }else{
                                          $data['todays_sip_team_income'] =0;

      }
		//$data['todays_sip_team_income'] =0;
//$data['todays_sip_team_income'] = $todays_sip_level1_in + $todays_sip_level2_in + $todays_sip_level3_in + $todays_sip_level4_in + $todays_sip_level5_in + $todays_sip_level6_in + $todays_sip_level7_in + $todays_sip_level8_in + $todays_sip_level9_in + $todays_sip_level10_in;

		if($data['user']['my_direct']>=2 && $data['user']['my_direct']<5)
		{
			$data['level'] = 2;
		}else if($data['user']['my_direct']>=5 && $data['user']['my_direct'] <9)
		{
			$data['level'] = 3;

		}else if($data['user']['my_direct']>=9 && $data['user']['my_direct'] <13)
		{
			$data['level'] = 4;

		}else if($data['user']['my_direct']>=13 && $data['user']['my_direct'] <17)
		{
			$data['level'] = 5;

		}else if($data['user']['my_direct'] >=17 && $data['user']['my_direct'] <21)
		{
			$data['level'] = 6;

		}else if($data['user']['my_direct']>=21 && $data['user']['my_direct'] <25)
		{
			$data['level'] = 7;

		}else if($data['user']['my_direct']>=25 && $data['user']['my_direct'] <29)
		{			
			$data['level'] = 8;

			
		}else if($data['user']['my_direct']>=29 && $data['user']['my_direct'] <33)
		{
			$data['level'] = 9;

		}else if($data['user']['my_direct']==33)
		{
			$data['level'] = 10;

		}else
		{
			$data['level'] = 1;
		}



		$data['team_capital_aum'] 	  = $team_capital_aum;
		$data['team_count'] 	  	  = $team_count;
		$data['team_self_capital']	  = $team_self_capital;;

		$data['check_todays_capital'] = $this->fund_model->check_todays_capital($this->session->userdata('admin_id'));
		$data['check_todays_sip'] = $this->fund_model->check_todays_sip($this->session->userdata('admin_id'));

		$data['check_capital_interest'] = $this->fund_model->check_capital_interest($this->session->userdata('admin_id'));
		$data['check_sip_interest'] = $this->fund_model->check_sip_interest_total($this->session->userdata('admin_id'));

		$data['check_last_accepted_payment'] = $this->fund_model->get_last_payment($this->session->userdata('admin_id'));
		$data['last_payment_rejected']   = $this->fund_model->get_last_payment_rejected($this->session->userdata('admin_id'));
		$data['alert']   = $this->fund_model->get_alerts();
		$data['today_team_count'] = $this->user_model->gettodaymembercount($this->session->userdata('admin_id'));
		$data['get_daily_capital_interest'] =  $this->fund_model->get_daily_capital_interest($this->session->userdata('admin_id'));
		$data['daily_capital_interest'] =  $this->fund_model->daily_capital_interest($this->session->userdata('admin_id'));
		
		$data['daily_sip_interest'] =  $this->fund_model->daily_sip_interest($this->session->userdata('admin_id'));
		/* CAPITAL CASHBACK, TEAM INCOME, CAPITALAUM WITHDRAWL */
		$data['total_withdrawl']  = $this->fund_model->get_total_withdrawl($this->session->userdata('admin_id'));
		$data['todays_cashback']  = $this->fund_model->get_todays_cashback($this->session->userdata('admin_id'));
		$data['all_i']  = $this->fund_model->get_all_i($this->session->userdata('admin_id'));
		
		$data['check_self_capital_status']  = $this->fund_model->check_self_status($this->session->userdata('admin_id'));
 		$data['check_capital_aum_status']  = $this->fund_model->check_capital_aum_status($this->session->userdata('admin_id'));
 		$data['check_sip_status'] = $this->fund_model->check_sip_Status($this->session->userdata('admin_id'));
 		if($data['user']['capital_aum']>=100000)
 		{

 			$data['daily_referral_return'] = $this->fund_model->referral_return($this->session->userdata('admin_id'));
 		}else
 		{
 			$data['daily_referral_return'] = 0;
 		}
 		
 		$data['active_capital_aum'] = $this->fund_model->get_active_capital_aum($this->session->userdata('admin_id'));
 		//$data['active_sip'] = $this->fund_model->get_active_sip($this->session->userdata('admin_id'));
      	if(!empty($sip_active))
          {
          $data['active_sip'] = $sip_active;
        }else{
           $data['active_sip'] = 0;
        }
    	
		$data['inactive_account'] = $this->fund_model->get_inactive($this->session->userdata('admin_id'));
		$data['sip_inactive_account'] = $this->fund_model->get_inactive_sip($this->session->userdata('admin_id'));
		$data['total_inactive'] = $this->fund_model->get_total_inactive($this->session->userdata('admin_id'));
		if($data['user']['sip_balance']>=10000)
 		{
 			
 			$data['daily_sip_referral'] = $this->fund_model->sip_referral_return($this->session->userdata('admin_id'));
 		}else
 		{
 			$data['daily_sip_referral'] = 0;
 		}
 		$data['check_team_income'] = $this->fund_model->check_team_income($this->session->userdata('admin_id'));
		$data['sip_all_team'] = $this->fund_model->sip_all_team_income($this->session->userdata('admin_id'));
		$data['all_team'] = $this->fund_model->all_team_income($this->session->userdata('admin_id'));
		$data['event'] = $this->fund_model->get_event_desc();
		$data['admin_pass'] = $this->user_model->get_admin_data('1');
		$data['get_total_active_num']= $this->fund_model->get_total_active_num($this->session->userdata('admin_id'));
		$data['total_all_active_account'] = $team_count;
		$data['cap_history']  = $this->user_model->get_capital_return_history1($this->session->userdata('admin_id'),0,0);
		$data['sip_history']  = $this->user_model->get_sip_return_history1($this->session->userdata('admin_id'),0,0);
		
		$data['extra_aum']  = $this->user_model->get_extra_return_history1($this->session->userdata('admin_id'),0,0);
       
		$data['extra_aum_sip']  = $this->user_model->get_extra_sip_history1($this->session->userdata('admin_id'),0,0);
		$data['total_capital_aum']= $this->user_model->get_total_capital_aum($this->session->userdata('admin_id'),0,0);
		$data['total_sip_aum']= $this->user_model->get_total_sip_aum($this->session->userdata('admin_id'),0,0);
		$data['sip_cash_history']  = $this->user_model->get_sip_cash_history($this->session->userdata('admin_id'),0,0);
		$data['capital_cash_history'] = $this->user_model->get_capital_cash($this->session->userdata('admin_id'),0,0);
		$data['todays_total_team_income'] = 0;
		//$data['todays_total_team_income'] = $todays_level1_in + $todays_level2_in +  $todays_level3_in + $todays_level4_in + $todays_level5_in + $todays_level6_in + $todays_level7_in + $todays_level8_in + $todays_level9_in + $todays_level10_in;
      
      if(!empty($data['team_income_bal']))
          {
           						$date = date('Y-m-d',strtotime($data['user']['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a"); 
                            $data['todays_total_team_income'] =    bcdiv($data['team_income_bal']/  $interval,1,2);
                                  
                            } else{
                                  
                                  $data['todays_total_team_income'] =0;
                                }    
        }else{
                                          $data['todays_total_team_income'] =0;

      }
      
			$data['old_team_income'] = $this->fund_model->old_team_income($this->session->userdata('admin_id'));	
		$data['all_i']  = $this->fund_model->get_all_i($this->session->userdata('admin_id'));
 		$data['all_i_extra']  = $this->fund_model->get_all_iint_extra($this->session->userdata('admin_id'));
      	
      	$this->load->view('admin/includes/_header', $data);

    	$this->load->view('admin/users/user_dashboard',$data);

    	//$this->load->view('admin/includes/_footer');
	}

public function dashboard_view()
    {
      	$data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		$data['all_i']  = $this->fund_model->get_all_i($this->session->userdata('admin_id'));
 		$data['all_i_extra']  = $this->fund_model->get_all_iint_extra($this->session->userdata('admin_id'));
			$data['title'] = 'Dashboard';
			$data['all_users'] = $this->dashboard_model->get_all_users(0,0);
			$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
			$data['active_alert'] = $this->user_model->get_active_alert();
			$data['check_verify'] = $this->user_model->check_self_Capital_verify($this->session->userdata('admin_id'));
			$data['check_verify_sip'] = $this->user_model->check_sip_verify($this->session->userdata('admin_id'));
 			$data['fixed_capital'] = $this->dashboard_model->get_fixed_capital($this->session->userdata('admin_id'));

			$data['active_users'] 	= $this->dashboard_model->get_active_users(0,0);

			$data['deactive_users'] = $this->dashboard_model->get_deactive_users(0,0);

	   	$data['my_fund'] 		= $this->dashboard_model->get_my_fund($this->session->userdata('admin_id'));

	   	$data['team_income'] 	= $this->dashboard_model->get_my_fund($this->session->userdata('admin_id'));

	   	$data['team_income']	= $this->user_model->get_team($this->session->userdata('admin_id'));
	   	$data['setting']      = $this->setting_model->get_general_settings();
	   	$team_capital_aum =0;$team_self_capital =0;

			$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income = 0;$level9_income = 0;$level10_income = 0;
	    $setting      	= $this->setting_model->get_general_settings();
	    $team_count     =0;
	    $data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
 		$data['withdraw_cap'] = $this->fund_model->capital_withdraw($this->session->userdata('admin_id'));
 		$data['withdraw_sip'] = $this->fund_model->sip_withdraw($this->session->userdata('admin_id'));
 		$todays_level1_in=0;$todays_level2_in=0;$todays_level3_in=0;$todays_level4_in=0;$todays_level5_in=0;$todays_level6_in=0;
 		$todays_level7_in=0;$todays_level8_in=0;$todays_level9_in=0;$todays_level10_in=0;
 	    $data['get_total_with_cash'] = $this->fund_model->check_withdrawl_cash($this->session->userdata('admin_id')); 
 	    $data['get_total_with_sip_cash'] = $this->fund_model->check_withdrawl_sip($this->session->userdata('admin_id'));  
		if($data['user']['capital_aum']>='100000')
		{
			$capital_aum_date = $data['user']['final_cap_date'];
			//$capital_aum_date = date('Y-m-d', strtotime($capital_aum_date . ' +1 day'));


			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));

			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{

				$user_id  = $this->session->userdata('admin_id');
				$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();$level1_incomea=0;
			foreach($data['team_income_level1'] as $row)
			{
				$level1_incomes=0;
				if(!empty($row['capital_aum']))
				{
					if(!empty($row['final_cap_date']))
				   {	
									
					$per               = round($setting['level1_incentive'],2)/100;
					$level1_income     = $row['capital_aum'] * $per;
					$data['level1_capital']+=$row['capital_aum'];
				    $level1_incomes    = $row['capital_aum'] * $per;
				    /* DAILY TEAM INCOME */ 
				    $date = date('Y-m-d',strtotime($row['final_cap_date']));
                  	$date = date('Y-m-d', strtotime($date . ' +1 day'));

                  		if(date('Y-m-d') >$date)
                  		{
                  			$team_capital_aum += $row['capital_aum'];
	 			    		$team_self_capital+= $row['self_capital'];;
				    
				    	$date1 = new DateTime($date);
						$date2 = new DateTime(date('Y-m-d'));
						$interval = $date1->diff($date2)->format("%a");  
						if($interval<=30 && $interval>0)
						{
							
				    		$level1_income  = $level1_incomes/30;

				    		$level1_income  =  bcdiv($level1_income * $interval,1,2);
				    	}

						if($interval>30)
						{
							
				    		$level1_income = $level1_incomes/30;
				    		$level1_income =  bcdiv($level1_income * $interval,1,2);
						}

						$level1_incomea += $level1_income ;
						$tlevel1_income   = $row['capital_aum'] * $per;
	
						if($interval>0)
				    		{
				    			$todays_level1_in = bcdiv($tlevel1_income/30,1,2);
							}
					
                  		array_push($l1_arr,$row['id']);

				   	$team_count++;
				   }
 				}
				}

			}

		
		   $data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
              if(!empty($data['team_income_level1']))
			{
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);

				$per          = ceil($setting['level2_incentive'])/100;
				$level2_incomea=0;
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							$level2_incomes=0;
 							if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
									{	
									
							   $per              = round($setting['level2_incentive'],2)/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $level2_incomes   = $row['capital_aum'] * $per;
							    $data['level2_capital']+=$row['capital_aum']; 
								//$team_count++;
								/* DAILY TEAM INCOME*/
                              
                                $date = date('Y-m-d',strtotime($row['final_cap_date']));
                                $date = date('Y-m-d', strtotime($date . ' +1 day'));
							if(date('Y-m-d') > $date)
                  			{
                  			   $team_capital_aum += $row['capital_aum'];
	 						   $team_self_capital+= $row['self_capital'];;

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
					    		$level2_income = $level2_incomes/30;
					    		$level2_income =  bcdiv($level2_income * $interval,1,2);
					    		//echo $level2_income." ";

							}

							if($interval>30)
              				{
			                  $level2_income  = $level2_incomes/30;
			                  $level2_income  =  bcdiv($level2_income * $interval,1,2);
			              	}
			              	$level2_incomea += $level2_income ;
			              	$tlevel2_income   = $row['capital_aum'] * $per;

							if($interval>0)
				    		{
				    			$todays_level2_in = bcdiv($tlevel2_income /30,1,2);
							}
								array_push($l2_arr,$row['id']);
						       $team_count++;
						    }   
							}
							
						}	
 						}

 						$data['l2_arr']= $l2_arr;

					}
						$data['level_2'] 		= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					   
 					}
			}	
	}
   }

			if(count($data['team_income_level1'])>=5)
			{

				if(!empty($data['team_income_level2']))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = floor($setting['level3_incentive'])/100;
						$level3_incomea=0;
						foreach($data['team_income_level2'] as $row)
 						{
 							$level3_incomes=0;
							if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
									{	
									
								if(date('Y-m-d') > $date)
                  				{	
                  			     $team_capital_aum += $row['capital_aum'];
			 				     $team_self_capital+= $row['self_capital'];;

								$per              = round($setting['level3_incentive'],2)/100;
								 $level3_income   += $row['capital_aum'] * $per;
								 $level3_incomes  = $row['capital_aum'] * $per;
							    $data['level3_capital']+=$row['capital_aum']; 

								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								  $date = date('Y-m-d', strtotime($date . ' +1 day'));


								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$level3_income = $level3_incomes/30;
				    		//echo  $level3_income;
				    		$level3_income =  bcdiv($level3_income * $interval,1,2);
				    		//echo $level3_income;
							}

							if($interval>30)
              				{
			                  $level3_income  = $level3_incomes/30;
			                  $level3_income  =  bcdiv($level3_income * $interval,1,2);
			              	}

							$level3_incomea += $level3_income ;
							$tlevel3_income   = $row['capital_aum'] * $per;

						   if($interval>0)
				    		{
				    			$todays_level3_in = bcdiv($tlevel3_income /30,1,2);
							}
							 $team_count++;
							 array_push($l3_arr,$row['id']);
							}
							}
							}
						 	$data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;
				 	 }	
					}
				}  

			}


			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();

					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);
						$level4_incomea=0;
						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						$level4_incomes=0;

						$per       = $setting['level4_incentive']/100;

						if(!empty($row['capital_aum']))
							{
							if(!empty($row['final_cap_date']))
									{	
										

								$per              = round($setting['level4_incentive'],2)/100;
								$level4_income   += $row['capital_aum'] * $per;
								$level4_incomes  = $row['capital_aum'] * $per;

							  $data['level4_capital']+=$row['capital_aum']; 


								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
                  				$team_capital_aum+= $row['capital_aum'];
								$team_self_capital+= $row['self_capital'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
                            {
				    			$level4_income = $level4_incomes/30;
				    			$level4_income =  bcdiv($level4_income * $interval,1,2);
							}


							if($interval>30)
              				{
			                  $level4_income  = $level4_incomes/30;
			                  $level4_income  =  bcdiv($level4_income * $interval,1,2);
			              	}
			              	$level4_incomea += $level4_income ;
			              	$tlevel4_income   = $row['capital_aum'] * $per;
			              	if($interval>0)
				    		{
				    			$todays_level4_in = bcdiv($tlevel4_income /30,1,2);

				    		}

						       $team_count++;
							 	array_push($l4_arr,$row['id']);
							} 	
							} 	
							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])>=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$level5_incomea=0;
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
									//print_r($row['id']);die();

					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);
					//print_r($data['team_income_level5']);die();
						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = $setting['level5_incentive']/100;

							$level5_incomes =0;
							if(!empty($row['capital_aum']))
							{

								if(!empty($row['final_cap_date']))
									{	
									
								$per              = round($setting['level5_incentive'],2)/100;
								$level5_income   += $row['capital_aum'] * $per;
								$level5_incomes  = $row['capital_aum'] * $per;

							   	$data['level5_capital']+=$row['capital_aum']; 


								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  				{

                  			    $team_capital_aum+= $row['capital_aum'];
								$team_self_capital+= $row['self_capital'];;

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  
							if($interval<=30 && $interval>0)
							{
				    			$level5_income  = $level5_incomes/30;
				    			$level5_income =  bcdiv($level5_income * $interval,1,2);
							}

							if($interval>30 && $interval>0)
              				{
			                  $level5_income   = $level5_incomes/30;
			                  $level5_income  =  bcdiv($level5_income * $interval,1,2);
			              	}
			              	//echo $row['capital_aum'];
							$level5_incomea += $level5_income ;
							$tlevel5_income   = $row['capital_aum'] * $per;

							if($interval>0)
				    		{
				    			$todays_level5_in = bcdiv($tlevel5_income /30,1,2);

				    		}
							 	$team_count++;
							 	array_push($l5_arr,$row['id']);
							} 	
							} 	
							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}
			//echo $level5_incomea;die();


			if(count($data['team_income_level1'])>=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$level6_incomea=0;
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							$level6_incomes=0;
							if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
									{	
										

								$per              = round($setting['level6_incentive'],2)/100;
								$level6_income   += $row['capital_aum'] * $per;
								$level6_incomes  = $row['capital_aum'] * $per;

							   	$data['level6_capital']+=$row['capital_aum']; 

							   	/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
                  				$team_capital_aum+= $row['capital_aum'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$level6_income = $level6_incomes/30;
				    		$level6_income =  bcdiv($level6_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $level6_income   = $level6_incomes/30;
			                  $level6_income  =  bcdiv($level6_income * $interval,1,2);
			              	}
							$level6_incomea += $level6_income ;
							$tlevel6_income   = $row['capital_aum'] * $per;

							if($interval>0)
				    		{
				    			$todays_level6_in = bcdiv($tlevel6_income /30,1,2);

				    		}
								$team_self_capital+= $row['self_capital'];;
								array_push($l6_arr,$row['id']);

 							$team_count++;
 							}
							}
							}	
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])>=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$level7_incomea=0;
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							$level7_incomes=0;
							foreach($data['team_income_level7'] as $row)
							{
								$per       = floor($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								
								if(!empty($row['final_cap_date']))
									{	
									
								$per              = round($setting['level7_incentive'],2)/100;
								$level7_income   += $row['capital_aum'] * $per;
								$level7_incomes   = $row['capital_aum'] * $per;

							   	$data['level7_capital']+=$row['capital_aum']; 


									/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  				{
                  				$team_capital_aum+= $row['capital_aum'];
								$team_self_capital+= $row['self_capital'];;

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30)
							{
				    		$level7_income = $level7_incomes/30;
				    		$level7_income =  bcdiv($level7_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $level7_income  = $level7_incomes/30;
			                  $level7_income  =  bcdiv($level7_income * $interval,1,2);
			              	}
			              	$level7_incomea += $level7_income ;
			              	$tlevel7_income   = $row['capital_aum'] * $per;

			              		if($interval>0)
				    		{
				    			$todays_level7_in = bcdiv($tlevel7_income /30,1,2);

				    		}

								$team_count++;

								array_push($l7_arr,$row['id']);
							}	
							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 
				 	 }	
					}
				}  

			}

			if(count($data['team_income_level1'])>=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$level8_incomea=0;
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{
							$level8_incomes=0;
							foreach($data['team_income_level8'] as $row)
							{
								$per       = floor($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								
								if(!empty($row['final_cap_date']))
									{	
									
								$per              = round($setting['level8_incentive'],2)/100;
								$level8_income   += $row['capital_aum'] * $per;
								$level8_incomes   = $row['capital_aum'] * $per;

							   	$data['level8_capital']+=$row['capital_aum']; 


								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
                  				$team_capital_aum+= $row['capital_aum'];
								$team_self_capital+= $row['self_capital'];;

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30)
							{
				    		$level8_income = $level8_incomes/30;
				    		$level8_income =  bcdiv($level8_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $level8_income  = $level8_incomes/30;
			                  $level8_income  =  bcdiv($level8_income * $interval,1,2);
			              	}
			              	$level8_incomea += $level8_income ;
			              	$tlevel8_income   = $row['capital_aum'] * $per;

			              	if($interval>0)
				    		{
				    			$todays_level8_in = bcdiv($tlevel8_income /30,1,2);

				    		}
								 $team_count++;
								 array_push($l8_arr,$row['id']);
							}	 

							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 
				 	 }	
					}
				}  

			}

				//echo $level7_incomea;die();
			if(count($data['team_income_level1'])>=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$level9_incomea=0;
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							$level9_incomes=0;
							foreach($data['team_income_level9'] as $row)
							{
							$per       = floor($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								if(!empty($row['final_cap_date']))
									{	
									

								$per              = round($setting['level9_incentive'],2)/100;
								$level9_income   += $row['capital_aum'] * $per;
							   	$data['level9_capital']+=$row['capital_aum']; 
							   	$level9_incomes = $row['capital_aum'] * $per;

								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
                  				$team_capital_aum+= $row['capital_aum'];
								$team_self_capital+= $row['self_capital'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30)
							{
				    		$level9_income = $level9_incomes/30;
				    		$level9_income =  bcdiv($level9_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $level9_income  = $level9_incomes/30;
			                  $level9_income  =  bcdiv($level9_income * $interval,1,2);
			              	}

			              	$level9_incomea += $level9_income ;
			               $tlevel9_income   = $row['capital_aum'] * $per;

			              	if($interval>0)
				    		{
				    			$todays_level9_in = bcdiv($tlevel9_income /30,1,2);

				    		}
								 array_push($l9_arr,$row['id']);


							
							 $team_count++;
							}
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 
				 	 }	
					}
				}  



			if(count($data['team_income_level1'])>=33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();$level10_incomea=0;
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
							foreach($data['team_income_level10'] as $row)
							{
								$per       = floor($setting['level10_incentive'])/100;

								if(!empty($row['capital_aum']))
								{
									if(!empty($row['final_cap_date']))
									{	
									$per              = round($setting['level10_incentive'],2)/100;
									$level10_income   += $row['capital_aum'] * $per;
								   	$data['level10_capital']+=$row['capital_aum']; 
							   	   $level10_incomes = $row['capital_aum'] * $per;


									/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
								$team_capital_aum+= $row['capital_aum'];
								$team_self_capital+= $row['self_capital'];

								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30)
							{
				    		$level10_income = $level10_incomes/30;
				    		$level10_income =  bcdiv($level10_income * $interval,1,2);
							}
							if($interval>30)
              				{
			                  $level10_income  = $level10_incomes/30;
			                  $level10_income  =  bcdiv($level10_income * $interval,1,2);
			              	}

			              	$tlevel10_income   = $row['capital_aum'] * $per;

							if($interval>0)
				    		{
				    			$todays_level10_in = bcdiv($tlevel10_income /30,1,2);

				    		}

							
							$level10_incomea +=$level10_income;

						   array_push($l10_arr,$row['id']);

							$team_count++;
						}	
						}
						}	 	
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 
				 	 	$data['team_income_bal'] = $data['level_10'];

					}
				}  

			}

		
			}
		}

		//echo  $level1_incomea +  $level2_incomea +  $level3_incomea +  $level4_incomea +  $level5_incomea + $level6_incomea + $level7_incomea + $level8_incomea + + $level9_incomea + + $level10_incomea; die();
		if(!empty($level1_incomea) && empty($level2_incomea))
		{
			$data['team_income_bal']  = $level1_incomea;
		}
		else if(!empty($level2_incomea) && !empty($level1_incomea) && empty($level3_incomea))
		{
			$data['team_income_bal']  = $level1_incomea + $level2_incomea;

		}else if(!empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level4_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea;

		}else if(!empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level5_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea;
		}else if(!empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level6_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea;

		}else if(!empty($level6_incomea) && !empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level7_incomea))
		{
			$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea + $level6_incomea;

		}
		else if(!empty($level7_incomea) && !empty($level6_incomea) && !empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level8_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea + $level6_incomea + $level7_incomea;
		}else if(!empty($level8_incomea) && !empty($level7_incomea) && !empty($level6_incomea) && !empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level9_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea + $level6_incomea +  $level7_incomea + $level8_incomea;

		}else if(!empty($level9_incomea) && !empty($level8_incomea) && !empty($level7_incomea) && !empty($level6_incomea) && !empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea) && empty($level10_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea + $level6_incomea +  $level7_incomea + $level8_incomea + $level9_incomea;
		}else if(!empty($level10_incomea) && !empty($level9_incomea) && !empty($level8_incomea) && !empty($level7_incomea) && !empty($level6_incomea) && !empty($level5_incomea)&& !empty($level4_incomea) && !empty($level3_incomea) && !empty($level1_incomea) && !empty($level2_incomea))
		{
				$data['team_income_bal']  = $level1_incomea + $level2_incomea + $level3_incomea + $level4_incomea + $level5_incomea + $level6_incomea +  $level7_incomea + $level8_incomea + $level9_incomea +  $level10_incomea;

		}


		if(!empty($data['team_income_bal']))
		{
			$data['team_income_bal'] = $data['team_income_bal'];
		}else if(!empty($data['user']['team_income']))
		{
			$data['team_income_bal']=$data['user']['team_income'];
		}else{
			$data['team_income_bal']=0;
		}

		
  

		$todays_sip_level1_in =0;$todays_sip_level2_in=0;$todays_sip_leve3_in=0;$todays_sip_level4_in=0;$todays_sip_level5_in=0;$todays_sip_level6_in=0;$todays_sip_level7_in=0;$todays_sip_level8_in=0;$todays_sip_level9_in=0;$todays_sip_level10_in=0;

		/* SIP TEAM INCOME START */
		$sip_level1_income =0;$sip_level2_income =0;$sip_level3_income =0;$sip_level4_income =0;$sip_level5_income =0;$sip_level6_income =0;
		$sip_level7_income =0;$sip_level8_income =0;$sip_level9_income =0;$sip_level10_income =0;$team_sip=0;
		$sip_level1_incomea=0;$sip_level2_incomea=0;$sip_level3_incomea=0;$sip_level4_incomea=0;$sip_level5_incomea=0;$sip_level6_incomea=0;
$sip_level7_incomea=0;$sip_level8_incomea=0;$sip_level9_incomea=0;$sip_level10_incomea=0;
	$todays_sip_level1_in=0;$todays_sip_level2_in=0;$todays_sip_level3_in=0;$todays_sip_level4_in=0;$todays_sip_level5_in=0;$todays_sip_level6_in=0;$todays_sip_level7_in=0;$todays_sip_level8_in=0;$todays_sip_level9_in=0;$todays_sip_level10_in=0;

		if($data['user']['sip_balance']>='10000')
		{
			$capital_aum_date 	= $data['user']['final_sip_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			$data['sip_level1']	= $this->user_model->get_my_levels($this->session->userdata('admin_id'));
			$sip_active =0;
			foreach($data['sip_level1'] as $row)
			{
				if(!empty($row['sip_balance']))
				{
					if(!empty($row['final_sip_date']))
					{
					$per              	  = round($setting['level1_incentive'],2)/100;
					$sip_level1_income   += $row['sip_balance'] * $per;
					$sip_level1_incomes   = $row['sip_balance'] * $per;
						/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_sip_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$sip_level1_income = $sip_level1_incomes/30;
				    		$sip_level1_income =  bcdiv($sip_level1_income * $interval,1,2);
							}
							//echo $sip_level1_income;die();

							if($interval>30)
              				{
			                  $sip_level1_income  = $sip_level1_incomes/30;
			                  $sip_level1_income  =  bcdiv($sip_level1_income * $interval,1,2);
			              	}
			              	$sip_level1_incomea += $sip_level1_income;
                            $tsip_level1_income = $row['sip_balance'] * $per; 
      
			              	if($interval>0)
			              	{
			              		$todays_sip_level1_in+=bcdiv($tsip_level1_income/30,1,2);
			              	}
						$sip_active++;
			     }         	
					
				}
				}	

			}
			$data['sip_1'] 	= $sip_level1_income;
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=3)
					{
                          
					foreach($data['sip_level1'] as $row)
					{

					$sip_level_2	= $this->user_model->get_my_levels($row['id']);
					if(!empty($sip_level_2))
					{
						$sip_level2_incomea=0;
						foreach($sip_level_2 as $row)
						{
							if(!empty($row['sip_balance']))
							{
								if(!empty($row['final_sip_date']))
								{
								$per          = round($setting['level2_incentive'],2)/100;
				
								$team_sip+= $row['sip_balance'];
 					
						   	$sip_level2_income+= $row['sip_balance'] * $per;
						   	$sip_level2_incomes   = $row['sip_balance'] * $per;
						  

						   		/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_sip_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
                  				$team_sip+= $row['capital_aum'];
								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$sip_level2_income = $sip_level2_incomes/30;
				    		$sip_level2_income =  bcdiv($sip_level2_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $sip_level2_income  = $sip_level2_incomes/30;
			                  $sip_level2_income      =  bcdiv($sip_level2_income * $interval,1,2);
			              	}
			              	$sip_level2_incomea +=$sip_level2_income;
                            $tsip_level2_income = $row['sip_balance'] * $per; 

			              	if($interval>0)
			              	{
			              		$todays_sip_level2_in +=bcdiv($sip_level2_income/30,1,2);
			              	}
                                  $sip_active++;
						}	
						}
							
						
						}
						$data['sip_2'] = $sip_level1_income + $sip_level2_income;
					}
				}	
			}

			}

		}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=5)
					{
							if(!empty($sip_level_2))
						{
							foreach($sip_level_2 as $row)
							{

								$sip_level_3	= $this->user_model->get_my_levels($row['id']);
							if(!empty($sip_level_3))
							{

							$per          = round($setting['level3_incentive'],2)/100;
							$sip_level3_incomea=0;
							foreach($sip_level_3 as $row)
							{
								
								if(!empty($row['sip_balance']))
								{
									if(!empty($row['final_sip_date']))
									{
								   $sip_level3_income+= $row['sip_balance'] * $per;
							 $sip_level3_incomes = $row['sip_balance'] * $per;	   

								/* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_sip_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
                  				$team_sip+= $row['capital_aum'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$sip_level3_income = $sip_level3_incomes/30;
				    		$sip_level3_income =  bcdiv($sip_level3_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $sip_level3_income  = $sip_level3_incomes/30;
			                  $sip_level3_income  =  bcdiv($sip_level3_income * $interval,1,2);
			              	}
			              	if($interval>0)
			              	{
                                 $tsip_level3_income = $row['sip_balance'] * $per; 

			              		if(!empty($tsip_level3_income))
			              		{
			              			$todays_sip_level3_in += bcdiv($tsip_level3_income/30,1,2);
			              		}
			              		
			              	}else
			              	{
			              		$todays_sip_level3_in = 0;
			              	}
			              	$sip_level3_incomea+=$sip_level3_income ;
                            $sip_active++;
							
			             } 	
						}
						}		
						
						}
							$data['sip_3'] = $sip_level1_income + $sip_level2_income + $sip_level3_income;
						}
					}	
				}

			}

			}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=9)
					{
							if(!empty($sip_level_3))
						{
							foreach($sip_level_3 as $row)
							{
								$sip_level_4	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_4))
						{

						$per    = round($setting['level4_incentive'],2)/100;
						$sip_level4_incomea =0;

 						foreach($sip_level_4 as $row)
						{
							if(!empty($row['sip_balance']))
							{
								if(!empty($row['final_sip_date']))
								{
							   $sip_level4_income+= $row['sip_balance'] * $per;
							   $sip_level4_incomes = $row['sip_balance'] * $per;
							   /* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_sip_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
                  				$team_sip+= $row['capital_aum'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$sip_level4_income = $sip_level4_incomes/30;
				    		$sip_level4_income =  bcdiv($sip_level4_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $sip_level4_income  = $sip_level4_incomes/30;
			                  $sip_level4_income  =  bcdiv($sip_level4_income * $interval,1,2);
			              	}
                            $tsip_level4_income = $row['sip_balance'] * $per; 

			                if($interval>0)
			              	{
			              		$todays_sip_level4_in+=bcdiv($tsip_level4_income/30,1,2);
			              	}
							$sip_level4_incomea+=$sip_level4_income ;
							 $sip_active++;   
							}	
                              
                                  
							}
							}	
							
						}
					$data['sip_4'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income;
			
				}
			}
		}
		}
	}
					
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=13)
					{
							if(!empty($sip_level_4))
						{
							foreach($sip_level_4 as $row)
							{

						$sip_level_5	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_5))
						{
                          	$per    = round($setting['level5_incentive'],2)/100;
							$sip_level5_incomea=0;
 						foreach($sip_level_5 as $row)
						{
						if(!empty($row['sip_balance']))
						{
							if(!empty($row['final_sip_date']))
							{
						   $sip_level5_income+= $row['sip_balance'] * $per;
						   $sip_level5_incomes = $row['sip_balance'] * $per;

						    /* DAILY TEAM INCOME*/
								 $date = date('Y-m-d',strtotime($row['final_sip_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
                  			    $team_sip+= $row['capital_aum'];

								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a");  

							if($interval<=30 && $interval>0)
							{
				    		$sip_level5_income = $sip_level5_incomes/30;
				    		$sip_level5_income =  bcdiv($sip_level5_income * $interval,1,2);
							}

							if($interval>30)
              				{
			                  $sip_level5_income  = $sip_level5_incomes/30;
			                  $sip_level5_income  =  bcdiv($sip_level5_income * $interval,1,2);
			              	}
							$sip_level5_incomea+=$sip_level5_income ;
                           $tsip_level5_income = $row['sip_balance'] * $per; 
       
							 if($interval>0)
			              	{
			              		$todays_sip_level5_in+=bcdiv($tsip_level5_income/30,1,2);
			              	}
							 $sip_active++;   
			            }  	
						}
						
						}
						}
						$data['sip_5'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income ;
						}
					}
				}
					
			}
			
			}

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=17)
					{
							if(!empty($sip_level_5))
						{
							foreach($sip_level_5 as $row)
							{
									$sip_level_6	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_6))
								{
									$sip_level6_incomea=0;

									$per    = round($setting['level6_incentive'],2)/100;
								
					 					foreach($sip_level_6 as $row)
										{
											if(!empty($row['sip_balance']))
											{
											if(!empty($row['final_sip_date']))
											{

							    $sip_level6_income+= $row['sip_balance'] * $per;
								$sip_level6_incomes= $row['sip_balance'] * $per;
											   
											   
											   /* DAILY TEAM INCOME*/
													 $date = date('Y-m-d',strtotime($row['final_sip_date']));
					$date = date('Y-m-d', strtotime($date . ' +1 day'));
					if(date('Y-m-d') > $date)
                  			{
                  				$team_sip+= $row['capital_aum'];
                                                    $date1 = new DateTime($date);
                                                    $date2 = new DateTime(date('Y-m-d'));

                                                    $interval = $date1->diff($date2)->format("%a");  

                                        if($interval<=30 && $interval>0)
												{
									    		$sip_level6_income = $sip_level6_incomes/30;
									    		$sip_level6_income =  bcdiv($sip_level6_income * $interval,1,2);
												}
							if($interval>30)
              				{
			                  $sip_level6_income  = $sip_level6_incomes/30;
			                  $sip_level6_income  =  bcdiv($sip_level6_income * $interval,1,2);
			              	}
			                 $sip_level6_incomea+=$sip_level6_income ;
                             $tsip_level6_income = $row['sip_balance'] * $per; 

 							if($interval>0)
			              	{
			              		$todays_sip_level6_in+=bcdiv($tsip_level6_income/30,1,2);
			              	}
                      
                      		 $sip_active++;   
			              }
											
											}
										}		
											
										}
										$data['sip_6'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income;
								}

							}
						}
					}
				}

			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=21)
					{
							if(!empty($sip_level_6))
						{
							foreach($sip_level_6 as $row)
							{
								$sip_level_7	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_7))
								{

									$per    = round($setting['level7_incentive'],2)/100;
								  $sip_level7_incomea=0;

									
 								foreach($sip_level_7 as $row)
								{
									if(!empty($row['sip_balance']))
									{
										if(!empty($row['final_sip_date']))
									{
								   $sip_level7_income+= $row['sip_balance'] * $per;
							 		$sip_level7_incomes= $row['sip_balance'] * $per;
	   
								   /* DAILY TEAM INCOME*/
					 $date = date('Y-m-d',strtotime($row['final_sip_date']));
					$date = date('Y-m-d', strtotime($date . ' +1 day'));
					if(date('Y-m-d') > $date)
                  			{

                  		$team_sip+= $row['capital_aum'];

                                                    $date1 = new DateTime($date);
                                                    $date2 = new DateTime(date('Y-m-d'));

                                                    $interval = $date1->diff($date2)->format("%a");  

												if($interval<=30 && $interval>0)
												{
									    		$sip_level7_income = $sip_level7_incomes/30;
									    		$sip_level7_income =  bcdiv($sip_level7_income * $interval,1,2);
												}
							if($interval>30)
              				{
			                  $sip_level7_income  = $sip_level7_incomes/30;
			                  $sip_level7_income  =  bcdiv($sip_level7_income * $interval,1,2);
			              	}
			              	$sip_level7_incomea+=$sip_level7_income ;
                            $tsip_level7_income = $row['sip_balance'] * $per; 

			              	if($interval>0)
			              	{
			              		$todays_sip_level7_in+=bcdiv($tsip_level7_income/30,1,2);
			              	}
							 $sip_active++;   
									

			              }
									}
						
									}
									$data['sip_7'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income;
							}
						}
							}
						}
					}
				}
			

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=24)
					{
							if(!empty($sip_level_7))
						{
							foreach($sip_level_7 as $row)
							{
									$sip_level_8	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_8))
								{
									$sip_level8_incomea=0;
											$per    = round($setting['level8_incentive'],2)/100;
										$data['sip_level8']	= $this->user_model->get_my_levels($row['id']);
						 					foreach($sip_level_8 as $row)
											{
												if(!empty($row['sip_balance']))
												{
												if(!empty($row['final_sip_date']))
												{

								$sip_level8_income+= $row['sip_balance'] * $per;
								$sip_level8_incomes = $row['sip_balance'] * $per;

				/* DAILY TEAM INCOME*/
			$date = date('Y-m-d',strtotime($row['final_sip_date']));
			$date = date('Y-m-d', strtotime($date . ' +1 day'));
			if(date('Y-m-d') > $date)
                  			{
                  						$team_sip+= $row['capital_aum'];

                                                    $date1 = new DateTime($date);
                                                    $date2 = new DateTime(date('Y-m-d'));

                                                    $interval = $date1->diff($date2)->format("%a");  

                                                if($interval<=30 && $interval>0)
												{
									    		$sip_level8_income = $sip_level8_incomes/30;
									    		$sip_level8_income =  bcdiv($sip_level8_income * $interval,1,2);
												}
							if($interval>30)
              				{
			                  $sip_level8_income  = $sip_level8_incomes/30;
			                  $sip_level8_income  =  bcdiv($sip_level8_income * $interval,1,2);
			              	}
			              	$sip_level8_incomea+= $sip_level8_income;
                            $tsip_level8_income = $row['sip_balance'] * $per; 

			              	if($interval>0)
			              	{
			              		$todays_sip_level8_in+=bcdiv($tsip_level8_income/30,1,2);
			              	}
              
              				 $sip_active++;   
			              }
											
											}
												
												
											}
											$data['sip_8'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income;
									}
									
								
								}
							}
						}
					}
					}


			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=29)
					{
							if(!empty($sip_level_8))
						{
							foreach($sip_level_8 as $row)
							{
								$sip_level_9	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_9))
						{
							$sip_level9_incomea=0;

								$per    = round($setting['level9_incentive'],2)/100;
								$sip_level_9	= $this->user_model->get_my_levels($row['id']);
							foreach($sip_level_9 as $row)
						{
								if(!empty($row['sip_balance']))
								{
									if(!empty($row['final_sip_date']))
									{
								   $sip_level9_income+= $row['sip_balance'] * $per;
								 $sip_level9_incomes = $row['sip_balance'] * $per;
								     /* DAILY TEAM INCOME*/
			$date = date('Y-m-d',strtotime($row['final_sip_date']));
			$date = date('Y-m-d', strtotime($date . ' +1 day'));
			if(date('Y-m-d') > $date)
              {								  
              	$team_sip+= $row['capital_aum'];


                                                    $date1 = new DateTime($date);
                                                    $date2 = new DateTime(date('Y-m-d'));

                                                    $interval = $date1->diff($date2)->format("%a");  

                                                if($interval<=30 && $interval>0)
												{
									    		$sip_level9_income = $sip_level9_incomes/30;
									    		$sip_level9_income =  bcdiv($sip_level9_income * $interval,1,2);
												}
							if($interval>30)
              				{
			                  $sip_level9_income  = $sip_level9_incomes/30;
			                  $sip_level9_income  =  bcdiv($sip_level9_income * $interval,1,2);
			              	}
                            $tsip_level9_income = $row['sip_balance'] * $per; 

			              	if($interval>0)
			              	{
			              		$todays_sip_level9_in+=bcdiv($tsip_level9_income/30,1,2);
			              	}
			              	$sip_level9_incomea+= $sip_level9_income;
							$sip_active++;   
								}			
								}
								
                            }	
							}
						$data['sip_9'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income;
				}	
				}
			}
		}
	}
			
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=33)
					{
							if(!empty($sip_level_9))
						{
							foreach($sip_level_9 as $row)
							{
								$sip_level_10	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_10))
							{
								$sip_level10_incomea=0;

							$per    = round($setting['level10_incentive'],2)/100;
 						foreach($sip_level_10 as $row)
						{
						if(!empty($row['sip_balance']))
						{
							if(!empty($row['final_sip_date']))
							{
						   $sip_level10_income+= $row['sip_balance'] * $per;
						   $sip_level10_incomes = $row['sip_balance'] * $per;
						    /* DAILY TEAM INCOME*/
			$date = date('Y-m-d',strtotime($row['final_sip_date']));
			$date = date('Y-m-d', strtotime($date . ' +1 day'));
			if(date('Y-m-d') > $date)
             {
             							   $team_sip+= $row['capital_aum'];


                                                    $date1 = new DateTime($date);
                                                    $date2 = new DateTime(date('Y-m-d'));

                                                    $interval = $date1->diff($date2)->format("%a");  

                                                if($interval<=30 && $interval>0)
												{
									    		$sip_level10_income = $sip_level10_incomes/30;
									    		$sip_level10_income =  bcdiv($sip_level10_income * $interval,1,2);
												}
							if($interval>30)
              				{
			                  $sip_level10_income  = $sip_level10_incomes/30;
			                  $sip_level10_income  =  bcdiv($sip_level10_income * $interval,1,2);
			              	}				
			              	$sip_level10_incomea+= $sip_level10_income;
                         $tsip_level10_income = $row['sip_balance'] * $per; 

			              	if($interval>0)
			              	{
			              		$todays_sip_level10_in+=bcdiv($tsip_level10_income/30,1,2);
			              	}
							 $sip_active++;   
						}				
						}
						}
						
					}

					$data['sip_10'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income + $sip_level10_income;
					$data['sip_team_income'] = $data['sip_10'];
					}

							}
						}
					}
			}
              
			if(!empty($sip_level1_incomea) && empty($sip_level2_incomea))
			{
				$data['sip_team_income'] = $sip_level1_incomea;
              //echo $sip_level1_incomea;

			}else if(!empty($sip_level1_incomea) && !empty($sip_level2_incomea) && empty($sip_level3_incomea))
			{
					$data['sip_team_income'] = $sip_level2_incomea + $sip_level1_incomea;

			}else if(!empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level4_incomea))
			{
					$data['sip_team_income'] = $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;

			}
			else if(!empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level5_incomea))
			{
					$data['sip_team_income'] = $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;


			}else if(!empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level6_incomea))
						{
					$data['sip_team_income'] = $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;

			}else if(!empty($sip_level6_incomea) && !empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level7_incomea))
			{
					$data['sip_team_income'] = $sip_level6_incomea + $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;

;
			}else if(!empty($sip_level7_incomea) && !empty($sip_level6_incomea) && !empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level8_incomea))
			{
					$data['sip_team_income'] = $sip_level7_incomea + $sip_level6_incomea + $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;
			}else if(!empty($sip_level8_incomea) && !empty($sip_level7_incomea) && !empty($sip_level6_incomea) && !empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level9_incomea))

			{
					$data['sip_team_income'] = $sip_level8_incomea + $sip_level7_incomea + $sip_level6_incomea + $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;

			}else if(!empty($sip_level9_incomea) && !empty($sip_level8_incomea) && !empty($sip_level7_incomea) && !empty($sip_level6_incomea) && !empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea) && empty($sip_level10_incomea))

			{
					$data['sip_team_income'] = $sip_level9_incomea + $sip_level8_incomea + $sip_level7_incomea + $sip_level6_incomea + $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;


			}else if(!empty($sip_level10_incomea) && !empty($sip_level9_incomea) && !empty($sip_level8_incomea) && !empty($sip_level7_incomea) && !empty($sip_level6_incomea) && !empty($sip_level5_incomea) && !empty($sip_level4_incomea) && !empty($sip_level3_incomea) && !empty($sip_level2_incomea) && !empty($sip_level1_incomea))

			{
					$data['sip_team_income'] = $sip_level10_incomea + $sip_level9_incomea + $sip_level8_incomea + $sip_level7_incomea + $sip_level6_incomea + $sip_level5_incomea + $sip_level4_incomea + $sip_level3_incomea + $sip_level2_incomea + $sip_level1_incomea;


			}
			//echo $data['sip_team_income'];die();

			if(!empty($data['sip_team_income']))
		{
			$data['sip_team_income'] = $data['sip_team_income'];
		}else if(!empty($data['user']['sip_team_income']))
		{
			$data['sip_team_income'] = $data['user']['sip_team_income'];
		}else{
			$data['sip_team_income'] = 0;
		}

		}
	}
  
  		if(!empty($data['sip_team_income']))
          {
           						$date = date('Y-m-d',strtotime($data['user']['final_sip_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a"); 
                            $data['todays_sip_team_income'] =    bcdiv($data['sip_team_income']/  $interval,1,2);
                                  
                            } else{
                                  
                                  $data['todays_sip_team_income'] =0;
                                }    
        }else{
                                  
                                  $data['todays_sip_team_income'] =0;
           } 
  
		//$data['todays_sip_team_income'] =0;
		//$data['todays_sip_team_income'] = $todays_sip_level1_in + $todays_sip_level2_in + $todays_sip_level3_in + $todays_sip_level4_in + $todays_sip_level5_in + $todays_sip_level6_in + $todays_sip_level7_in + $todays_sip_level8_in + $todays_sip_level9_in + $todays_sip_level10_in;

		if($data['user']['my_direct']>=2 && $data['user']['my_direct']<5)
		{
			$data['level'] = 2;
		}else if($data['user']['my_direct']>=5 && $data['user']['my_direct'] <9)
		{
			$data['level'] = 3;

		}else if($data['user']['my_direct']>=9 && $data['user']['my_direct'] <13)
		{
			$data['level'] = 4;

		}else if($data['user']['my_direct']>=13 && $data['user']['my_direct'] <17)
		{
			$data['level'] = 5;

		}else if($data['user']['my_direct'] >=17 && $data['user']['my_direct'] <21)
		{
			$data['level'] = 6;

		}else if($data['user']['my_direct']>=21 && $data['user']['my_direct'] <25)
		{
			$data['level'] = 7;

		}else if($data['user']['my_direct']>=25 && $data['user']['my_direct'] <29)
		{			
			$data['level'] = 8;

			
		}else if($data['user']['my_direct']>=29 && $data['user']['my_direct'] <33)
		{
			$data['level'] = 9;

		}else if($data['user']['my_direct']==33)
		{
			$data['level'] = 10;

		}else
		{
			$data['level'] = 1;
		}



		$data['team_capital_aum'] 	  = $team_capital_aum;
		$data['team_count'] 	  	  = $team_count;
		$data['team_self_capital']	  = $team_self_capital;;

		$data['check_todays_capital'] = $this->fund_model->check_todays_capital($this->session->userdata('admin_id'));
		$data['check_todays_sip'] = $this->fund_model->check_todays_sip($this->session->userdata('admin_id'));

		$data['check_capital_interest'] = $this->fund_model->check_capital_interest($this->session->userdata('admin_id'));
		$data['check_sip_interest'] = $this->fund_model->check_sip_interest_total($this->session->userdata('admin_id'));

		$data['check_last_accepted_payment'] = $this->fund_model->get_last_payment($this->session->userdata('admin_id'));
		$data['last_payment_rejected']   = $this->fund_model->get_last_payment_rejected($this->session->userdata('admin_id'));
		$data['alert']   = $this->fund_model->get_alerts();
		$data['today_team_count'] = $this->user_model->gettodaymembercount($this->session->userdata('admin_id'));
		$data['get_daily_capital_interest'] =  $this->fund_model->get_daily_capital_interest($this->session->userdata('admin_id'));
		$data['daily_capital_interest'] =  $this->fund_model->daily_capital_interest($this->session->userdata('admin_id'));
		
		$data['daily_sip_interest'] =  $this->fund_model->daily_sip_interest($this->session->userdata('admin_id'));
		/* CAPITAL CASHBACK, TEAM INCOME, CAPITALAUM WITHDRAWL */
		$data['total_withdrawl']  = $this->fund_model->get_total_withdrawl($this->session->userdata('admin_id'));
		$data['todays_cashback']  = $this->fund_model->get_todays_cashback($this->session->userdata('admin_id'));
		
		$data['check_self_capital_status']  = $this->fund_model->check_self_status($this->session->userdata('admin_id'));
 		$data['check_capital_aum_status']  = $this->fund_model->check_capital_aum_status($this->session->userdata('admin_id'));
 		$data['check_sip_status'] = $this->fund_model->check_sip_Status($this->session->userdata('admin_id'));
 		if($data['user']['capital_aum']>=100000)
 		{

 			$data['daily_referral_return'] = $this->fund_model->referral_return($this->session->userdata('admin_id'));
 		}else
 		{
 			$data['daily_referral_return'] = 0;
 		}
 		
 		$data['active_capital_aum'] = $this->fund_model->get_active_capital_aum($this->session->userdata('admin_id'));
 		//$data['active_sip'] = $this->fund_model->get_active_sip($this->session->userdata('admin_id'));
  		if(!empty($sip_active))
          {
            $data['active_sip'] =  $sip_active;
        }else{
           $data['active_sip'] = 0;
        }
         
		$data['inactive_account'] = $this->fund_model->get_inactive($this->session->userdata('admin_id'));
		$data['sip_inactive_account'] = $this->fund_model->get_inactive_sip($this->session->userdata('admin_id'));
		$data['total_inactive'] = $this->fund_model->get_total_inactive($this->session->userdata('admin_id'));
		if($data['user']['sip_balance']>=10000)
 		{
 			
 			$data['daily_sip_referral'] = $this->fund_model->sip_referral_return($this->session->userdata('admin_id'));
 		}else
 		{
 			$data['daily_sip_referral'] = 0;
 		}
 		$data['check_team_income'] = $this->fund_model->check_team_income($this->session->userdata('admin_id'));
		$data['sip_all_team'] = $this->fund_model->sip_all_team_income($this->session->userdata('admin_id'));
		$data['all_team'] = $this->fund_model->all_team_income($this->session->userdata('admin_id'));
		$data['event'] = $this->fund_model->get_event_desc();
		$data['admin_pass'] = $this->user_model->get_admin_data('1');
		$data['get_total_active_num']= $this->fund_model->get_total_active_num($this->session->userdata('admin_id'));
		$data['total_all_active_account'] = $team_count;
		$data['cap_history']  = $this->user_model->get_capital_return_history1($this->session->userdata('admin_id'),0,0);
		$data['sip_history']  = $this->user_model->get_sip_return_history1($this->session->userdata('admin_id'),0,0);
		
		$data['extra_aum']  = $this->user_model->get_extra_return_history1($this->session->userdata('admin_id'),0,0);
       
		$data['extra_aum_sip']  = $this->user_model->get_extra_sip_history1($this->session->userdata('admin_id'),0,0);
		$data['total_capital_aum']= $this->user_model->get_total_capital_aum($this->session->userdata('admin_id'),0,0);
		$data['total_sip_aum']= $this->user_model->get_total_sip_aum($this->session->userdata('admin_id'),0,0);
		$data['sip_cash_history']  = $this->user_model->get_sip_cash_history($this->session->userdata('admin_id'),0,0);
		$data['capital_cash_history'] = $this->user_model->get_capital_cash($this->session->userdata('admin_id'),0,0);
  		$data['todays_total_team_income'] = 0;
  
  if(!empty($data['team_income_bal']))
          {
           						$date = date('Y-m-d',strtotime($data['user']['final_cap_date']));
								$date = date('Y-m-d', strtotime($date . ' +1 day'));
								if(date('Y-m-d') > $date)
                  			{
								$date1 = new DateTime($date);
								$date2 = new DateTime(date('Y-m-d'));
						
								$interval = $date1->diff($date2)->format("%a"); 
                            $data['todays_total_team_income'] =    bcdiv($data['team_income_bal']/  $interval,1,2);
                                  
                            } else{
                                  
                                  $data['todays_total_team_income'] =0;
                                }    
        }
  
  
		//$data['todays_total_team_income'] = 0;
		//$data['todays_total_team_income'] = $todays_level1_in + $todays_level2_in +  $todays_level3_in + $todays_level4_in + $todays_level5_in + $todays_level6_in + $todays_level7_in + $todays_level8_in + $todays_level9_in + $todays_level10_in;
			$data['old_team_income'] = $this->fund_model->old_team_income($this->session->userdata('admin_id'));	
				$data['co_module']= $this->admin_roles->get_admin_access($this->session->userdata('admin_id'));
	
		$this->load->view('admin/includes/header1', $data);

    	$this->load->view('admin/users/view_user_dashboard',$data);

    	//$this->load->view('admin/includes/_footer');
	}


    
	public function add_user(){

		$this->rbac->check_operation_access(); // check opration permission

			$this->form_validation->set_rules('sid', 'Account Number', 'trim|required');
			$this->form_validation->set_rules('fname', 'First Name', 'trim|required');
			$this->form_validation->set_rules('mname', 'Middle Name', 'trim|required');
			$this->form_validation->set_rules('lname', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');

			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('admin/users/new_register'),'refresh');
			}
			else{
              		
              $user_exists = $this->user_model->check_no_exists(strtoupper($this->input->post('ac_no')));
              		if(!empty($user_exists))
                      {
                      $this->session->set_flashdata('error', "This Account No Is Already Exists!!");
					redirect(base_url('admin/users/add_user'),'refresh');
                    }else{

					$get_prefix = $this->user_model->get_prefix();
					$prefix 	= $get_prefix['account_no_prefix'];
					
					$check_no = $this->user_model->check_user_max();
					/*if(empty($check_no))
					{
						$account_no =1;
					}else
					{
						$account_no = $check_no['id']+1;
					}*/
					/*$ac_n = random_int(100000, 999999);
					$check_no = $this->user_model->check_no_exists($prefix.$ac_n);
					if(empty($check_no))
					{
					  $account_no =	$ac_n;
					}else
					{
						$account_no = $check_no['id']+1;
						
					}
					$account_no = $prefix.$account_no;	*/
					$sort_order = $this->user_model->check_order($this->input->post('parent_id'));
					if(!empty($sort_order))
					{
						$sort_order = $sort_order['sort_order'] + 1;
					}else
					{
						$sort_order = 1;
					}

				$get_user_de = $this->user_model->get_user_detail($this->input->post('parent_id'));
				if($sort_order == '1')
				{
						if($get_user_de['type_of_partner']=='Investor')
					{
						$up_arr = array('type_of_partner'=>'Royalty Holder');
						$this->user_model->edit_user($up_arr,$this->input->post('parent_id'));	
					}

				}
				
					$data = array(
					'account_no' =>strtoupper($this->input->post('ac_no')),
					'username'   => strtoupper($this->input->post('fname')),
					'firstname'  => strtoupper($this->input->post('mname')),
					'lastname'   => strtoupper($this->input->post('lname')),
					'password'   =>  password_hash($this->input->post('password'), PASSWORD_BCRYPT),
					'email'	   => $this->input->post('email_id'),
					'mobile_no'	=> $this->input->post('mobile_no'),
				    'dob'		=> date('Y-m-d',strtotime($this->input->post('dob'))),
				    'age'		=> $this->input->post('age'),
				    'gender'	=> $this->input->post('gender'),
				    'type_of_partner'=>$this->input->post('type_of_partner'),
				    'nominee_name'	=> strtoupper($this->input->post('nominee_name')),
				    'nominee_relation'=>strtoupper($this->input->post('nominee_relation')),
				    'reference_id'=>$this->input->post('sid'),
				    'self_capital'=>0,
				    'capital_aum'=>0,
				    'sip_balance'=>0,
				    'my_fund'=>0,
					'role'=>5,
					'is_verify'=>1,
					'is_supper'=>1,
					'is_active'=>1,
					'is_parent'=>$this->input->post('parent_id'),
					'close_account_status'=>'Open',
					'sort_order'=>$sort_order,
					'created_at' => date('Y-m-d : h:m:s'),
				);
				$data = $this->security->xss_clean($data);
				$result = $this->user_model->add_user($data);
				if($result){
					

				$check_direct = $this->user_model->get_user_detail($this->input->post('parent_id'));
				if(!empty($check_direct['my_direct']))
				{
					$my_direct = $check_direct['my_direct'] + 1;
				}else
				{
					$my_direct = 1;
				}
				$arr =  array('my_direct'=>$my_direct);
				$this->user_model->edit_user($arr,$this->input->post('parent_id'));

				if(!empty($this->input->post('parent_id')))
					{
						$arrdata = array(
						'user_id'=>$this->input->post('parent_id'),
						'activity'=>'Referral Member Added',
						'created_at'=>date('Y-m-d h:i:s'),
						'created_by'=>$this->session->userdata('admin_id'),
						);

						$this->fund_model->add_log($arrdata);
					}

		  $arr = $this->setting_model->get_sms_by_id(7);

		  $sms = $arr['message'];
		  $sms = $this->str_replace_limit('{#var#}', $this->input->post('fname'), $sms, 1);
		  $sms = $this->str_replace_limit('{#var#}', strtoupper($this->input->post('ac_no')), $sms, 1);

		  $sms  = str_replace('{#var#}',$this->input->post('password'),$sms);
 		  $sms1 = $sms;	
          $sms = urlencode($sms);
          $contact_mobile = urlencode('91'.$this->input->post('mobile_no'));
          //echo $contact_mobile;die();
          $user_id  = $this->session->userdata('user_id');
            /* Common_Helper Function To Send SMS */
          //send_sms($contact_mobile,$sms);
          /* Common Helper Function to Send EMail */

          send_sms_text(urlencode($contact_mobile),urlencode($sms));
          send_email_user($this->input->post('email_id'),$sms1,'User Registration');
		  $arrp  = $this->setting_model->get_sms_by_id(16);
		  $smsp  = $arrp['message'];
		  $smsp  = str_replace('{#var#}',strtoupper($this->input->post('ac_no')),$smsp);
 			
          $smsp = urlencode($smsp);
          $parent_data = $this->user_model->get_user_detail($this->input->post('parent_id'));
          $contact_mobilep = urlencode('91'.$parent_data['mobile_no']);
        
          //send_sms($contact_mobilep,$smsp);
          /* Common Helper Function to Send EMail */
		  send_sms_text($contact_mobilep,$smsp);

					// Activity Log 
					$this->activity_model->add_log(1);


					$this->session->set_flashdata('success', 'User with Account No "'.strtoupper($this->input->post('ac_no')).'" AND Password "'.$this->input->post('password').'"  Registered Successfully!!');
					redirect(base_url('admin/users/new_register'));
				}
			}
          }
		
		
	}

	public function new_register()
	{
		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
		$data['active_alert'] = $this->user_model->get_active_alert();

		$this->load->view('admin/includes/_header',$data);
		$this->load->view('admin/users/user_register');
		$this->load->view('admin/includes/_footer');
	}

	public function get_parent_name()
	{
		$reference_id = $this->input->post('refernce_id');
		if($reference_id)
		{
			$data = $this->user_model->get_parent_name($reference_id);
			if(!empty($data))
			{

				$result = array('status'=>1,'parent'=>$data['username'],'account_no'=>$data['account_no']);
			}
			else
			{
				$result = array('status'=>0,'parent'=>'','account_no'=>'');

			}


		}else
		{
			$result = array('status'=>0);
			
		}
		echo json_encode($result);
	}

	public function getuser()
	{
		$reference_id = $this->input->post('refernce_id');
		if($reference_id)
		{
			$data = $this->user_model->get_userbyreference($reference_id);
			if(!empty($data))
			{
				if(!empty($data['photo']))
				{
					$profile_photo = base_url().'uploads/profile_photo/'.$data['photo'];
					$result = array('status'=>1,'username'=>$data['username'],'mobile_no'=>$data['mobile_no'],'reference_id'=>$data['account_no'],'id'=>$data['id'],'mobile_no'=>$data['mobile_no'],'my_fund'=>$data['my_fund'],'username'=>$data['username'],'my_direct'=>$data['my_direct'],'capital_aum'=>$data['capital_aum'],'self_capital'=>$data['self_capital'],'profile_photo'=>$profile_photo,'sip_balance'=>$data['sip_balance'],'kyc_status'=>$data['kyc_status'],'middle_name'=>$data['firstname'],'lastname'=>$data['lastname']);
				}else
				{
					$result = array('status'=>1,'username'=>$data['username'],'mobile_no'=>$data['mobile_no'],'reference_id'=>$data['account_no'],'id'=>$data['id'],'mobile_no'=>$data['mobile_no'],'my_fund'=>$data['my_fund'],'username'=>$data['username'],'my_direct'=>$data['my_direct'],'capital_aum'=>$data['capital_aum'],'self_capital'=>$data['self_capital'],'profile_photo'=>'','sip_balance'=>$data['sip_balance'],'kyc_status'=>$data['kyc_status'],'middle_name'=>$data['firstname'],'lastname'=>$data['lastname']);
				}
				
			}
			else
			{
				$result = array('status'=>0,'username'=>"",'mobile_no'=>"",'reference_id'=>"",'id'=>"",'mobile_no'=>"",'my_fund'=>"",'username'=>"",'my_direct'=>"",'capital_aum'=>"",'self_capital'=>"",'profile_photo'=>'','sip_balance'=>'','kyc_status'=>'','middle_name'=>'','lastname'=>'');
			}


		}else
		{
			$result = array('status'=>0);
			
		}
		echo json_encode($result);
	}

	public function get_close_ac_details()
	{
		$level1_income = 0;$level2_income = 0;$level3_income = 0;$level4_income = 0;$level5_income = 0;$level6_income = 0;$level7_income = 0;$level8_income = 0;$level9_income = 0;$level10_income = 0;
	   $team_capital_aum =0;$team_self_capital =0;$team_count=0;
	   $setting =  $this->setting_model->get_general_settings();
	   $data['level1_capital']=0; $data['level2_capital']=0; $data['level3_capital']=0; $data['level4_capital']=0; $data['level5_capital']=0; $data['level6_capital']=0; $data['level7_capital']=0; $data['level8_capital']=0; $data['level9_capital']=0; $data['level10_capital']=0;
 		
		$reference_id = $this->input->post('refernce_id');
		if($reference_id)
		{

			$data2 = $this->user_model->get_userbyreference($reference_id);
			if(!empty($data2))
			{
				if($data2['capital_aum']>='100000')
		{
			$capital_aum_date = $data2['final_cap_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{

				$user_id  = $data2['id'];
				$data['team_income_level1']	= $this->user_model->get_my_levels($user_id);
			$l1_arr = array();
			foreach($data['team_income_level1'] as $row)
			{
				if(!empty($row['capital_aum']))
				{
					$per              = ceil($setting['level1_incentive'])/100;
					$level1_income   += $row['capital_aum'] * $per;
				    $data['level1_capital']+=$row['capital_aum']; 
				    array_push($l1_arr,$row['id']);
				    
				}
					
			}

			$data['l1_arr'] = $l1_arr;

		   $data['level1_capital']= $data['level1_capital']; 

			$data['level_1'] = $level1_income;
			if(count($data['team_income_level1'])>=3)
			{
				$l2_arr = array();
				if(!empty(count($data['team_income_level1'])))
			{
				foreach($data['team_income_level1'] as $row)
			{
				$data['team_income_level2'] = $this->user_model->get_my_levels($row['id']);
				$per          = ceil($setting['level2_incentive'])/100;
				
					if(!empty($data['team_income_level2']))
 					{

 						foreach($data['team_income_level2'] as $row)
 						{
 							if(!empty($row['capital_aum']))
							{

								 $per              = ceil($setting['level2_incentive'])/100;
							   $level2_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level2_capital']+=$row['capital_aum']; 
	 						   $team_self_capital+= $row['self_capital'];;
								$team_count++;
								array_push($l2_arr,$row['id']);
							}
							
						
 						}

 						$data['l2_arr']= $l2_arr;

					}
							$data['level_2'] 		= $level2_income;
					    $data['level2_capital'] = $data['level2_capital'];
					    $data['team_count']		= $team_count; 
					   
 					}
			}	
	}

			if(count($data['team_income_level1'])>=5)
			{

				if(!empty(count($data['team_income_level2'])))
				{ 				$l3_arr = array();

					foreach($data['team_income_level2'] as $row)
					{
				
					$data['team_income_level3'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level3']))
						{

						$per       = ceil($setting['level3_incentive'])/100;

						foreach($data['team_income_level2'] as $row)
 						{
 						
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level3_incentive'])/100;
								 $level3_income   += $row['capital_aum'] * $per;
							   $team_capital_aum += $row['capital_aum'];
							    $data['level3_capital']+=$row['capital_aum']; 

	 						   $team_self_capital+= $row['self_capital'];;
								

							 $team_count++;$team_self_capital++;
							 array_push($l3_arr,$row['id']);
							}
							}
						 $data['l3_arr']= $l3_arr;

						}
				 		$data['level_3'] = $level3_income + $level2_income;
				 	 	$data['level3_capital'] =$data['level3_capital']; 
				 	 	$data['team_count']		= $team_count;

					}
				}  

			}

			if(count($data['team_income_level1'])>=9)
			{

				if(!empty($data['team_income_level3']))
				{
					$l4_arr = array();
					foreach($data['team_income_level3'] as $row)
					{
				
						$data['team_income_level4'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level4']))
						{
							foreach($data['team_income_level4'] as $row)
 						{
 						

						$per       = ceil($setting['level4_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level4_incentive'])/100;
								$level3_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level4_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

						       $team_count++;$team_self_capital++;
							 	array_push($l4_arr,$row['id']);


							}
						}
						 $data['l4_arr']= $l4_arr;

				 		$data['level_4'] = $level4_income + $level3_income + $level2_income;
				 	 	$data['level4_capital'] =$data['level4_capital'];
				 	 	$data['team_count']		= $team_count;
 

					}
				}  

			}
		}

			if(count($data['team_income_level1'])<=13)
			{

				if(!empty($data['team_income_level4']))
				{
					$l5_arr = array();
					foreach($data['team_income_level4'] as $row)
					{
				
					$data['team_income_level5'] = $this->user_model->get_my_levels($row['id']);

						if(!empty($data['team_income_level5']))
						{
							foreach($data['team_income_level5'] as $row)
 						{
 						

						$per       = ceil($setting['level5_incentive'])/100;


							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level5_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level5_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

							 	$team_count++;$team_self_capital++;
							 	array_push($l5_arr,$row['id']);


							}
						}
						 $data['l5_arr']= $l5_arr;
						}
				 		$data['level_5'] = $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level5_capital'] =$data['level5_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])<=17)
			{

				if(!empty($data['team_income_level5']))
				{
					$l6_arr = array();
					foreach($data['team_income_level5'] as $row)
					{
				
					$data['team_income_level6'] = $this->user_model->get_team_levels($row['id']);

					if(!empty($data['team_income_level6']))
					{

						foreach($data['team_income_level6'] as $row)
						{
							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level6_incentive'])/100;
								$level6_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level6_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
							array_push($l6_arr,$row['id']);



							}
							 $team_count++;$team_self_capital++;
					
						}

						$data['l6_arr']= $l6_arr;

					}
				 		$data['level_6'] = $level6_income + $level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level6_capital'] =$data['level6_capital']; 

					}
				}  

			}



			if(count($data['team_income_level1'])<=21)
			{

				if(!empty($data['team_income_level6']))
				{
					$l7_arr = array();
					foreach($data['team_income_level6'] as $row)
					{
				
					$data['team_income_level7'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level7']))
						{
							foreach($data['team_income_level7'] as $row)
							{
								$per       = ceil($setting['level7_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level7_incentive'])/100;
								$level7_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level7_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								$team_count++;$team_self_capital++;

								array_push($l7_arr,$row['id']);

							}
						
							}
							$data['l7_arr']= $l7_arr;


						}
				 		$data['level_7'] = $level7_income + $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level7_capital'] =$data['level7_capital']; 

					}
				}  

			}

			if(count($data['team_income_level1'])<=25)
			{

				if(!empty($data['team_income_level7']))
				{
					$l8_arr = array();
					foreach($data['team_income_level7'] as $row)
					{
				
					$data['team_income_level8'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level8']))
						{

							foreach($data['team_income_level8'] as $row)
							{
								$per       = ceil($setting['level8_incentive'])/100;

							if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level8_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level8_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;

								 $team_count++;$team_self_capital++;
								 array_push($l8_arr,$row['id']);


							}
						
							}
							$data['l8_arr'] = $l8_arr;
					
						}
				 		$data['level_8'] = $level8_income + $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level8_capital'] =$data['level8_capital']; 

					}
				}  

			}


			if(count($data['team_income_level1'])<=29)
			{

				if(!empty($data['team_income_level8']))
				{
					$l9_arr = array();
					foreach($data['team_income_level8'] as $row)
					{
				
					$data['team_income_level9'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level9']))
						{
							foreach($data['team_income_level9'] as $row)
							{
							$per       = ceil($setting['level9_incentive'])/100;

						if(!empty($row['capital_aum']))
							{
								

								$per              = ceil($setting['level9_incentive'])/100;
								$level5_income   += $row['capital_aum'] * $per;
								$team_capital_aum+= $row['capital_aum'];
							   	$data['level9_capital']+=$row['capital_aum']; 

								$team_self_capital+= $row['self_capital'];;
								 array_push($l9_arr,$row['id']);


							
							 $team_count++;$team_self_capital++;
							}
							}
							$data['l9_arr'] = $l9_arr;
						}
						}
				 		$data['level_9'] = $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level9_capital'] =$data['level9_capital']; 

					}
				}  

			


			if(count($data['team_income_level1'])<=33)
			{
				if(!empty($data['team_income_level9']))
				{
					$l10_arr = array();
					foreach($data['team_income_level9'] as $row)
					{
				
					$data['team_income_level10'] = $this->user_model->get_team_levels($row['id']);

						if(!empty($data['team_income_level10']))
						{
							foreach($data['team_income_level10'] as $row)
							{
								$per       = ceil($setting['level10_incentive'])/100;

								if(!empty($row['capital_aum']))
								{
									

									$per              = ceil($setting['level10_incentive'])/100;
									$level10_income   += $row['capital_aum'] * $per;
									$team_capital_aum+= $row['capital_aum'];
								   	$data['level10_capital']+=$row['capital_aum']; 

									$team_self_capital+= $row['self_capital'];;
									 array_push($l10_arr,$row['id']);



								}
							 	$team_count++;$team_self_capital++;
					
						}
						$data['l10_arr'] = $l10_arr;

						}
				 		$data['level_10'] = $level10_income + $level9_income + $level8_income +  $level7_income+ $level6_income +$level5_income + $level4_income + $level3_income +$level2_income;
				 	 	$data['level10_capital'] =$data['level10_capital']; 
				 	 	$data['team_income_bal'] = $data['level_10'];

					}
				}  

			}

				

		

	}
}
	if(!empty($level1_income) && empty($level2_income))
		{
			$data['team_income_bal']  = $level1_income;

		}
		else if(!empty($level2_income))
		{
			$data['team_income_bal']  = $level1_income + $level2_income;
		}else if(!empty($level3_income))
		{
				$data['team_income_bal']  = $level1_income + $level2_income + $level3_income;

		}else if(!empty($level4_income))
		{
				$data['team_income_bal']  = $level1_income +  $level2_income + $level3_income + $level4_income;
		}else if(!empty($level5_income))
		{
				$data['team_income_bal']  = $level1_income + $level2_income + $level3_income + $level4_income + $level5_income;

		}else if(!empty($level6_income))
		{
				$data['team_income_bal']  = $level1_income + $level2_income + $level3_income + $level4_income + $level5_income + $level6_income;

		}
		else if(!empty($level7_income))
		{
				$data['team_income_bal']  = $level1_income + $level2_income + $level3_income + $level4_income + $level5_income + $level6_income + $level7_income;
		}else if(!empty($level8_income))
		{
				$data['team_income_bal']  = $level1_income + $level2_income + $level3_income + $level4_income + $level5_income + $level6_income + $level7_income + $level8_income;
		}else if(!empty($level9_income))
		{
				$data['team_income_bal']  = $level1_income + $level2_income + $level3_income + $level4_income + $level5_income + $level6_income + $level7_income + $level8_income + $level9_income;
		}else if(!empty($level10_income))
		{
				$data['team_income_bal']  = $level1_income + $level2_income + $level3_income + $level4_income + $level5_income + $level6_income + $level7_income + $level8_income + $level9_income + $level10_income;
		}
	

		if(!empty($data['team_income_bal']))
		{
			$data['team_income_bal'] = $data['team_income_bal'];
		}else
		{
			$data['team_income_bal']=0;
		}

		/* SIP TEAM INCOME START */
		$sip_level1_income =0;$sip_level2_income =0;$sip_level3_income =0;$sip_level4_income =0;$sip_level5_income =0;$sip_level6_income =0;
		$sip_level7_income =0;$sip_level8_income =0;$sip_level9_income =0;$sip_level10_income =0;$team_sip=0;
		if($data2['sip_balance']>='10000')
		{
			$capital_aum_date = $data2['final_sip_date'];
			$after_twenty_month = 	strtotime("+20 months", strtotime($capital_aum_date));
			$after_twenty_month = date('Y-m-d',$after_twenty_month);
			if(date('Y-m-d')< $after_twenty_month )
			{
			$data['sip_level1']	= $this->user_model->get_my_levels($data2['id']);
			foreach($data['sip_level1'] as $row)
			{
				if(!empty($row['sip_balance']))
				{
					$per              	  = ceil($setting['level1_incentive'])/100;
					$sip_level1_income   += $row['sip_balance'] * $per;
				}
					

			}
			$data['sip_1'] 	= $sip_level1_income;
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=3)
					{
					foreach($data['sip_level1'] as $row)
					{

					$sip_level_2	= $this->user_model->get_my_levels($row['id']);
					if(!empty($sip_level_2))
					{

						foreach($sip_level_2 as $row)
						{
                            
							if(!empty($row['sip_balance']))
							{
								$per          = ceil($setting['level2_incentive'])/100;
				
								$team_sip+= $row['sip_balance'];
 					
						   	$sip_level2_income+= $row['sip_balance'] * $per;
						   	$team_sip+= $row['capital_aum'];
							}
							$team_count++;
						
						}
						$data['sip_2'] = $sip_level1_income + $sip_level2_income;

				}	
			}

			}

		}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=5)
					{
							if(!empty($sip_level_2))
						{
							foreach($sip_level_2 as $row)
							{
								
								$sip_level_3	= $this->user_model->get_my_levels($row['id']);
							if(!empty($sip_level_3))
							{

							$per          = ceil($setting['level3_incentive'])/100;
				
							foreach($sip_level_3 as $row)
							{
								
								if(!empty($row['sip_balance']))
								{

								   $sip_level3_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								}
								$team_count++;
						
						}
							$data['sip_3'] = $sip_level1_income + $sip_level2_income + $sip_level3_income;
						}
					}	
				}

			}

			}
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=9)
					{
							if(!empty($sip_level_3))
						{
							foreach($sip_level_3 as $row)
							{
								$sip_level_4	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_4))
						{

						$per    = ceil($setting['level4_incentive'])/100;
				

 						foreach($data['sip_level4'] as $row)
						{
							if(!empty($row['sip_balance']))
							{

							   $sip_level4_income+= $row['sip_balance'] * $per;
							   $team_sip+= $row['capital_aum'];
							}
							$team_count++;
							
						}
					$data['sip_4'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income;
			
				}
			}
		}
		}
	}
					
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=13)
					{
							if(!empty($sip_level_4))
						{
							foreach($sip_level_4 as $row)
							{

						$sip_level_5	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_5))
						{

 						foreach($data['sip_level5'] as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level5_income+= $row['sip_balance'] * $per;
						   $team_sip+= $row['capital_aum'];
						}
						$team_count++;
						
						}
						$data['sip_5'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income ;
						}
					}
				}
					
			}
			
			}

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=17)
					{
							if(!empty($sip_level_5))
						{
							foreach($sip_level_5 as $row)
							{
									$sip_level_6	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_6))
								{

									$per    = ceil($setting['level6_incentive'])/100;
								
					 					foreach($data['sip_level6'] as $row)
										{
											if(!empty($row['sip_balance']))
											{

											   $sip_level6_income+= $row['sip_balance'] * $per;
											   $team_sip+= $row['capital_aum'];
											}
											$team_count++;
											
										}
										$data['sip_6'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income;
								}

							}
						}
					}
				}

			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=21)
					{
							if(!empty($sip_level_6))
						{
							foreach($sip_level_6 as $row)
							{
								$sip_level_7	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_7))
								{

									$per    = ceil($setting['level7_incentive'])/100;
									
 								foreach($data['sip_level7'] as $row)
								{
									if(!empty($row['sip_balance']))
									{

								   $sip_level7_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
									}
								$team_count++;
						
									}
									$data['sip_7'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income;
							}

							}
						}
					}
				}
			

			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=24)
					{
							if(!empty($sip_level_7))
						{
							foreach($sip_level_7 as $row)
							{
									$sip_level_8	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_8))
								{
									
											$per    = ceil($setting['level8_incentive'])/100;
										$data['sip_level8']	= $this->user_model->get_my_levels($row['id']);
						 					foreach($data['sip_level8'] as $row)
											{
												if(!empty($row['sip_balance']))
												{

												   $sip_level8_income+= $row['sip_balance'] * $per;
												   $team_sip+= $row['capital_aum'];
												}
												$team_count++;
												
											}
											$data['sip_8'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income;
									}
									
								
								}
							}
						}
					}
				


			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=29)
					{
							if(!empty($sip_level_8))
						{
							foreach($sip_level_8 as $row)
							{
								$sip_level_9	= $this->user_model->get_my_levels($row['id']);
						if(!empty($sip_level_9))
						{

								$per    = ceil($setting['level9_incentive'])/100;
								$data['sip_level9']	= $this->user_model->get_my_levels($row['id']);
							
								if(!empty($row['sip_balance']))
								{

								   $sip_level9_income+= $row['sip_balance'] * $per;
								   $team_sip+= $row['capital_aum'];
								}
								$team_count++;
								
							}
						$data['sip_9'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income;
					
				}
			}
		}
	}
			
			
			if(!empty($data['sip_level1']))
			{
						if(count($data['sip_level1'])>=33)
					{
							if(!empty($sip_level_9))
						{
							foreach($sip_level_9 as $row)
							{
								$sip_level_10	= $this->user_model->get_my_levels($row['id']);
								if(!empty($sip_level_10))
							{

							$per    = ceil($setting['level10_incentive'])/100;
 						foreach($data['sip_level10'] as $row)
						{
						if(!empty($row['sip_balance']))
						{

						   $sip_level10_income+= $row['sip_balance'] * $per;
						   $team_sip+= $row['capital_aum'];
						}
						$team_count++;
						
					}
					$data['sip_10'] = $sip_level1_income + $sip_level2_income + $sip_level3_income + $sip_level4_income + $sip_level5_income + $sip_level6_income + $sip_level7_income + $sip_level8_income + $sip_level9_income + $sip_level10_income;
					$data['sip_team_income'] = $data['sip_10'];
					}

							}
						}
					}
			}
			
}
}

if(!empty($sip_level1_income) && empty($sip_level2_income))
			{
				$data['sip_team_income'] = $sip_level1_income;

			}else if(!empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level2_income + $sip_level1_income;

			}else if(!empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level3_income + $sip_level2_income+ $sip_level1_income;

			}
			else if(!empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;

			}else if(!empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
						{
					$data['sip_team_income'] = $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;
;

			}else if(!empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;
;
			}else if(!empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))
			{
					$data['sip_team_income'] = $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;
			}else if(!empty($sip_level8_income) && !empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))

			{
					$data['sip_team_income'] = $sip_level8_income + $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income+ $sip_level1_income;

			}else if(!empty($sip_level9_income) && !empty($sip_level8_income) && !empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))

			{
					$data['sip_team_income'] = $sip_level9_income + $sip_level8_income + $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income + $sip_level1_income;

			}else if(!empty($sip_level10_income) && !empty($sip_level9_income) && !empty($sip_level8_income) && !empty($sip_level7_income) && !empty($sip_level6_income) && !empty($sip_level5_income) && !empty($sip_level4_income) && !empty($sip_level3_income) && !empty($sip_level2_income) && !empty($sip_level1_income))

			{
					$data['sip_team_income'] = $sip_level10_income + $sip_level9_income + $sip_level8_income + $sip_level7_income + $sip_level6_income + $sip_level5_income + $sip_level4_income + $sip_level3_income + $sip_level2_income + $sip_level1_income;

			}


	if(!empty($data['sip_team_income']))
	{
		$data['sip_team_income'] = $data['sip_team_income'];
	}else
	{
		$data['sip_team_income'] = 0;
	}

	$data['cashback1'] = $this->fund_model->get_cashback_desc($data2['id']);
		if(!empty($data['cashback1']))
     {
         
				$data['cashback'] = $this->fund_model->get_cashback_desc($data2['id']);
	}else
	{

				$data['cashback'] = $this->fund_model->get_cashback_desc_last_month($data2['id']);
				//echo $this->db->last_query();die();
				if(empty($data['cashback']['withdrawl']))
				{
					$data['cashback'] = $this->fund_model->get_cashback_desc_last_month($data2['id']);
				}else
				{
					if(!empty($data['cashback']['remaining_cashback']))
					{
						$data['cashback'] = $this->fund_model->get_cashback_desc_last_month($data2['id']);
					}else
					{
						$data['cashback'] = array();

					}
			}
		}

    
		
		$data['sip_cashback1'] = $this->fund_model->get_sip_cashback_desc($data2['id']);
		if(!empty($data['sip_cashback1']))
     {
          		
			$data['sip_cashback'] = $this->fund_model->get_sip_cashback_desc($data2['id']);
		}else
		{
			$data['sip_cashback'] = $this->fund_model->get_sip_cashback_desc_last_month($data2['id']);
			if(empty($data['sip_cashback']['withdrawl']))
			{
				$data['sip_cashback'] = $this->fund_model->get_sip_cashback_desc_last_month($data2['id']);
			}else
			{
				if(!empty($data['sip_cashback']['remaining_cashback']))
				{
					$data['sip_cashback'] = $this->fund_model->get_sip_cashback_desc_last_month($data2['id']);
				}else
				{

					$data['sip_cashback'] = $this->fund_model->get_sip_cashback_desc_last_month($data2['id']);

				}

			}

		}

      
	
				
				if(!empty($data2['photo']))
				{
					$profile_photo = base_url().'uploads/profile_photo/'.$data2['photo'];
					$result = array('status'=>1,'username'=>$data2['username'],'mobile_no'=>$data2['mobile_no'],'reference_id'=>$data2['account_no'],'id'=>$data2['id'],'mobile_no'=>$data2['mobile_no'],'my_fund'=>$data2['my_fund'],'username'=>$data2['username'],'my_direct'=>$data2['my_direct'],'capital_aum'=>$data2['capital_aum'],'self_capital'=>$data2['self_capital'],'profile_photo'=>$profile_photo,'sip_balance'=>$data2['sip_balance'],'kyc_status'=>$data2['kyc_status'],'team_income_bal'=>$data['team_income_bal'],'sip_team_income'=>$data['sip_team_income'],'capital_cashback'=>$data['cashback']['capital_aum_interest'],'sip_cashback'=>$data['sip_cashback']['sip_interest']);
				}else
				{
					$result = array('status'=>1,'username'=>$data2['username'],'mobile_no'=>$data2['mobile_no'],'reference_id'=>$data2['account_no'],'id'=>$data2['id'],'mobile_no'=>$data2['mobile_no'],'my_fund'=>$data2['my_fund'],'username'=>$data2['username'],'my_direct'=>$data2['my_direct'],'capital_aum'=>$data2['capital_aum'],'self_capital'=>$data2['self_capital'],'profile_photo'=>'','sip_balance'=>$data2['sip_balance'],'kyc_status'=>$data2['kyc_status'],'team_income_bal'=>$data['team_income_bal'],'sip_team_income'=>$data['sip_team_income'],'capital_cashback'=>$data['cashback']['capital_aum_interest'],'sip_cashback'=>$data['sip_cashback']['sip_interest']);
				}
				
			}
			else
			{
				$result = array('status'=>0,'username'=>"",'mobile_no'=>"",'reference_id'=>"",'id'=>"",'mobile_no'=>"",'my_fund'=>"",'username'=>"",'my_direct'=>"",'capital_aum'=>"",'self_capital'=>"",'profile_photo'=>'','sip_balance'=>'','kyc_status'=>'','team_income_bal'=>'','sip_team_income'=>'','capital_cashback'=>'','sip_cashback'=>'');
			}


		}else
		{
			$result = array('status'=>0);
			
		}
		echo json_encode($result);
	}

	public function set_fund()
	{
		$data = $this->input->post();
		if(!empty($data))
		{
			$sip = $data['sip'];
			$this->user_model->set_fund($this->session->userdata('admin_id'),);
			$arr = array('status'=>true);
		}else
		{
			$arr = array('status'=>false);
		}
		echo json_encode($arr);
	}

	public function set_capital_auto()
	{
		$data = $this->input->post();
		if(!empty($data))
		{
			$sip = $data['sip'];

			$this->user_model->set_capital_auto($this->session->userdata('admin_id'),$sip);
			$arr = array('status'=>true);
		}else
		{
			$arr = array('status'=>false);
		}
		echo json_encode($arr);
	}

	/* UPLOAD RECEIPT page load START */
	public function upload_receipt()
	{
		$data['user']	= $this->user_model->get_user_detail($this->session->userdata('admin_id'));
		$this->load->view('admin/includes/_header',$data);
		$this->load->view('admin/fund/add_fund');
		$this->load->view('admin/includes/_footer');
	}
	/* UPLOAD RECEIPT page load END */

	public function getuserbyid()
	{
		$user_id = $this->input->post('user_id');
		if($user_id)
		{
			$data = $this->user_model->get_user_detail($user_id);
			if(!empty($data))
			{
				$result = array('status'=>1,'username'=>$data['username'],'mobile_no'=>$data['mobile_no'],'reference_id'=>$data['reference_id'],'id'=>$data['id'],'self_capital'=>$data['self_capital'],'capital_aum'=>$data['capital_aum'],'my_fund'=>$data['my_fund'],'back_entry'=>$data['back_entry']);
			}
			else
			{
				$result = array('status'=>0,'username'=>"",'mobile_no'=>"",'reference_id'=>"",'id'=>"",'self_capital'=>'','capital_aum'=>"",'my_fund'=>"",'back_entry'=>"");
			}


		}else
		{
			$result = array('status'=>0);
			
		}
		echo json_encode($result);
	}

	public function getuserforsip()
	{
		$user_id = $this->input->post('user_id');
		if($user_id)
		{
			$data = $this->user_model->get_user_detail($user_id);
			if(!empty($data))
			{
				$result = array('status'=>1,'username'=>$data['username'],'mobile_no'=>$data['mobile_no'],'reference_id'=>$data['reference_id'],'id'=>$data['id'],'self_capital'=>$data['self_capital'],'sip_balance'=>$data['capital_aum'],'my_fund'=>$data['my_fund']);
			}
			else
			{
				$result = array('status'=>0,'username'=>"",'mobile_no'=>"",'reference_id'=>"",'id'=>"",'self_capital'=>'','sip_balance'=>"",'my_fund'=>"");
			}


		}else
		{
			$result = array('status'=>0);
			
		}
		echo json_encode($result);
	}

	public function view($id)
	{
		 	$data['user'] = $this->user_model->get_user_by_id($id);
			$this->load->view('admin/includes/_header',$data);
			$this->load->view('admin/users/user_view', $data);
			$this->load->view('admin/includes/_footer');
	}

	 function str_replace_limit($search, $replace, $string, $limit = 1) {
    	$pos = strpos($string, $search);
    	
    	if ($pos === false) {
    		return $string;
    	}
    	
    	$searchLen = strlen($search);
    	
    	for ($i = 0; $i < $limit; $i++) {
    		$string = substr_replace($string, $replace, $pos, $searchLen);
    		
    		$pos = strpos($string, $search);
    		
    		if ($pos === false) {
    			break;
    		}
    	}
    	
    	return $string;
    }


    /* CRON JOB FOR Birthday Wish */
	public function auto_birthday_wish()
    {
    	$result = $this->user_model->birthday_wish();
    	foreach($result as $row)
    	{
    		$dob = $row['dob'];

    		if(date('d-m',strtotime($dob))== date('d-m'))
    		{
    			$username  = $row['username'];
    			$arr = $this->setting_model->get_sms_by_id(10);

		  		$sms = $arr['message'];
		  		$sms = str_replace('{#var#}',$row['username'],$sms);
		  		
          		$sms = urlencode($sms);
    			//send_sms(urlencode('91'.$row['mobile_no']),$sms);
    		    send_sms_text(urlencode($row['mobile_no']),$sms);

    		}
    	}
    }
    /* CRON JOB END */

    public function testsms()
    {
    	
    	send_sms_text('9552689263','Dear User,Thanks For Crediting Amount 1000');
    }

      /* CRON JOB FOR Auto Upgrade fund to 0 */
	public function set_auto_upgrade()
    {
    	$result = $this->user_model->set_auto_upgrade();
    	
    }
    /* CRON JOB END */
	
	 /* Need To Run Cron Job For this */
	public function daily_interest_aum()
	{
		$setting = $this->setting_model->get_general_settings();
		$user	 = $this->user_model->get_user_data();

		foreach($user as $userdata)
		{
			if(!empty($userdata['capital_aum']))
			{
			
				$check_empty = $this->fund_model->check_capital_cashback($userdata['id']);
				 if(empty($check_empty))
				 {
						$from_date = date('Y-m-d',strtotime('+1 day',strtotime($userdata['capital_aum_date'])));
						if($from_date<=$setting['before_date'])
						{
				 		$calculate_interest =  $userdata['capital_aum'] * ($setting['before_july_in']/100);

						 		
						}else
						{
				 			$calculate_interest =  $userdata['capital_aum'] * ($setting['lumpsum_compond']/100);

						}

						$cap_interest  = floor($calculate_interest) + $userdata['capital_aum_interest'];
						$this->fund_model->update_interest($userdata['id'],floor($calculate_interest) );
						$from_date = date('Y-m-d',strtotime('+1 day',strtotime($userdata['capital_aum_date'])));
						
				 		$next_date = date('Y-m-d',strtotime('+29 days',strtotime($userdata['capital_aum_date'])));
				 			$arrcap  = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'],
								'capital_aum_interest'=>floor($calculate_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 	$this->fund_model->save('ci_users_capitalaum_daily_interest',$arrcap);
				 	
				 }else
				 {
				 	$result = $this->fund_model->check_capital_cashback_desc($userdata['id']);
				 	$new_cap = $userdata['capital_aum'] - $result['capital_aum'];
				 	if((!empty($new_cap)) && ($new_cap> 0))
				 	{
				 		$from_date = date('Y-m-d',strtotime('+1 day',strtotime($userdata['capital_aum_date'])));
						if($from_date<=$setting['before_date'])
						{
								$newint  = 	$new_cap * ($setting['before_july_in']/100);

						}else
						{
							$newint  = 	$new_cap * ($setting['lumpsum_compond']/100);

						}
						//$calculate_interest =  $result['capital_aum_interest'] * ($setting['lumpsum_compond']/100);
						//$cap_interest  = floor($calculate_interest) + $userdata['capital_aum_interest'];
						$cap_interest  = floor($newint) + floor($result['capital_aum_interest']);
						$check_extra_cashback = $this->fund_model->check_extra_cashback($userdata['id']);
						if(empty($check_extra_cashback))
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));
					
				 			$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
							$new_ar = array(
							'user_id'=>$userdata['id'],
							'capital_aum'=>$userdata['capital_aum'],
							'new_cap'=>$new_cap,
							'new_interest'=>$newint,
							'from_date'=>$from_date,
							'to_date'=>$next_date,
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>'1');
							$this->fund_model->save('ci_extra_add_aum',$new_ar);
							//$this->fund_model->update_interest($userdata['id'],$cap_interest );



						}else
						{

							if(date('Y-m-d') > $check_extra_cashback['to_date'])
							{

								$from_date = date('Y-m-d',strtotime('+1 day',strtotime($check_extra_cashback['to_date'])));
								if($from_date<=$setting['before_date'])
								{
									$cextra_interest1 =   $check_extra_cashback['new_interest'] * ($setting['before_july_in']/100);

								}else
								{
									$cextra_interest1 =   $check_extra_cashback['new_interest'] * ($setting['lumpsum_compond']/100);

								}
							$cap_interest = $cap_interest + $cextra_interest1;
							
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($check_extra_cashback['to_date'])));
					
				 			$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
				 			$new_ar = array(
							'user_id'=>$userdata['id'],
							'capital_aum'=>$userdata['capital_aum'],
							'new_cap'=>$new_cap,
							'new_interest'=>$newint,
							'from_date'=>$from_date,
							'to_date'=>$next_date,
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>'1');
							$this->fund_model->save('ci_extra_add_aum',$new_ar);

							//$arr_u = $userdata['capital_aum'] + floor($cap_interest);
							//$arru = array('capital_aum'=>$arr_u );
							//$this->user_model->edit_user($arru,$userdata['id']);

							}else
							{
								$from_date = date('Y-m-d',strtotime('+1 day',strtotime($check_extra_cashback['from_date'])));
					
				 				$next_date = $check_extra_cashback['to_date'];
				 				$new_ar = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'],
								'new_cap'=>$new_cap,
								'new_interest'=>$newint,
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d h:i:s'),
								'created_by'=>'1');

				 			$this->fund_model->save('ci_extra_add_aum',$new_ar);


							//$this->fund_model->update_interest($userdata['id'],$cap_interest );

				 			

							}
						}
						
						if($result['to_date']== date('Y-m-d'))
						{
							//echo "hi";die();
						  $arr = $this->setting_model->get_sms_by_id(14);

						  $sms = $arr['message'];
						  //echo $sms;die();
						  $sms = str_replace('{#var#}',$result['capital_aum_interest'],$sms);
						  $sms1 = $sms;
				          $sms = urlencode($sms);
				          $contact_mobile = urlencode($userdata['mobile_no']);
				            //send_sms($contact_mobile,$sms);

         					send_sms_text($contact_mobile,$sms);
         					send_email_user($userdata['email'],$sms1);
						}
						if(date('Y-m-d') > $result['to_date'])
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));
								if($from_date<=$setting['before_date'])
								{
									$cap_interest1 =   $result['capital_aum_interest'] * ($setting['before_july_in']/100);

								}else
								{
									$cap_interest1 =   $result['capital_aum_interest'] * ($setting['lumpsum_compond']/100);

								}
							$cap_interest = $cap_interest + $cap_interest1;
							
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));
					
				 			$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
				 			$arrcap  = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'],
								'capital_aum_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_capitalaum_daily_interest',$arrcap);
							//$arr_u = $userdata['capital_aum'] + floor($cap_interest);
							//$arru = array('capital_aum'=>$arr_u );
							//$this->user_model->edit_user($arru,$userdata['id']);
						}else
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['from_date'])));
					
				 			$next_date = $result['to_date'];
				 			$arrcap  = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'],
								'capital_aum_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_capitalaum_daily_interest',$arrcap);
							
						}
				 	}
				 	else
				 	{
				 		//$calculate_interest =  $result['capital_aum_interest'] * ($setting['lumpsum_compond']/100);
						//$cap_interest  =  $userdata['capital_aum_interest'];

						//$this->fund_model->update_interest($userdata['id'],$cap_interest );
						/*if($result['to_date']== date('Y-m-d'))
						{
						*/
						if($result['to_date']== date('Y-m-d'))
						{
							//echo "hi";die();
						  $arr = $this->setting_model->get_sms_by_id(14);

						  $sms = $arr['message'];

						  $sms = str_replace('{#var#}',$result['capital_aum_interest'],$sms);
						  $sms1 = $sms;
				          $sms = urlencode($sms);
				           //echo $sms;die();
				          $contact_mobile = urlencode('91'.$userdata['mobile_no']);
				           // send_sms($contact_mobile,$sms);
         					send_sms_text($contact_mobile,$sms);
         					send_email_user($userdata['email'],$sms1);
						}	

						if(date('Y-m-d') > $result['to_date'])
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));
								if($from_date<=$setting['before_date'])
								{
									$calculate_interest =  $result['capital_aum_interest'] * ($setting['before_july_in']/100);

								}else
								{
								$calculate_interest =  $result['capital_aum_interest'] * ($setting['lumpsum_compond']/100);

								}
							$cap_interest  =  $userdata['capital_aum_interest']+ floor($calculate_interest);

							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));
					
				 			$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
				 			$arrcap  = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'] ,
								'capital_aum_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_capitalaum_daily_interest',$arrcap);
							//$arr_u = $userdata['capital_aum'] + floor($cap_interest);
							//$arru = array('capital_aum'=>$arr_u );
							//$this->user_model->edit_user($arru,$userdata['id']);
						}else
						{

							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['from_date'])));
							$cap_interest  =  $userdata['capital_aum_interest'];
				 			$next_date = $result['to_date'];
				 			$arrcap  = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'] ,
								'capital_aum_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_capitalaum_daily_interest',$arrcap);
							
						}
				 	}


				 }

				
			}

			
		}
	}


	public function approve_kyc($from_date,$to_date)
	{
		if(!empty($from_date) && !empty($to_date))
		{
		  $data['users']	= $this->user_model->get_all_pending_kyc($from_date,$to_date);

		}else
		{
		  $data['users']	= $this->user_model->get_all_pending_kyc(0,0);

		}
		//$data['query']	= $this->user_model->get_all_query();

    	$data['active_alert'] = $this->user_model->get_active_alert();

		$this->load->view('admin/includes/_header',$data);
		$this->load->view('admin/users/show_filter_list',$data);
		$this->load->view('admin/includes/_footer');
	}

	public function users_list()
	{
		$data['users']	= $this->user_model->get_all_users(0,0);
		//$data['query']	= $this->user_model->get_all_query();

    	$data['active_alert'] = $this->user_model->get_active_alert();

		$this->load->view('admin/includes/_header',$data);
		$this->load->view('admin/users/users_list',$data);
		$this->load->view('admin/includes/_footer');
	}



	public function daily_interest_sip()
	{
		$user	= $this->user_model->get_user_data();
	    $setting = $this->setting_model->get_general_settings();

		foreach($user as $userdata)
		{
			if(!empty($userdata['sip_balance']) && !empty($userdata['sip_date']))
			{
			

				$check_empty = $this->fund_model->check_sip_cashback($userdata['id']);

				 if(empty($check_empty))
				 {
				 		$from_date = date('Y-m-d',strtotime('+1 day',strtotime($userdata['sip_date'])));

				 		if($from_date<=$setting['before_date'])
					{
						$calculate_interest =  $userdata['sip_balance'] * ($setting['before_july_in']/100);


					}else
					{
						$calculate_interest =  $userdata['sip_balance'] * ($setting['sip_compound']/100);

					}
						//$cap_interest  = floor($calculate_interest) + $userdata['sip_interest'];
						//echo $cap_interest;die();
						$this->fund_model->update_interest_sip($userdata['id'],$calculate_interest );
						$from_date = date('Y-m-d',strtotime('+1 day',strtotime($userdata['sip_date'])));
				 		$next_date = date('Y-m-d',strtotime('+29 days',strtotime($userdata['sip_date'])));
				 			$arrcap  = array(
								'user_id'=>$userdata['id'],
								'sip_balance'=>$userdata['sip_balance'],
								'sip_interest'=>floor($calculate_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 	$this->fund_model->save('ci_users_sip_daily_interest',$arrcap);
				 	

				 }else
				 {
				 	$result = $this->fund_model->check_sip_cashback_desc($userdata['id']);
				 	$new    = $userdata['sip_balance'] - $result['sip_balance'];
				 	if((!empty($new)) && ($new>0))
				 	{
				 		$from_date = date('Y-m-d',strtotime('+1 day',strtotime($userdata['sip_date'])));					

				 		if($from_date<=$setting['before_date'])
					{
				 		$new_int = $new * ($setting['before_july_in']/100);
					}else
					{
						$new_int = $new * ($setting['sip_compound']/100);

					}
				 		//$calculate_interest =  $result['sip_interest'] * ($setting['sip_compound']/100);
						$cap_interest  = floor($new_int) + $userdata['sip_interest'];

						$check_extra_cashback = $this->fund_model->check_extra_cashback_sip($userdata['id']);
						if(empty($check_extra_cashback))
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($userdata['sip_date'])));					
				 			$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
						
							$new_ar = array(
							'user_id'=>$userdata['id'],
							'sip_balance'=>$userdata['sip_balance'],
							'new_sip'=>$new,
							'new_interest'=>$new_int,
							'from_date'=>$from_date,
							'to_date'=>$next_date,
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>'1');
							$this->fund_model->save('ci_extra_add_sip',$new_ar);

							//$this->fund_model->update_interest_sip($userdata['id'],floor($cap_interest) );
						
						}else 
						{

							if(date('Y-m-d') > $check_extra_cashback['to_date'])
							{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($check_extra_cashback['to_date'])));


							if($from_date<=$setting['before_date'])
					{
				 		$cextra_interest1 =   $check_extra_cashback['new_interest'] * ($setting['before_july_in']/100);

					}else
					{
						$cextra_interest1 =   $check_extra_cashback['new_interest'] * ($setting['lumpsum_compond']/100);

					}		
							$cap_interest = $cap_interest + $cextra_interest1;
							
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($check_extra_cashback['to_date'])));
					
				 			$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
				 			$new_ar = array(
							'user_id'=>$userdata['id'],
							'sip_balance'=>$userdata['capital_aum'],
							'new_sip'=>$new,
							'new_interest'=>$new_int,
							'from_date'=>$from_date,
							'to_date'=>$next_date,
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>'1');
							$this->fund_model->save('ci_extra_add_sip',$new_ar);

							//$arr_u = $userdata['sip_balance'] + floor($cap_interest);
							//$arru = array('sip_balance'=>$arr_u );
							//$this->user_model->edit_user($arru,$userdata['id']);

							}else
							{
								$from_date = date('Y-m-d',strtotime('+1 day',strtotime($check_extra_cashback['from_date'])));
					
				 				$next_date = $check_extra_cashback['to_date'];
				 				$new_ar = array(
								'user_id'=>$userdata['id'],
								'sip_balance'=>$userdata['capital_aum'],
								'new_sip'=>$new,
								'new_interest'=>$new_int,
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d h:i:s'),
								'created_by'=>'1');

				 			$this->fund_model->save('ci_extra_add_sip',$new_ar);

						//$cap_interest = floor($cap_interest) + floor($new_int);

						//$this->fund_model->update_interest_sip($userdata['id'],floor($cap_interest) );
						
				 			

							}
							
						}	
						if($result['to_date']== date('Y-m-d'))
						{
							$arr = $this->setting_model->get_sms_by_id(15);

						  $sms = $arr['message'];
						  $sms = str_replace('{#var#}',$result['sip_interest'],$sms);
						  $sms = $sms1;
				          $sms = urlencode($sms);
				          $contact_mobile = urlencode($userdata['mobile_no']);
				            //send_sms($contact_mobile,$sms);
         					send_sms_text($contact_mobile,$sms);
         					send_email_user($userdata['email'],$sms);

						}

						if(date('Y-m-d') > $result['to_date'])
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));
							if($from_date<=$setting['before_date'])
							{
									$calculate_interest =  $result['sip_interest'] * ($setting['before_july_in']/100);

							}else
							{
								$calculate_interest =  $result['sip_interest'] * ($setting['sip_compound']/100);

							}

									$cap_interest  = floor($new_int) + $userdata['sip_interest'] + $calculate_interest ;

					
				 			$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
				 			$arrcap  = array(
								'user_id'=>$userdata['id'],
								'sip_balance'=>$userdata['sip_balance'],
								'sip_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_sip_daily_interest',$arrcap);
							$arr_u = $userdata['sip_balance'] + floor($cap_interest);
							$arru = array('sip_balance'=>$arr_u );
							$this->user_model->edit_user($arru,$userdata['id']);
						}else
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['from_date'])));
					
				 			$next_date = $result['to_date'];
				 			$arrcap  = array(
								'user_id'=>$userdata['id'],
								'sip_balance'=>$userdata['sip_balance'],
								'sip_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_sip_daily_interest',$arrcap);
						}
				 	}else
				 	{
				 		//$calculate_interest =  $result['sip_interest'] * ($setting['sip_compound']/100);
						$cap_interest  =  $userdata['sip_interest'];
						//$this->fund_model->update_interest_sip($userdata['id'],floor($cap_interest) );
						if($result['to_date']== date('Y-m-d'))
						{
							$arr = $this->setting_model->get_sms_by_id(15);

						  $sms = $arr['message'];
						  $sms = str_replace('{#var#}',$result['sip_interest'],$sms);
				          $sms = urlencode($sms);
				          $contact_mobile = urlencode($userdata['mobile_no']);
				            //send_sms($contact_mobile,$sms);
         					send_sms_text($contact_mobile,$sms);
         					send_email_user($userdata['email'],$sms);
						}
						
						if(date('Y-m-d') > $result['to_date'])
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));
							if($from_date<=$setting['before_date'])
							{
								$calculate_interest =  $result['sip_interest'] * ($setting['before_july_in']/100);

							}else
							{
								$calculate_interest =  $result['sip_interest'] * ($setting['sip_compound']/100);

							}
							$cap_interest  =  $userdata['sip_interest'] + floor($calculate_interest);
				 			$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
				 			$arrcap  = array(
								'user_id'=>$userdata['id'],
								'sip_balance'=>$userdata['sip_balance'] ,
								'sip_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_sip_daily_interest',$arrcap);
							$arr_u = $userdata['sip_balance'] + floor($cap_interest);
							$arru = array('sip_balance'=>$arr_u );
							$this->user_model->edit_user($arru,$userdata['id']);
						}else
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['from_date'])));
					
				 			$next_date = $result['to_date'];
				 			$arrcap  = array(
								'user_id'=>$userdata['id'],
								'sip_balance'=>$userdata['sip_balance'] ,
								'sip_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_sip_daily_interest',$arrcap);
						
						}
				 	}
				 
				 }

				
			}

		}
	}

	/* Cron Job  END */


	/* CRON JOB FOR FUND DEBIT ADMIN OLD FUND TRANSFER */
	


/* CRON JOB FOR AUTO UPGRADE MY FUND TO SIP*/
	public function transfer_on_each_month_auto_sip()
	{
		$user	= $this->user_model->get_auto_upgrade_sip();
		foreach($user as $row)
		{

			$userdata = $this->report_model->get_last_debited_auto_sip($row['id']);
			//print_r($userdata);die();
			if(!empty($row['my_fund']) && ($row['my_fund']>=$row['amount']))
			{
				
				if($row['no_of_months']>=1)
				{
					
					
						if($row['completed_months']==$row['no_of_months'])
						{

						}else 
						{
							if(date('d',strtotime($row['issue_date']))==date('d'))
							{
								if(empty($userdata['completed_months']))
								{
									$month1 = '1';
									$my_fund = $row['my_fund'] - $row['amount'];

								}else
								{
									
									$month1 = $row['completed_months'] + 1;
									$my_fund = $row['my_fund'] - $row['amount'];
								
								
								}

								$arr = array('my_fund'=>$my_fund);

								$remaining_month = $row['no_of_months'] - $month1;


								$arru = array(
							'user_id'=>$row['user_id'],
							'amount' =>$row['amount'],
							'my_fund'=>$my_fund,
							'issue_date'=>date('Y-m-d'),
							'completed_months'=>$month1,
							'auto_id'=>$row['id'],
							'no_of_months'=>$row['no_of_months'],
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>1,
							);
							$this->fund_model->add_auto_sip($arru);
							$comp = array('completed_months'=>$month1);

							$this->fund_model->edit_auto_sip($comp,$row['id']);

								$this->user_model->edit_user($arr,$userdata['user_id']);
								$dataarr = array(
						'user_id' => $row['user_id'],
						'amount'  => $row['amount'],
						'account_holder_name' => strtoupper($row['bank_holder_name']),
						'approved'=>1,
						'description' => "My Fund Debited By"." ".$row['amount'],
						'created_at'  => date('Y-m-d : h:i:s'),
						'created_by'  => '1',
						'updated_at'  => date('Y-m-d : h:i:s'),
						'updated_by'  => '1',
						);
							$this->fund_model->save('ci_funds',$dataarr);	

							
						$transactions = array(
						'debit' => $row['amount'],
						'credit'  => '0',
						'payment_mode' => '',
						'description'=>'My Fund Debited By'." ".$row['amount'],
						'transaction_date' => date('Y-m-d'),
						);		
							$this->fund_model->save('ci_transactions',$transactions);
						$arrdata = array(
							'user_id'=>$row['user_id'],
							'activity'=>'My Fund Debited',
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>$row['user_id'],
							);

							$this->fund_model->add_log($arrdata);


						}
						}
					
					
				}

			}
		}

	}


	/* Function TO SEND SCHEDULED SMS */
	public function send_schedule_sms()
	{
		$sms  = $_POST['msg1'];
		$date = $_POST['date'];
		$date = str_replace(' pm','pm',$date);

		$date = str_replace(' am','am',$date);
		//echo $date;die();
		 if(!empty($sms) && !empty($date))
		 {
			$user	= $this->user_model->get_user_data();

			foreach($user as $userdata)
			{
				send_schedule_sms(urlencode($userdata['mobile_no']),urlencode($sms),urlencode($date));
				//send_schedule_sms(urlencode('9552689263'),urlencode($sms),urlencode($date));
		 	}
		 }
			$this->session->set_flashdata('success', 'SMS Sent Successfully!!');
			redirect(base_url('admin/users/admin_bulk_send'));

	}

	/* Function TO SEND SCHEDULED SMS */

	public function update_order_status()
	{
		$data = $this->input->post();
		if(!empty($data))
		{
			$arr = array('status'=>$data['status']);
			$this->user_model->update_order_status($arr,$data['id']);
			$product = $this->setting_model->get_collateral_by_id($data['id']);
			$qty = $data['quantity'];
			$total  = $qty * $product['amount'];
			$remain = $product['total_amount'] - $total;
			$stock  = $product['quantity'] - $qty;
			$new_ar = array(
				'quantity'=>$stock,
				'total_amount'=>$remain
			);
			$this->user_model->update_stock($new_ar,$data['id']);
		 	$settings 	=  $this->setting_model->get_general_settings();

			$txt="";
			$user_info = $this->user_model->get_order_info($data['id']);

			if($data['status']=='Order Sent')
			{


		$this->load->library('MYPDF');

		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Packing Slip');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Packing Slip', PDF_HEADER_STRING, array(0,64,255), 
  			array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print

		$user_info = $this->user_model->get_order_info($data['id']);
		
		$txt .= '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
	
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><td width="50%" style="text-align:center">From<br>'.$settings["application_name"]."<br>".$settings["company_address"].'</td>
			
			<td width="50%" style="text-align:center">To<br>'.$user_info["name"]."<br> ".$user_info["address"]."<br>".$user_info["mobile"].'</td>
			</tr>';
		$capital_aum="1";

			$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="6" style="text-align:center">'.$capital_aum.'</th><tr class="bo"><td colspan="7" class="bo" style="text-align:center;">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["logo"].'" style="height: 100px;margin-left: 40px;width: 106px;color:#fff;"></td></tr><tr><td colspan="7" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	$txt.='</body></html>';
	 

	$pdf->writeHTML($txt, true, false, true, false, '');
	ob_end_clean();

    $pdf->Output('Packing_Slip.pdf', 'I');
	}

	redirect(base_url().'admin/users/collaterals_enquiry/0/0');
		}
	}

	public function daily_capital_return()
	{
		$data['active_alert'] = $this->user_model->get_active_alert();
		$data['daily_return'] = $this->user_model->get_daily_capital_return($this->session->userdata('admin_id'));
		$data['daily_extra_return'] = $this->user_model->get_daily_extra_capital_return($this->session->userdata('admin_id'));
		$data['user'] 		  = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
	
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/report/daily_capital_return', $data);
		$this->load->view('admin/includes/_footer');
	
	}

	public function download_daily_capital_return()
	{

		$daily_return = $this->user_model->get_daily_capital_return($this->session->userdata('admin_id'));
	    $settings = $this->setting_model->get_general_settings();
		$data['active_alert'] = $this->user_model->get_active_alert();
		$daily_return = $this->user_model->get_daily_capital_return($this->session->userdata('admin_id'));
		$daily_extra_return = $this->user_model->get_daily_extra_capital_return($this->session->userdata('admin_id'));
		$txt="";
		$this->load->library('MYPDF');

		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('TDS Report');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  			array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print

		
		
		$txt .= '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th>
			<th  width="20%" style="text-align:center">Account No</th>
			<th  width="20%" style="text-align:center">Name</th>

			<th  width="20%" style="text-align:center">Capital AUM</th>
			<th  width="20%" style="text-align:center">Capital Cashback</th>

			</tr><tbody>';
			$capital_aum=0;$id=1;
			if(!empty($daily_return))
			{

				foreach($daily_return as $row)
				{

					if(!empty($row['capital_aum_interest']))
					{
						  
					$capital_aum+=$row['capital_aum_interest'];
               
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				    <td style="text-align:center">' .$row['username'].'</td>

					<td style="text-align:center">' .$row['account_no'].'</td>

            		<td class="total" style="text-align:center">'.$row['capital_aum'].'</td>
          			<td class="total" style="text-align:center">'.($row['capital_aum_interest']/30).'</td></tr>';

					$id++;
				}
				}
			}

			if(!empty($daily_extra_return))
			{
				foreach($daily_extra_return as $row)
				{

					if(!empty($row['new_interest']))
					{
						  
					$capital_aum+=$row['new_interest'];
               
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				    <td style="text-align:center">' .$row['username'].'</td>

					<td style="text-align:center">' .$row['account_no'].'</td>

            		<td class="total" style="text-align:center">'.$row['new_cap'].'</td>
            		<td class="total" style="text-align:center">'.($row['new_interest']/30).'</td>

					</tr>';

					$id++;
				}
				}
	
			}

			$words = $this->getIndianCurrency($capital_aum);
	
	$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
	<th colspan="6" style="text-align:center">'.$capital_aum.'</th></tr>
	<tr><th colspan="2">Total In Words:</th><th colspan="6" style="text-align:center">'.$words.'</th></tr><tr class="bo" ><td colspan="7" class="bo" style="text-align:center;">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["logo"].'" style="height: 100px;margin-left: 40px;width: 106px;color:#fff;"></td></tr><tr><td colspan="7" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
	$txt.='	</tfoot></table>';
	$txt.='</body></html>';
	 

	$pdf->writeHTML($txt, true, false, true, false, '');
	ob_end_clean();

    $pdf->Output('daily_capital_return.pdf', 'I');
	
	}


	public function daily_sip_return()
	{
		$data['active_alert'] = $this->user_model->get_active_alert();
		$data['daily_return'] = $this->user_model->get_daily_sip_return($this->session->userdata('admin_id'));
		$data['daily_sip_extra_return'] = $this->user_model->get_daily_extra_sip_return($this->session->userdata('admin_id'));

		$data['user'] 		  = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));
	
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/report/daily_sip_return', $data);
		$this->load->view('admin/includes/_footer');
	
	}

	public function download_daily_sip_return()
	{
		$daily_return = $this->user_model->get_daily_sip_return($this->session->userdata('admin_id'));
	    $settings = $this->setting_model->get_general_settings();
		$daily_sip_extra_return = $this->user_model->get_daily_extra_sip_return($this->session->userdata('admin_id'));

		$txt="";
		$this->load->library('MYPDF');

		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);
		$pdf->SetTitle('Daily SIP Return');

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('PdfWithCodeigniter');
		//$pdf->SetTitle('PDF');
		$pdf->SetSubject('PdfWithCodeigniter');
		$pdf->SetKeywords('TCPDF, PDF, example, test, codeigniter');

		// set default header data
 		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 
 		'Total Active Account', PDF_HEADER_STRING, array(0,64,255), 
  			array(0,64,100));
		//$pdf->SetHeaderData('http://localhost/uneed_new/assets/img/d49546b7dbe2645374937b94c4fa1cfa.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('times', '', 11);

		// add a page
		$pdf->AddPage();
		$id=1;
		// set some text to print

		
		
		$txt .= '<html>
			<head></head>
			<body>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<table   class="table table-bordered table-striped" style="text-align:center;margin-left:150px;" width="100%"><tbody>
									<tr >
										<td   style="width: 90px; height: 80px;"><img src="'.base_url().$settings["logo"].'" style="height: 100px;color: #fff;"> </td>
										<td  style="text-align:center;"><span style="text-align:center;font-size:20px;font-weight:bold;">'.$settings["application_name"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_address"].'</span><br /><span style="text-align:center;font-size:small;">'.$settings["company_contact"].'</span></td>
									</tr>
								</tbody>
							</table><br>';
		
		$txt.= '<table border="1" class="table table-bordered table-striped" width="100%">
			<tr><th width="5%" style="text-align:center">ID</th>
			<th width="20%" style="text-align:center">Date</th>
			<th  width="20%" style="text-align:center">Account No</th>
			<th  width="20%" style="text-align:center">Name</th>

			<th  width="20%" style="text-align:center">SIP AUM</th>
			<th  width="20%" style="text-align:center">SIP Cashback</th>

			</tr><tbody>';
			$capital_aum=0;$id=1;
			if(!empty($daily_return))
			{

				foreach($daily_return as $row)
				{
					if(!empty($row['sip_balance']))
					{
						  
					$capital_aum+=$row['sip_interest'];
               
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				    <td style="text-align:center">' .$row['username'].'</td>

					<td style="text-align:center">' .$row['account_no'].'</td>


					<td style="text-align:center">' .$row['username'].'</td>
            		<td class="total" style="text-align:center">'.$row['sip_balance'].'</td>
            		<td class="total" style="text-align:center">'.($row['sip_interest']/30).'</td></tr>';

						$id++;
					}
				}
			}

			if(!empty($daily_sip_extra_return))
			{
				foreach($daily_sip_extra_return as $row)
				{
					if(!empty($row['new_sip']))
					{
						  
					$capital_aum+=$row['new_interest'];
               
					$txt.=	'<tr>
					<td style="text-align:center">' .$id.'</td>
				    <td style="text-align:center">' .$row['username'].'</td>

					<td style="text-align:center">' .$row['account_no'].'</td>


					<td style="text-align:center">' .$row['username'].'</td>
            		<td class="total" style="text-align:center">'.$row['new_sip'].'</td>
            		<td class="total" style="text-align:center">'.($row['new_interest']/30).'</td>

					</tr>';

						$id++;
					}
				}
			}
			$words = $this->getIndianCurrency($capital_aum);
	
		$txt.='<tfoot><tr><th  colspan="2"  style="text-align:center">Total:</th>
		<th colspan="6" style="text-align:center">'.$capital_aum.'</th></tr>
		<tr><th colspan="2">Total In Words:</th><th colspan="6" style="text-align:center">'.$words.'</th></tr><tr class="bo" ><td colspan="7" class="bo" style="text-align:center;">Note:No Signature is required as this is computer generated receipt. <img class="pull-right" src="'.base_url().$settings["logo"].'" style="height: 100px;margin-left: 40px;width: 106px;color:#fff;"></td></tr><tr><td colspan="7" class="bo" style="text-align:center;">We look forward to a lifetime relationship with you and assure you our best services at all times.</td></tr>';
		$txt.='	</tfoot></table>';
		$txt.='</body></html>';
	 

	$pdf->writeHTML($txt, true, false, true, false, '');
	ob_end_clean();

    $pdf->Output('daily_sip_return.pdf', 'I');
	}

	public function view_royalty_capital($level1_income,$id,$level,$pos)
	{
		$segment_3 = $this->uri->segment('4');
    	$last = substr($segment_3, -2);
		$level1_income = $level1_income;
		$level1_income = substr_replace($level1_income, '.',$pos,$level1_income);
		
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));

		$data['level_user'] = $this->user_model->get_user_by_id($id);
		$data['active_alert'] = $this->user_model->get_active_alert();
		$data['level1_income'] = $level1_income.$last;
		$data['level']		= $level;
		$this->load->view('admin/includes/_header', $data);

    	$this->load->view('admin/users/view_royalty_capital');

    	$this->load->view('admin/includes/_footer');


	}

	public function view_royalty_sip($level1_income,$id,$level,$pos)
	{
		$segment_3 = $this->uri->segment('4');
    	$last = substr($segment_3, -2);
		$level1_income = $level1_income;
		$level1_income = substr_replace($level1_income, '.',$pos,$level1_income);
		
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));

		$data['level_user'] = $this->user_model->get_user_by_id($id);
		$data['active_alert'] = $this->user_model->get_active_alert();
		$data['level1_income'] = $level1_income.$last;
		$data['level']		= $level;
		$this->load->view('admin/includes/_header', $data);

    	$this->load->view('admin/users/view_royalty_sip');

    	$this->load->view('admin/includes/_footer');


	}

	public function view_total_royalty_count($level1_income,$id,$level,$pos)
	{
		$segment_3 = $this->uri->segment('4');
    	$last = substr($segment_3, -2);
    	
		$level1_income = $level1_income;
		$level1_income = substr_replace($level1_income, '.',$pos,$level1_income);
		
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));

		$data['level_user'] = $this->user_model->get_user_by_id($id);
		$data['active_alert'] = $this->user_model->get_active_alert();
		$data['level1_income'] = $level1_income.$last;
		$data['level']		= $level;
		$this->load->view('admin/includes/_header', $data);

    	$this->load->view('admin/users/view_total_royalty_count');

    	$this->load->view('admin/includes/_footer');


	}
	
	public function view_total_royalty($level1_income,$id,$level,$pos)
	{
		$segment_3 = $this->uri->segment('4');
    	$last = substr($segment_3, -2);
    	
		$level1_income = $level1_income;
		$level1_income = substr_replace($level1_income, '.',$pos,$level1_income);
		
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));

		$data['level_user'] = $this->user_model->get_user_by_id($id);
		$data['active_alert'] = $this->user_model->get_active_alert();
		$data['level1_income'] = $level1_income.$last;
		$data['level']		= $level;
		$this->load->view('admin/includes/_header', $data);

    	$this->load->view('admin/users/view_total_royalty');

    	$this->load->view('admin/includes/_footer');


	}

	public function view_total_royalty_sip($level1_income,$id,$level,$pos)
	{
		$segment_3 = $this->uri->segment('4');
    	$last = substr($segment_3, -2);
    	
		$level1_income = $level1_income;
		$level1_income = substr_replace($level1_income, '.',$pos,$level1_income);
		
		$data['user'] = $this->user_model->get_user_by_id($this->session->userdata('admin_id'));

		$data['level_user'] = $this->user_model->get_user_by_id($id);
		$data['active_alert'] = $this->user_model->get_active_alert();
		$data['level1_income'] = $level1_income.$last;
		$data['level']		= $level;
		$this->load->view('admin/includes/_header', $data);

    	$this->load->view('admin/users/view_total_royalty_sip');

    	$this->load->view('admin/includes/_footer');


	}
  
  public function transfer_on_each_date()
	{
		$user	= $this->user_model->get_user_data();
		
		foreach($user as $row)
		{

			$userdata = $this->report_model->get_last_fund_debited($row['id']);
          /*if($row['id']=='380')
            {
            echo "hi";echo $row['old_fund_date'];die();
          }*/
			 
			if(!empty($userdata['total_amount']))
			{
				
				if($userdata['completed_months']>=1)
				{
					
					
						if($userdata['completed_months']==$userdata['no_of_months'])
						{

						}else 
						{
						if(date('d',strtotime($row['old_fund_add_date']))=='30')
						{
							if(date('M')=='Feb')
							{
								$month1 = $userdata['completed_months']+1;
								$my_fund = $userdata['my_fund'] + $userdata['ecs_amount'];
								$arr = array('my_fund'=>$my_fund,'old_fund_completed'=>$month1);

								$remaining_month = $userdata['no_of_months'] - $month1;
								$total_debited  = $userdata['amount_debited'] +$userdata['ecs_amount'];
								$remaining_amount = $userdata['total_amount']- $total_debited;

								$arru = array(
							'ecs_amount'=>$userdata['ecs_amount'],
							'no_of_months'=>$userdata['no_of_months'],
							'total_amount'=>$userdata['total_amount'],
							'completed_months'=>$month1,
							'amount_debited'=>$total_debited,
							'remaining_month'=>$remaining_month,
							'remaining_amount'=>$remaining_amount,
							'updated_at'=>date('Y-m-d h:i:s'),
							'updated_by'=>1,
							);
							//print_r($arru);die();
							$this->fund_model->update_fund_debit($arru,$userdata['user_id']);

								$this->user_model->edit_user($arr,$userdata['user_id']);
								$dataarr = array(
						'user_id' => $userdata['id'],
						'amount'  => $userdata['ecs_amount'],
						'account_holder_name' => strtoupper($userdata['username']),
						'approved'=>1,
						'description' => "My Fund Credited By"." ".$userdata['ecs_amount'],
						'created_at'  => date('Y-m-d : h:i:s'),
						'created_by'  => '1',
						'updated_at'  => date('Y-m-d : h:i:s'),
						'updated_by'  => '1',
						);
							$this->fund_model->save('ci_funds',$dataarr);	

							
						$transactions = array(
						'debit' => '0',
						'credit'  => $userdata['ecs_amount'],
						'payment_mode' => '',
						'description'=>'My Fund Credited By'." ".$userdata['ecs_amount'],
						'transaction_date' => date('Y-m-d'),
						);		
							$this->fund_model->save('ci_transactions',$transactions);
						$arrdata = array(
							'user_id'=>$userdata['id'],
							'activity'=>'My Fund Credited',
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>$userdata['id'],
							);

							$this->fund_model->add_log($arrdata);


						}else
						{
							if(date('d',strtotime($userdata['old_fund_add_date']))==date('d'))
							{

							
								$month1 = $userdata['completed_months']+1;
								$my_fund = $userdata['my_fund'] + $userdata['ecs_amount'];
								$arr = array('my_fund'=>$my_fund,'old_fund_completed'=>$month1);

								$remaining_month = $userdata['no_of_months'] - $month1;
								$total_debited  = $userdata['amount_debited'] +$userdata['ecs_amount'];
								$remaining_amount = $userdata['total_amount']- $total_debited;

								$arru = array(
							'ecs_amount'=>$userdata['ecs_amount'],
							'no_of_months'=>$userdata['no_of_months'],
							'total_amount'=>$userdata['total_amount'],
							'completed_months'=>$month1,
							'amount_debited'=>$total_debited,
							'remaining_month'=>$remaining_month,
							'remaining_amount'=>$remaining_amount,
							'updated_at'=>date('Y-m-d h:i:s'),
							'updated_by'=>1,
							);
							//print_r($arru);die();
							$this->fund_model->update_fund_debit($arru,$userdata['user_id']);

								$this->user_model->edit_user($arr,$userdata['user_id']);
								$dataarr = array(
						'user_id' => $userdata['id'],
						'amount'  => $userdata['ecs_amount'],
						'account_holder_name' => strtoupper($userdata['username']),
						'approved'=>1,
						'description' => "My Fund Credited By"." ".$userdata['ecs_amount'],
						'created_at'  => date('Y-m-d : h:i:s'),
						'created_by'  => '1',
						'updated_at'  => date('Y-m-d : h:i:s'),
						'updated_by'  => '1',
						);
							$this->fund_model->save('ci_funds',$dataarr);	

							
						$transactions = array(
						'debit' => '0',
						'credit'  => $userdata['ecs_amount'],
						'payment_mode' => '',
						'description'=>'My Fund Credited By'." ".$userdata['ecs_amount'],
						'transaction_date' => date('Y-m-d'),
						);		
							$this->fund_model->save('ci_transactions',$transactions);
						$arrdata = array(
							'user_id'=>$userdata['id'],
							'activity'=>'My Fund Credited',
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>$userdata['id'],
							);

							$this->fund_model->add_log($arrdata);


						}
						}

					}else 
					if(!empty($userdata['old_fund_add_date'])){
						if(date('d',strtotime($userdata['old_fund_add_date']))==date('d'))
							{
                         
								//echo "hi";die();	
								if(empty($userdata['completed_months'])){
                                  								$month1 = 1;

                                }else{
                                  								$month1 = $userdata['completed_months']+1;

                                }
								$my_fund = $userdata['my_fund'] + $userdata['ecs_amount'];
								$arr = array('my_fund'=>$my_fund,'old_fund_completed'=>$month1);

								$remaining_month = $userdata['no_of_months'] - $month1;
								$total_debited  = $userdata['amount_debited'] +$userdata['ecs_amount'];
								$remaining_amount = $userdata['total_amount']- $total_debited;

								$arru = array(
							'ecs_amount'=>$userdata['ecs_amount'],
							'no_of_months'=>$userdata['no_of_months'],
							'total_amount'=>$userdata['total_amount'],
							'completed_months'=>$month1,
							'amount_debited'=>$total_debited,
							'remaining_month'=>$remaining_month,
							'remaining_amount'=>$remaining_amount,
							'updated_at'=>date('Y-m-d h:i:s'),
							'updated_by'=>1,
							);
							//print_r($arru);die();
							$this->fund_model->update_fund_debit($arru,$userdata['user_id']);

								$this->user_model->edit_user($arr,$userdata['user_id']);
								$dataarr = array(
						'user_id' => $userdata['id'],
						'amount'  => $userdata['ecs_amount'],
						'account_holder_name' => strtoupper($userdata['username']),
						'approved'=>1,
						'description' => "My Fund Credited By"." ".$userdata['ecs_amount'],
						'created_at'  => date('Y-m-d : h:i:s'),
						'created_by'  => '1',
						'updated_at'  => date('Y-m-d : h:i:s'),
						'updated_by'  => '1',
						);
							$this->fund_model->save('ci_funds',$dataarr);	

							
						$transactions = array(
						'debit' => '0',
						'credit'  => $userdata['ecs_amount'],
						'payment_mode' => '',
						'description'=>'My Fund Credited By'." ".$userdata['ecs_amount'],
						'transaction_date' => date('Y-m-d'),
						);		
							$this->fund_model->save('ci_transactions',$transactions);
						$arrdata = array(
							'user_id'=>$userdata['id'],
							'activity'=>'My Fund Credited',
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>$userdata['id'],
							);

							$this->fund_model->add_log($arrdata);


						}
					}
						}
					
					
				}

			}else
			{
				if(date('d',strtotime($row['old_fund_add_date']))==date('d'))
							{
                         
								//echo "hi";die();	
								if(empty($userdata['completed_months'])){
                                  								$month1 = 1;

                                }else{
                                  								$month1 = $userdata['completed_months']+1;

                                }
								$my_fund = $row['my_fund'] + $row['old_fund'];
								$arr = array('my_fund'=>$my_fund,'old_fund_completed'=>$month1);
								if(empty($userdata['no_of_months']))
								{
									$remaining_month = $row['month_count'] - $month1;
								$total_debited  	= $row['old_fund'];
								$remaining_amount = ($row['old_fund']* $row['month_count']) - $row['old_fund'];
								}else
								{
									$remaining_month = $userdata['no_of_months'] - $month1;
								$total_debited  = $userdata['amount_debited'] +$userdata['ecs_amount'];
								$remaining_amount = $userdata['total_amount']- $total_debited;
								}
								

								$arru = array(
                                  'user_id'=>$row['id'],
							'ecs_amount'=>$row['old_fund'],
							'no_of_months'=>$row['month_count'],
							'total_amount'=>$row['old_fund'],
							'completed_months'=>$month1,
							'amount_debited'=>$total_debited,
							'remaining_month'=>$remaining_month,
							'remaining_amount'=>$remaining_amount,
							'updated_at'=>date('Y-m-d h:i:s'),
							'updated_by'=>1,
							);
							//print_r($arru);die();
							$this->report_model->insert_fund_de($arru);

								$this->user_model->edit_user($arr,$row['id']);
								$dataarr = array(
						'user_id' => $row['id'],
						'amount'  => $row['old_fund'],
						'account_holder_name' => strtoupper($row['username']),
						'approved'=>1,
						'description' => "My Fund Credited By"." ".$row['old_fund'],
						'created_at'  => date('Y-m-d : h:i:s'),
						'created_by'  => '1',
						'updated_at'  => date('Y-m-d : h:i:s'),
						'updated_by'  => '1',
						);
							$this->fund_model->save('ci_funds',$dataarr);	

							
						$transactions = array(
						'debit' => '0',
						'credit'  => $row['old_fund'],
						'payment_mode' => '',
						'description'=>'My Fund Credited By'." ".$row['old_fund'],
						'transaction_date' => date('Y-m-d'),
						);		
							$this->fund_model->save('ci_transactions',$transactions);
						$arrdata = array(
							'user_id'=>$row['id'],
							'activity'=>'My Fund Credited',
							'created_at'=>date('Y-m-d h:i:s'),
							'created_by'=>$row['id'],
							);

							$this->fund_model->add_log($arrdata);
			}
		}
		}

	}
  
  
  
  	public function re_check_daily_interest_aum()
	{
		$setting = $this->setting_model->get_general_settings();
		$user	 = $this->user_model->get_user_data();

		foreach($user as $userdata)
		{
			if(!empty($userdata['capital_aum']))
			{
			
				$check_empty = $this->fund_model->check_capital_cashback($userdata['id']);
				 if(empty($check_empty))
				 {
				 		$from_date = date('Y-m-d',strtotime('+1 day',strtotime($userdata['capital_aum_date'])));

				 		if($from_date<=$setting['before_date'])
				 		{
				 			$calculate_interest =  $userdata['capital_aum'] * ($setting['before_july_in']/100);
				 		}else{
				 			$calculate_interest =  $userdata['capital_aum'] * ($setting['lumpsum_compond']/100);
				 		}
				 		
						$cap_interest  = floor($calculate_interest) + $userdata['capital_aum_interest'];
						$this->fund_model->update_interest($userdata['id'],floor($calculate_interest) );
						$check_from_date = $this->fund_model->check_int_ex($from_date);
						if(empty($check_from_date))
						{
							$next_date = date('Y-m-d',strtotime('+29 days',strtotime($userdata['capital_aum_date'])));
				 			$arrcap  = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'],
								'capital_aum_interest'=>floor($calculate_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_capitalaum_daily_interest',$arrcap);
						}
				 		
				 	
				 }else
				 {
				 	$result = $this->fund_model->check_capital_cashback_desc($userdata['id']);
				 	$new_cap = $userdata['capital_aum'] - $result['capital_aum'];
				 	if((!empty($new_cap)) && ($new_cap> 0))
				 	{
				 		
				 		$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));


				 		if($from_date<=$setting['before_date'])
				 		{
				 			$newint  = 	$new_cap * ($setting['before_july_in']/100);
				 		}else{
				 			$newint  = 	$new_cap * ($setting['lumpsum_compond']/100);

				 		}
				 		
				 		echo "hi";die();
						//$calculate_interest =  $result['capital_aum_interest'] * ($setting['lumpsum_compond']/100);
						//$cap_interest  = floor($calculate_interest) + $userdata['capital_aum_interest'];
						$cap_interest  = floor($newint) + floor($result['capital_aum_interest']);
						$check_extra_cashback = $this->fund_model->check_extra_cashback($userdata['id']);
						if(empty($check_extra_cashback))
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));
							$check_from_date = $this->fund_model->check_int_ex_add($from_date);
							if(empty($check_from_date))
							{
								$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
								$new_ar = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'],
								'new_cap'=>$new_cap,
								'new_interest'=>$newint,
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d h:i:s'),
								'created_by'=>'1');
								$this->fund_model->save('ci_extra_add_aum',$new_ar);
								//$this->fund_model->update_interest($userdata['id'],$cap_interest );
							}
				 			



						}else
						{

							if(date('Y-m-d') > $check_extra_cashback['to_date'])
							{

							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($check_extra_cashback['to_date'])));


						 		if($from_date<=$setting['before_date'])
						 		{
									$cextra_interest1 =   $check_extra_cashback['new_interest'] * ($setting['before_july_in']/100);

						 		}else{
						 			$cextra_interest1 =   $check_extra_cashback['new_interest'] * ($setting['lumpsum_compond']/100);

						 		}

							$cap_interest = $cap_interest + $cextra_interest1;
							
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($check_extra_cashback['to_date'])));
					
				 			$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
				 			$check_from_date = $this->fund_model->check_int_ex_add($from_date);
							if(empty($check_from_date))
							{
					 			$new_ar = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'],
								'new_cap'=>$new_cap,
								'new_interest'=>$newint,
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d h:i:s'),
								'created_by'=>'1');
								$this->fund_model->save('ci_extra_add_aum',$new_ar);
							}
							//$arr_u = $userdata['capital_aum'] + floor($cap_interest);
							//$arru = array('capital_aum'=>$arr_u );
							//$this->user_model->edit_user($arru,$userdata['id']);

							}else
							{
								$from_date = date('Y-m-d',strtotime('+1 day',strtotime($check_extra_cashback['from_date'])));
					
				 				$next_date = $check_extra_cashback['to_date'];
				 			$check_from_date = $this->fund_model->check_int_ex_add($from_date);
							if(empty($check_from_date))
							{
				 				$new_ar = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'],
								'new_cap'=>$new_cap,
								'new_interest'=>$newint,
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d h:i:s'),
								'created_by'=>'1');

				 				$this->fund_model->save('ci_extra_add_aum',$new_ar);
				 			}

							//$this->fund_model->update_interest($userdata['id'],$cap_interest );

				 			

							}
						}
						
						if($result['to_date']== date('Y-m-d'))
						{
							//echo "hi";die();
						  $arr = $this->setting_model->get_sms_by_id(14);

						  $sms = $arr['message'];
						  //echo $sms;die();
						  $sms = str_replace('{#var#}',$result['capital_aum_interest'],$sms);
						  $sms1 = $sms;
				          $sms = urlencode($sms);
				          $contact_mobile = urlencode($userdata['mobile_no']);
				            //send_sms($contact_mobile,$sms);

         					send_sms_text($contact_mobile,$sms);
         					send_email_user($userdata['email'],$sms1);
						}
						if(date('Y-m-d') > $result['to_date'])
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));


						 		if($from_date<=$setting['before_date'])
						 		{
						 			$cap_interest1 =   $result['capital_aum_interest'] * ($setting['before_july_in']/100);

						 		}else
						 		{
						 										
						 			$cap_interest1 =   $result['capital_aum_interest'] * ($setting['lumpsum_compond']/100);

						 		}

							$cap_interest = $cap_interest + $cap_interest1;
							
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));
					
				 			$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
				 			$check_from_date = $this->fund_model->check_int_ex($from_date);
							if(empty($check_from_date))
							{
				 				$arrcap  = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'],
								'capital_aum_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 				$this->fund_model->save('ci_users_capitalaum_daily_interest',$arrcap);
				 			}
							//$arr_u = $userdata['capital_aum'] + floor($cap_interest);
							//$arru = array('capital_aum'=>$arr_u );
							//$this->user_model->edit_user($arru,$userdata['id']);
						}else
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['from_date'])));
					
				 			$next_date = $result['to_date'];
				 			$check_from_date = $this->fund_model->check_int_ex($from_date);
							if(empty($check_from_date))
							{
								$arrcap  = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'],
								'capital_aum_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_capitalaum_daily_interest',$arrcap);
							
							}
				 			
						}
				 	}
				 	else
				 	{
				 		//$calculate_interest =  $result['capital_aum_interest'] * ($setting['lumpsum_compond']/100);
						//$cap_interest  =  $userdata['capital_aum_interest'];

//$this->fund_model->update_interest($userdata['id'],$cap_interest );
						/*if($result['to_date']== date('Y-m-d'))
						{
						*/
						if($result['to_date']== date('Y-m-d'))
						{
							//echo "hi";die();
						  $arr = $this->setting_model->get_sms_by_id(14);

						  $sms = $arr['message'];

						  $sms = str_replace('{#var#}',$result['capital_aum_interest'],$sms);
						  $sms1 = $sms;
				          $sms = urlencode($sms);
				           //echo $sms;die();
				          $contact_mobile = urlencode('91'.$userdata['mobile_no']);
				           // send_sms($contact_mobile,$sms);
         					send_sms_text($contact_mobile,$sms);
         					send_email_user($userdata['email'],$sms1);
						}	

						if(date('Y-m-d') > $result['to_date'])
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));


						 		if($from_date<=$setting['before_date'])
						 		{
									$calculate_interest =  $result['capital_aum_interest'] * ($setting['before_july_in']/100);

						 		}else
						 		{
						 			$calculate_interest =  $result['capital_aum_interest'] * ($setting['lumpsum_compond']/100);

						 		}

							$cap_interest  =  $userdata['capital_aum_interest']+ floor($calculate_interest);

							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['to_date'])));
					
				 			$next_date = date('Y-m-d',strtotime('+29 days',strtotime($from_date)));
				 			$check_from_date = $this->fund_model->check_int_ex($from_date);
							if(empty($check_from_date))
							{
								$arrcap  = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'] ,
								'capital_aum_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_capitalaum_daily_interest',$arrcap);
							}
				 			
							//$arr_u = $userdata['capital_aum'] + floor($cap_interest);
							//$arru = array('capital_aum'=>$arr_u );
							//$this->user_model->edit_user($arru,$userdata['id']);
						}else
						{
							$from_date = date('Y-m-d',strtotime('+1 day',strtotime($result['from_date'])));
							$cap_interest  =  $userdata['capital_aum_interest'];
				 			$next_date = $result['to_date'];
				 			$check_from_date = $this->fund_model->check_int_ex($from_date);
							if(empty($check_from_date))
							{
								$arrcap  = array(
								'user_id'=>$userdata['id'],
								'capital_aum'=>$userdata['capital_aum'] ,
								'capital_aum_interest'=>floor($cap_interest),
								'from_date'=>$from_date,
								'to_date'=>$next_date,
								'created_at'=>date('Y-m-d')
									);
				 			$this->fund_model->save('ci_users_capitalaum_daily_interest',$arrcap);
							}
				 			
							
						}
				 	}


				 }

				
			}

			
		}
	}
	
	
	public function importFile(){
$path = 'uploads/';
require_once APPPATH . "/third_party/PHPExcel.php";
$config['upload_path'] = $path;
$config['allowed_types'] = 'xlsx|xls|csv';
$config['remove_spaces'] = TRUE;
$this->load->library('upload', $config);
$this->upload->initialize($config);            
if (!$this->upload->do_upload('uploadFile')) {
$error = array('error' => $this->upload->display_errors());
} else {
$data = array('upload_data' => $this->upload->data());
}
if(empty($error)){
if (!empty($data['upload_data']['file_name'])) {
$import_xls_file = $data['upload_data']['file_name'];
} else {
$import_xls_file = 0;
}
$inputFileName = $path . $import_xls_file;
try {
$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($inputFileName);
$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
$flag = true;
$i=0;
foreach ($allDataInSheet as $value) {
if($flag){
$flag =false;
continue;
}
//$this->Model_page_faq->gettranstype($value['F'])
$inserdata[$i]['id'] = $value['A'];
$inserdata[$i]['account_no'] = $value['B'];
$inserdata[$i]['username'] = $value['C'];
$inserdata[$i]['firstname'] = $value['D'];
$inserdata[$i]['lastname'] = $value['E'];
$inserdata[$i]['mobile_no'] = $value['G'];
$inserdata[$i]['role'] = $value['P'];
$inserdata[$i]['is_active'] = $value['Q'];
$inserdata[$i]['is_verify'] = $value['R'];
$inserdata[$i]['is_admin'] = $value['S'];
$inserdata[$i]['reference_id'] = $value['W'];
$inserdata[$i]['is_supper'] = $value['AC'];
$inserdata[$i]['type_of_partner'] = $value['AN'];
$inserdata[$i]['is_parent'] = $this->user_model->get_userid($value['W']);
//echo $inserdata[$i]['is_parent'];die();

$inserdata[$i]['created_at'] = date('Y-m-d h:i:s');
$inserdata[$i]['updated_at'] = date('Y-m-d h:i:s');

 


$i++;
}        
//print_r($inserdata);die();      
$result = $this->user_model->insert_va($inserdata);   
if($result){
echo "Imported successfully";
  $success = 'Users updated successfully';
				$this->session->set_flashdata('success',$success);
				redirect(base_url().'admin/users/all_users/0/0');
}else{
echo "ERROR !";
}             
} catch (Exception $e) {
die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
. '": ' .$e->getMessage());
}
}else{
echo $error['error'];
}

}



}


?>