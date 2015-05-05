<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Root extends CI_Controller {

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
		// create private key for CA cert
		$CAPrivKey = new Crypt_RSA();
		extract($CAPrivKey->createKey());
		$CAPrivKey->loadKey($privatekey);

		$pubKey = new Crypt_RSA();
		$pubKey->loadKey($publickey);
		$pubKey->setPublicKey();

		echo "the private key for the CA cert (can be discarded):\r\n\r\n";
		echo $privatekey;
		echo "\r\n\r\n";


		// create a self-signed cert that'll serve as the CA
		$subject = new File_X509();
		$subject->setDNProp('id-at-organizationName', 'teratai putih');
		$subject->setPublicKey($pubKey);

		$issuer = new File_X509();
		$issuer->setPrivateKey($CAPrivKey);
		$issuer->setDN($CASubject = $subject->getDN());

		$x509 = new File_X509();
		$x509->makeCA();
		$x509->setStartDate('-1 month');
		$x509->setEndDate('+1 year');
		$result = $x509->sign($issuer, $subject);
		echo $x509->saveX509($result);
	}	
}
