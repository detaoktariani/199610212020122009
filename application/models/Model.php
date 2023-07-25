<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model extends CI_Model {
 function __construct(){
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');

$this->db->query('SET SESSION sql_mode =
                  REPLACE(REPLACE(REPLACE(
                  @@sql_mode,
                  "ONLY_FULL_GROUP_BY,", ""),
                  ",ONLY_FULL_GROUP_BY", ""),
                  "ONLY_FULL_GROUP_BY", "")');
        parent::__construct();
    }


public function security(){
  $level = $this->session->userdata('level');
  if($level!='2'){
      $this->session->sess_destroy();
      redirect('');
  }
}

function tambah_asn($data){
    $this->db->set('uid', 'UUID()', FALSE);
  $this->db->insert('datacerai', $data);
  return TRUE;
}

public function cekasn($nik){
  // $now = date('Y-m-d');
  $this->db->select('*');
  $this->db->from('datacerai');
  $this->db->where('nik_suami',$nik);
  $query = $this->db->get();
  return $query->result_array();
}

public function cekasnis($nik){
  // $now = date('Y-m-d');
  $this->db->select('*');
  $this->db->from('datacerai');
  $this->db->where('nik_istri',$nik);
  $query = $this->db->get();
  return $query->result_array();
}

function tambah_instansi($data){
  $this->db->insert('user', $data);
  return TRUE;
}

function data($nama){
  $this->db->select('user., datacerai.');
  $this->db->join('datacerai','datacerai.id_pa=user.id','left');
 $this->db->from('user');
 $this->db->where('user.instansi',$nama);
 $query = $this->db->get();
 return $query->result_array();
}

function putusan1($PA){
//   $this->db->select('datacerai.*');
//  $this->db->from('datacerai');
//  $this->db->where('id_pa',$PA);
//  $this->db->where('putusan', "");
//  $this->db->order_by('datacerai.id', 'DESC');
//  $this->db->limit(5);
//  $query = $this->db->get();
//  return $query->result_array();
 $this->db->select('datacerai.*');
  $this->db->join('user','datacerai.id_pa=user.id_instansi','left');
 $this->db->from('datacerai');
 $this->db->where('username',$PA);
 $this->db->where('putusan', "");
 $this->db->order_by('datacerai.id', 'DESC');
 $this->db->limit(5);
 $query = $this->db->get();
 return $query->result_array();
}
function tbputusan($PA){
  $this->db->select('datacerai.*');
  $this->db->join('user','datacerai.id_pa=user.id_instansi','left');
 $this->db->from('datacerai');
 $this->db->where('username',$PA);
 $this->db->where('putusan !=', "");
 $this->db->where('status_perkara', "Putus");
 $query = $this->db->get();
 return $query->result_array();
}

function tambah_putusan(){

    $tgl_input =date("Y-m-d");
    $this->db->set('uid', 'UUID()', FALSE);
      $data=array(
          'id_pa'        => $this->session->userdata('id_instansi'),
          'nama_istri'        => $this->input->post('nama_istri'),
          'nik_istri'        => $this->input->post('nik_istri'),
          'nohp_istri'        => $this->input->post('nohp_istri'),
          'nama_suami'        => $this->input->post('nama_suami'),
          'nik_suami'        => $this->input->post('nik_suami'),
          'nip'        => $this->input->post('nip'),
          'instansi'        => $this->input->post('instansi'),
          'nohp_suami'        => $this->input->post('nohp_suami'),
          'email_suami'        => $this->input->post('email_suami'),
          'jk1'        => $this->input->post('jk1'),
          'tgl_input'       => $tgl_input
          );
       

  $this->db->insert('datacerai', $data);
  return TRUE;
  // }
}

function hapus_putusan($id_putusan){
  $this->db->delete('datacerai', array('id' => $id_putusan));
  return true;
}

function edit_putusan($id_putusan){
  $this->db->select('*');
 $this->db->from('datacerai');
 $this->db->where('uid',$id_putusan);
 $query = $this->db->get();
 return $query->result_array();
}

function edit_option(){
 $this->db->select('user.id_instansi, user.id, user.instansi');
 $this->db->from('user');
 $this->db->where('level',"3");
 $this->db->or_where('level',"2");
 $this->db->or_where('id_instansi',"1");
 $this->db->group_by('id_instansi'); 
 $this->db->order_by('id_instansi','asc');
 $query = $this->db->get();
 return $query->result_array();
}

function edit_anak($id_putusan){
  $this->db->select('*');
 $this->db->from('dataanak');
 $this->db->join('datacerai','datacerai.id=dataanak.id_cerai','left');
 $this->db->where('uid',$id_putusan);
 $query = $this->db->get();
 return $query->result_array();
}

function emails($idinstansi){
  $id = $this->input->post('ids');
  // echo "ids" . $id;
  // echo "idinstansi" . $idinstansi;
  $this->db->select('datacerai.id, datacerai.nama_suami, datacerai.nip, datacerai.nama_istri, datacerai.norek_istri, datacerai.nik_suami,datacerai.cerai,datacerai.no_putusan,user.email,user.instansi');
  $this->db->join('user','datacerai.instansi=user.id_instansi','left');
  $this->db->from('datacerai');
  $this->db->where('user.id_instansi',$idinstansi);
  $this->db->where('datacerai.id=', $id);

  $query = $this->db->get();
  return $query->result_array();
}

function nohpbbkl($idn){  
  $this->db->select('*');
  $this->db->from('user');
  $this->db->where('id_instansi',$idn);
  $this->db->where('level','5');
  $query = $this->db->get();
  return $query->result_array();
  }

  function noPA($idn){  
    $this->db->select('*');
    $this->db->from('user');
    $this->db->where('id_instansi',$idn);
    $this->db->where('level','2');
    $query = $this->db->get();
    return $query->result_array();
    }

function emailinput($idinstansi){  
  $this->db->select('*');
  $this->db->from('user');
  $this->db->where('user.id',$idinstansi);
  $query = $this->db->get();
  return $query->result_array();
  }

  function update_putusanTL(){
    $id = $this->input->post('id1');
    // echo "idm=" . $id;
    $config['upload_path']          = 'assets/images/imgputusan';
    $config['allowed_types']        = 'doc|docx|jpg|jpeg|png|pdf';
    $config['max_size']             = 50000000;
    $config['max_width']            = 50000000;
    $config['max_height']           = 50000000;
    $config['encrypt_name']         = TRUE;
  
  
    $this->load->library('upload', $config);
  
    $namafile = array();
    if ($_FILES['file1']['size'] != 0 ){
      if ($this->input->post('photo1') != "") {
        unlink('assets/images/imgputusan/'.$this->input->post('photo1'));  /*hapus data lama */
      }
      $file1 = $this->upload->do_upload('file1');
      $data1= $this->upload->data();
      $file1= $this->upload->data('file_name');
    }else {
      $file1 = $this->input->post('photo1');
    }
        $data=array(
            'putusan'     => $file1,
            'status_perkara' => $this->input->post('status_perkara'),
            );
            $this->db->set($data);
            $this->db->update('datacerai', $this, array('id' => $id));
    return TRUE;
  }
  

function update_putusan(){
  $id = $this->input->post('ids');
  $config['upload_path']          = 'assets/images/imgputusan';
  $config['allowed_types']        = 'jpg|jpeg|png|pdf';
  $config['max_size']             = 50000000;
  $config['max_width']            = 50000000;
  $config['max_height']           = 50000000;
  $config['encrypt_name']         = TRUE;


  $this->load->library('upload', $config);

  $namafile = array();
  if ($_FILES['file1']['size'] != 0 ){
    if ($this->input->post('photo1') != "") {
      unlink('assets/images/imgputusan/'.$this->input->post('photo1'));  /*hapus data lama */
    }
    $file1 = $this->upload->do_upload('file1');
    $data1= $this->upload->data();
    $file1= $this->upload->data('file_name');
  }else {
    $file1 = $this->input->post('photo1');
  }

  if ($_FILES['file2']['size'] != 0 ){
    if ($this->input->post('photo2') != "") {
      unlink('assets/images/imgputusan/'.$this->input->post('photo2'));  /*hapus data lama */
    }
    $file2 = $this->upload->do_upload('file2');
    $data2= $this->upload->data();
    $file2= $this->upload->data('file_name');
  }else {
    $file2 = $this->input->post('photo2');
  }

  if ($_FILES['file3']['size'] != 0 ){
    if ($this->input->post('photo3') != "") {
      unlink('assets/images/imgputusan/'.$this->input->post('photo3'));  /*hapus data lama */
    }
    $file3 = $this->upload->do_upload('file3');
    $data3= $this->upload->data();
    $file3= $this->upload->data('file_name');
  }else {
    $file3 = $this->input->post('photo3');
  }

  if ($_FILES['file4']['size'] != 0 ){
    if ($this->input->post('photo4') != "") {
      unlink('assets/images/imgputusan/'.$this->input->post('photo4'));  /*hapus data lama */
    }
    $file4 = $this->upload->do_upload('file4');
    $data4= $this->upload->data();
    $file4= $this->upload->data('file_name');
  }else {
    $file4 = $this->input->post('photo4');
  }
      $data=array(
          // 'id_pa'        => $this->session->userdata('id'),
          'nip'        => $this->input->post('nip'),
          'nik_suami'        => $this->input->post('nik_suami'),
          'ktp_suami'     => $file3,
          'nama_suami'        => $this->input->post('nama_suami'),
          'instansi'        => $this->input->post('instansi'),
          'email_suami'        => $this->input->post('email_suami'),
          'nohp_suami'        => $this->input->post('nohp_suami'),
          'nik_istri'        => $this->input->post('nik_istri'),
          'ktp_istri'     => $file4,
          'nama_istri'        => $this->input->post('nama_istri'),
          'nohp_istri'        => $this->input->post('nohp_istri'),
          'norek_istri'        => $this->input->post('norek_istri'),
          'cerai'        => $this->input->post('cerai'),     
          'no_putusan'        => $this->input->post('no_putusan'),
          'tgl_putusan'        => $this->input->post('tgl_putusan'),
          'putusan'     => $file1,
          'akta_cerai'     => $file2,
          'tgl_mulai'        => $this->input->post('tgl_mulai'),
          'biaya_istri'        => preg_replace('/[Rp. ]/','',$this->input->post('biaya_istri')),
          // 'tgl_selesai_anak'        => $tgl_selesai_anak,
          'biaya_anak'        => preg_replace('/[Rp. ]/','',$this->input->post('biaya_anak')),
          'status_perkara' => $this->input->post('status_perkara'),
          // 'tgl_input'        => $this->input->post('tgl_input'),
          );
        //   echo "data=". $this->input->post('instansi') ;
          $this->db->set($data);
          $this->db->update('datacerai', $this, array('id' => $id));

  return TRUE;
}

function updateanak($id_cerai){
  // DB ANAK
          $nama_anak = $this->input->post('nama_anak');
          $total = count($nama_anak);
          // echo "total=" . $total;
          // //
          $tahun = $this->input->post('tahun');
          $bulan = $this->input->post('bulan');
          
          $tgl_akta = $this->input->post('tgl_mulai');
          $data1 = array();
          
            for($i=0; $i<$total; $i++){ // Kita buat perulangan berdasarkan nis sampai data terakhir
              $hbln[$i] = (int) $tahun[$i] * 12;
            $hbln[$i] = (int) $hbln[$i] + (int) $bulan[$i];
            $hbln1[$i] = 252 - (int) $hbln[$i];
          
            $htot[$i]= (int) $hbln1[$i];
            $htot2[$i] = '+'.$htot[$i].'month';
        
            
            
            $tgl_selesai_anak[$i] = date('Y-m-d', strtotime($htot2[$i], strtotime( $tgl_akta )));

         
              $data1[$i]=array(
                  'nama_anak'        => $nama_anak[$i],
                  'tahun'        => $tahun[$i],
                  'bulan'        => $bulan[$i],
                  'tgl_selesai_anak'        => $tgl_selesai_anak[$i],
                  );

              $this->db->set($data1[$i]);
              $this->db->where('id_cerai', $id_cerai[$i])->update('dataanak', $this);
       
              
          }
         
          return TRUE;
}


function grafik(){
  $this->db->where("YEAR(tgl_putusan) >= ", date('Y-m-d', strtotime('-1 YEAR')));
  $this->db->where('putusan !=', "");
  $this->db->where('status_perkara', "Putus");
  // $this->db->where('instansi NOT IN (1,2,7,9,10,11,12,13,14)');
  $this->db->where('length(instansi) > ', 2 );
  // INTANSI DG ID 8= PA CURUP GA BISA DIMASUKIN, ERROR GATAU ANEH
  $data = $this->db->get('datacerai');
  return $data;
}

function grafikPA($PA){
  $this->db->where("YEAR(tgl_putusan) >= ", date('Y-m-d', strtotime('-1 YEAR')));
  $this->db->where('putusan !=', "");
  $this->db->where('status_perkara', "Putus");
  $this->db->where('length(instansi) <= ', 2 );
  $this->db->where('id_pa ', $PA );
   // INTANSI DG ID 8= PA CURUP GA BISA DIMASUKIN, ERROR GATAU ANEH
  $data = $this->db->get('datacerai');
  return $data;
}


function tbbelumTL($PA){
  $this->db->select('datacerai.*');
  $this->db->join('user','datacerai.id_pa=user.id_instansi','left');
 $this->db->from('datacerai');
 $this->db->where('username',$PA);
 $this->db->where('putusan', "");
 $query = $this->db->get();
 return $query->result_array();
}

function tbtidakTL($PA){
  $this->db->select('datacerai.*');
  $this->db->join('user','datacerai.id_pa=user.id_instansi','left');
 $this->db->from('datacerai');
 $this->db->where('username',$PA);
 $this->db->where('putusan !=', "");
 $this->db->where('status_perkara !=', "Putus");
 $query = $this->db->get();
 return $query->result_array();
}

function tambah_anak($id,$tgl_mulai){

  $nama_anak = $this->input->post('nama_anak1');
          $total = count($nama_anak)-1;
          $tahun = $this->input->post('tahun1');
          $bulan = $this->input->post('bulan1');
          $tgl_akta = $this->input->post('tgl_mulai');
          $data = array();

  for($i=0; $i<$total; $i++){ // Kita buat perulangan berdasarkan nis sampai data terakhir
  
            $hblnp[$i] =  $tahun[$i] * 12;
            $hbln[$i] = $hblnp[$i] +  $bulan[$i];
            $hbln1[$i] = 252 -  $hbln[$i];
          
            $htot[$i]=  $hbln1[$i];
            $htot2[$i] = '+'.$htot[$i].'month';         
        
            
            
            $tgl_selesai_anak[$i] = date('Y-m-d', strtotime($htot2[$i], strtotime( $tgl_akta )));
         
              $data[$i]=array(
                  'id_cerai'        => $id,
                  'nama_anak'        => $nama_anak[$i],
                  'tahun'        => $tahun[$i],
                  'bulan'        => $bulan[$i],
                  'tgl_selesai_anak'        => $tgl_selesai_anak[$i],
                  );

              $this->db->insert('dataanak', $data[$i]); 
              
          }
return TRUE;
}

function prosestidakTL($instansi,$status_perkara,$PA)
{

  $stat1= "Putus";
  $stat2= "";
  $SQL = 'SELECT
  id,
  nik_suami,
  nip,
  nama_suami,
  instansi,
  putusan,
  status_perkara
FROM
  datacerai
WHERE datacerai.status_perkara !="'. $stat1 . '"
 AND datacerai.status_perkara != "' . $stat2 .'"
 AND datacerai.id_pa = "' . $PA .'"' ;
  $nl= '';
  if ($instansi != '' && $status_perkara == '' ) {
    $SQL = 'SELECT
                id,
                nik_suami,
                nip,
                nama_suami,
                instansi,
                putusan,
                status_perkara
            FROM
                datacerai
            WHERE
              datacerai.instansi = "' . $instansi . '"
            AND datacerai.id_pa = "' . $PA . '"
            AND datacerai.putusan != "' . $nl .'"' ;
  }
  if ($status_perkara != '' && $instansi == '') {
    $SQL = 'SELECT
                id,
                nik_suami,
                nip,
                nama_suami,
                instansi,
                putusan,
                status_perkara
            FROM
                datacerai
            WHERE
              datacerai.status_perkara = "' . $status_perkara . '"
            AND datacerai.id_pa = "' . $PA . '"
            AND datacerai.putusan != "' . $nl .'"' ;
  }
  if ($instansi != '' && $status_perkara != '') {
    $SQL = 'SELECT
                id,
                nik_suami,
                nip,
                nama_suami,
                instansi,
                putusan,
                status_perkara
            FROM
                datacerai
            WHERE
              datacerai.status_perkara = "' . $status_perkara . '"
             AND datacerai.instansi = "' . $instansi . '"
            AND datacerai.id_pa = "' . $PA . '"
            AND datacerai.putusan != "' . $nl .'"' ;
  }
  $query = $this->db->query($SQL);
  return $query->result_array();
}

function tbpengaduan($PA){
  $this->db->select('*, datapengaduan.id_pa as id_pa, datapengaduan.id as id');
  $this->db->join('user','datapengaduan.id_pa=user.id','left');
 $this->db->from('datapengaduan');
 $this->db->where('username',$PA);
 $this->db->where('masalah =','');
 $query = $this->db->get();
 return $query->result_array();
}

function insertpengaduan($id){
  
 $this->db->select('uid, id_pa, nama_istri, nik_istri, nama_suami, nik_suami, no_putusan, nohp_istri, instansi, nip');
 $this->db->from('datapengaduan');
 $this->db->where('uid',$id);
 $query = $this->db->get();
 $ambil= $query->result_array();

 
 $data=array(
  'uid'         => $ambil[0]['uid'],
  'id_pa'         => $ambil[0]['id_pa'],
  'nik_suami'        => $ambil[0]['nik_suami'],
  'nama_suami'        => $ambil[0]['nama_suami'],
  'nik_istri'        =>  $ambil[0]['nik_istri'],
  'nama_istri'        => $ambil[0]['nama_istri'],
  'nohp_istri'        => $ambil[0]['nohp_istri'],
  'no_putusan'        =>  $ambil[0]['no_putusan'],
  'instansi'        =>  $ambil[0]['instansi'],
  'nip'        =>  $ambil[0]['nip'],
  'jk1'        =>  "L",
  );
  
 $this->db->select('*');
 $this->db->from('datacerai');
 $this->db->where('nik_suami',$data["nik_suami"]);
 $this->db->or_where('nik_istri',$data["nik_istri"]);

 $query = $this->db->get();
 $ambilcerai= $query->result_array();
 if (count($ambilcerai)<1){
  $this->db->insert('datacerai', $data);
  $dataP=array(
    'status_pendaftaran'         => "diterima",
    );
    $this->db->set($dataP);
    $this->db->where('uid',$id)->update('datapengaduan', $this);
  return TRUE;
 } else {
  
  $dataP=array(
    'status_pendaftaran'         => "data telah ada di database",
    );
    $this->db->set($dataP);
    $this->db->where('uid',$id)->update('datapengaduan', $this);
  return TRUE;
 }

}

function tolakpengaduan($id){ 
  $dataP=array(
    'status_pendaftaran'         => "ditolak",
  );
  $this->db->set($dataP);
  $this->db->where('id',$id)->update('datapengaduan', $this);
  return TRUE;
 }

 public function instansiPA(){
  // $now = date('Y-m-d');
  $this->db->select('*');
  $this->db->from('user');
  $this->db->where('level','2');
  $this->db->or_where('id_instansi','1');
  $query = $this->db->get();
  return $query->result_array();
}

function tabeldataBKD($PA){
  $this->db->select('datacerai.*');
  $this->db->join('user','datacerai.id_pa=user.id','left');
 $this->db->from('datacerai');
 $this->db->where('id_pa',"0");
 $this->db->where('putusan', "");
 $query = $this->db->get();
 return $query->result_array();
}

function updateIdPABKD($id){
      $data=array(
          'id_pa' => $this->session->userdata('id_instansi'),
          );
          $this->db->set($data);
          $this->db->update('datacerai', $this, array('uid' => $id));
  return TRUE;
}

///DUKCAPIL
function datadukcapil(){
  $this->db->select('datacerai.*');
 $this->db->from('datacerai');
 $this->db->where('putusan !=', "");
 $query = $this->db->get();
 return $query->result_array();
}

function getInstansi($idinstansi,$level)
  {
    $this->db->select('*');
    $this->db->from('user');
    $this->db->where('id_instansi',$idinstansi);
    $this->db->where('level',$level);
    $query = $this->db->get();
    return $query->result_array();
  }

  function getPengaduan($uid)
  {
    $this->db->select('*');
    $this->db->from('datapengaduan');
    $this->db->where('uid',$uid);
    $query = $this->db->get();
    return $query->result_array();
  }

  function getTPengaduan($uid)
  {
    $this->db->select('*');
    $this->db->from('datapengaduan');
    $this->db->where('id',$uid);
    $query = $this->db->get();
    return $query->result_array();
  }


}