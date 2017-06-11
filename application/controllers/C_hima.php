<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_hima extends CI_Controller {

	function __construct(){
		parent::__construct();		
		$this->load->model('Hima_model');
		$this->load->model('Email_model');
		$this->load->model('User_model');

	}
	public function index()
	{
		$data['himalist'] = $this->Hima_model->get_hima()->result();
		$this->load->view('view_hima',$data);
	}
	public function detail_hima($id)
	{	
		$total_data = $this->Hima_model->get_all_count_byhima($id);
        $content_per_page = 4; 
        $this->data['total_data'] = ceil($total_data->tol_records/$content_per_page);
		$this->data['himaid'] = $this->Hima_model->get_himabyid($id)->result();
        $this->load->view('detail_hima', $this->data, FALSE);

		// $data['postingid'] = $this->Hima_model->get_postingbyhima($id)->result();
		// $this->load->view('detail_hima',$data);

	}
	public function load_more($id)
    {
        $group_no = $this->input->post('group_no');
        $content_per_page = 4;
        $start = ceil($group_no * $content_per_page);
        $all_content = $this->Hima_model->get_all_content($id,$start,$content_per_page);
        if(isset($all_content) && is_array($all_content) && count($all_content)) : 
            foreach ($all_content as $key => $content) :
                 echo"
             		<div class='blog-posts'>
             			<article>
							<div class='box-post'>
								<div class='row'>
									"; if(!empty($content->posting_image)){ echo"
									<div class='col-md-12'>
										<div class='post-image postme'>
											<center>
											<img class='img-responsive' src='".base_url()."gambar/".$content->posting_image."'/>
											</center>
										</div>
									</div>
									";}else{

									}echo "
									<div class='col-md-12'>
										<div class='post-content'>
											<h4>
												".$content->posting_title."
											</h4>
											<span class='waktu'>".date('d F Y', strtotime($content->posting_create_at))."</span>
											<p>
												".character_limiter( $content->posting_description,200)."
												<a class='view-more' href='".base_url()."Index/detail_posting/".$content->posting_id."'>
													Selengkapnya <i class='fa fa-angle-right'></i>
												</a>
											</p>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<div class='post-meta'>
											<a href='http://www.facebook.com/sharer.php?u=".base_url()."Index/detail_posting/".$content->posting_id."' target='_blank'>
											<button class='btn btn-facebook'>
												<i class='fa fa-facebook'></i>
											</button>
											</a>
										</div>
									</div>
								</div>
							</div>
						</article>
             		</div>
             		";                 
            endforeach;                                
        endif; 
    }
	public function add_hima() {
		$this->form_validation->set_rules('hima_name','hima name','required|min_length[1]');
		$this->form_validation->set_rules('hima_email','hima email','required|min_length[1]');

		 if($this->form_validation->run()!=false){

		 	$config['upload_path']          = './pdf/';
			$config['allowed_types']        = 'pdf';

			$this->load->library('upload', $config);
			if ($this->upload->do_upload('hima_file')){
		      	$himafile = $this->upload->data();
			}else{
				echo "file tidak ada sesuai";
			}
				$role_id='2';
				 $data = array (
				 	'username' 	  => $this->input->post('hima_email'),
					'password' 	  => md5($this->input->post('hima_password')),
					'role_id' 	  => $role_id
					);
				 $adduserhima = $this->User_model->adduser_db($data);
			if ($adduserhima){

		    $data = array (
		    	'user_id' => $adduserhima,
		        'hima_name' => $this->input->post('hima_name'),
		        'hima_email' => $this->input->post('hima_email'),
		        'universitas_id' => $this->input->post('hima_univ'),
		        'fakultas_id' => $this->input->post('hima_fak'),
		        'hima_file' => $himafile['file_name']);
				$addhima = $this->Hima_model->addhima_db($data);
			}
			$this->session->set_flashdata('notif','Silahkan tunggu 2x24 jam untuk dapatkan konfirmasi');
			//sent email
			$email = $this->input->post('hima_email');
			$subject = 'Selamat Datang';
			$isiemail = '<center>
						<h3>Terima kasih telah mendaftar pada Sipuma</h3>
						<p>Berikut kami kirimkan nama pengguna dan kata sandi</p>
						<table>
							<tr><td>Nama Pengguna : Email</td></tr>
							<tr><td>Kata Sandi : passwird</td></tr>
						</table>
						<p>Simpan Baik-baik nama pengguna dan kata sandi tersebut</p>
						<b>SIPUMA</b>
						</center>';
			$this->Email_model->sendemail($email,$subject,$isiemail);	
			//end sent email
            redirect(base_url('Loginhima'));

		}
		else 
		{
			 echo "gagal";
			  $this->load->view('register');
		}
		
	}
}
