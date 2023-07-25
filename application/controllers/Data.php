<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use GuzzleHttp\Client;

class Data extends CI_Controller {

	function __construct(){
        parent::__construct();
		$client = new \GuzzleHttp\Client();
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
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	// public function index()
	// {
		
    //     $client = new \GuzzleHttp\Client();
    //     $response = $client->request('GET','http://103.226.55.159/json/data_rekrutmen.json',
       
    //     );
    //     $json= $response->getBody();
    //     $json = json_decode($json);
    //         $data=array(
    //             'id'               => $json->id,
    //             'nama'              => $json->nama,
    //             'nip		'     => $json->nip,
    //             'satuan_kerja'   => $json->satuan_kerja,
    //             'posisi_yang_dipilih'             => $json->posisi_yang_dipilih,
    //             'email'             => $json->email,
    //             'jenis_kelamin'     => $json->jenis_kelamin,
    //         );
    //     $json = json_encode($data);
	// 	$data = array(
	// 		'data'  => $json,
    //         // 'wilayah' => $this->Mymodel->getDataUser('2'),
    //         'judul' => 'Data Pendaftar Tim Dev MA',
    //         // 'html'  => 'Data'
    //     );
	// 	$this->load->view('Data',$data);
	}


	function index(){
		$url     ="http://103.226.55.159/json/data_attribut.json";
        $get_url = file_get_contents($url);
        $json    = json_decode($get_url);
        //  var_dump($json);

        $data = array(
            // 'html'     => 'admin/LKPP/LKPP',
            'judul'    => 'Data Pengadaan BKD Provinsi Bengkulu',   
            'data' => $json,  
            
        );
	$this->load->view('Data',$data);
   }

}
