<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*eader('HTTP/1.1 301 Moved Permanently');
header('location: http://schooldname.edu'.$_SERVER['REQUEST_URI']);
*/
class Welcome extends CI_Controller {
	// public $webtitle = "ALI";
	public $Param=array();
	function __construct()
	{
		parent::__construct();
		//$this->load->library('form_validation');
		$this->load->model("ams_model");

		//base64encode_url
		if ( ! function_exists('base64_encode_url'))
		{
			function base64_encode_url($plainText){
				$base64 = base64_encode($plainText);
				$base64url = strtr($base64, '+/=', '-_~');
				return $base64url;
			}
		}

		//base64decode_url
		if ( ! function_exists('base64_decode_url'))
		{
			function base64_decode_url($encoded)
			{
				$base64 = strtr($encoded,'-_~','+/=');
				$plainText = base64_decode($base64);
				return $plainText;
			}
		}

		foreach( $_GET as $key => $value )
		{
			$this->Param[$key] = $this->input->get($key);
		}

		foreach ( $_POST as $key2 => $value2 )
		{
			$this->Param[$key2] = $this->input->post($key2);
		}

		//$this->Param['session_username'] = $this->session->userdata('SEASESS_USERNAME');
		//$this->Param['session_userno'] = $this->session->userdata('SEASESS_USERNO');
		//$this->Param['session_authno'] = $this->session->userdata('SEASESS_AUTHNO');
	}
	public function _remap($method, $params = array())
	{
		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $params);
		}
		show_404();
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function common_layout($data=''){
		if(isset($data)){
			$layout_data['header'] = $this->load->view('common/header','', true);
			$layout_data['navigation'] = $this->load->view('common/navigation',array("navi_seq"=>"home"), true);
			$layout_data['content_body'] = $data['contents'];
			$layout_data['footer'] = $this->load->view('common/footer','', true);
			return $layout_data;
		}
	}
	public function index()
	{
		$contents_data['contents'] = $this->load->view('main',$this->Param,true);
		$layout_data = $this->common_layout($contents_data);
		$this->load->view('layouts/layout_main',$layout_data,false);
	}

	public function alumni()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('inputSender', 'Name', 'required');
		$this->form_validation->set_rules('inputEmail', 'Email', 'required');
		$this->form_validation->set_rules('inputSubject', 'Subject', 'required');
		$this->form_validation->set_rules('inputMessage', 'Message', 'required');

		if ($this->form_validation->run() == TRUE) {

			$message = "<strong>Name</strong> : " . $this->input->post('inputSender') . "<br/>";
			$message .= "<strong>Email</strong> : " . $this->input->post('inputEmail') . "<br/>";
			$message .= "<strong>Subject</strong> : " . $this->input->post('inputSubject') . "<br/>";
			$message .= "<strong>Message</strong> : <ul><li>" . $this->input->post('inputMessage') . "</li></ul>";
			$message = stripslashes(nl2br($message));

			$this->email->from('smtp@schooldname.com', "ALUMNI");
			$this->email->to('info@schooldname.com');
			$this->email->subject("ALUMNI Request by (".$this->input->post('inputSender').")");
			$this->email->message($message);
			$this->email->send();
			$this->session->set_flashdata('sendessage','<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> Your message has been sent successfully.</div>');

		}

	    return $this->index();
	}


	function webmail($mode= '')
	{

		$this->load->library('form_validation');

		$this->form_validation->set_rules('fullname', 'Fullname', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('subject', 'Subject', 'required');

		if ($this->form_validation->run() == TRUE) {

			$message = "<strong>Name</strong> : " . $this->input->post('fullname') . "<br/>";
			$message .= "<strong>City</strong> : " . $this->input->post('city') . "<br/>";
			$message .= "<strong>State</strong> : " . $this->input->post('state') . ", " . $this->input->post('zipcode') . "<br/>";
			$message .= "<strong>Phone (day)</strong> : " . $this->input->post('phoneday') . "<br/>";
			$message .= "<strong>Phone (evening)</strong> : " . $this->input->post('phoneevening') . "<br/>";
			$message .= "<strong>Email</strong> : " . $this->input->post('email') . "<br/>";
			$message .= "<strong>Subject</strong> : " . $this->input->post('subject') . "<br/>";
			$message .= "<strong>How did you hear about us</strong> : <br/>";

			$searchengine = (trim($this->input->post('searchengine'))!="")?"X":"";
			if(trim($searchengine)!=""){
				$message .= "<ul><li>(" . $searchengine . ") Search Engine </li>";
			}

			$referral = (trim($this->input->post('referral'))!="")?"X":"";
			if(trim($referral)!="") {
				$message .= "<li>(" . $referral . ") Referral (By Whom? <strong>" . $this->input->post('whom') . "</strong>) </li>";
			}

			$newskorea = (trim($this->input->post('newskorea'))!="")?"X":"";
			if(trim($newskorea)!="") {
				$message .= "<li>(" . $newskorea . ") NewsKorea </li>";
			}

			$koreanjournal = (trim($this->input->post('koreanjournal'))!="")?"X":"";
			if(trim($koreanjournal)!="") {
				$message .= "<li>(" . $koreanjournal . ") Korean Journal </li>";
			}

			$other = (trim($this->input->post('other'))!="")?"X":"";
			if(trim($other)!="") {
				$message .= "<li>(" . $other . ") Other (<strong>" . $this->input->post('other2') . "</strong>) </li></ul>";
			}
			$message .= "<strong>Message</strong> : <ul><li>" . $this->input->post('description') . "</li></ul>";
			$message = stripslashes(nl2br($message));

			$this->email->from('smtp@schooldname.com', "ALUMI");
			$this->email->to('info@schooldname.com');
			$this->email->subject("WebMail Request from (" . $this->input->post('fullname') . ")");
			$this->email->message($message);
			$this->email->send();
			$this->session->set_flashdata('sendessage','<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> Your message has been sent successfully.</div>');

		}


		return $this->index();

	}

}
