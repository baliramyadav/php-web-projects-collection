<?php 
	$this->load->view('components/page_header');
	$this->load->view('components/page_topbar');
	$this->load->view('components/page_sidebar');

		$this->load->view($subview);

	$this->load->view('components/page_footer');
