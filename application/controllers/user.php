<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

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

	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');

        $this->load->library('session');
        include(APPPATH . '/third_party/phpseclib/File/X509.php');
        include(APPPATH . '/third_party/phpseclib/Crypt/RSA.php');
        
	}
	public function index()
	{
		$this->load->view('user');
	}

	public function create_ca($namaperusahaan)
	{
		echo $namaperusahaan;
		$privKey = new Crypt_RSA();
		extract($privKey->createKey());
		$privKey->loadKey($privatekey);

		$x509 = new File_X509();
		$x509->setPrivateKey($privKey);
		$x509->setDNProp('id-at-organizationName', '$namaperusahaan');

		$csr = $x509->signCSR();

		echo $x509->saveCSR($csr);
	}
}
