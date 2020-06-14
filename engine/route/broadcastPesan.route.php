<?php

class broadcastPesan extends Route{

    public function index()
    {
        $this -> bind('dasbor/broadcastPesan/broadcastPesan');
    }

    public function prosesBroadcast()
    {
        // 'judulPesan':judulPesan, 'isiPesan':isiPesan
        $judulPesan = $this -> inp('judulPesan');
        $isiPesan = $this -> inp('isiPesan');
        $tipeProses = $this -> inp('tipeProses');
        $waktu = $this -> inp('waktu');
        //buat id pesan
        $idPesan = $this -> rnstr(10);
        //coba buat regex
        $qPelanggan = $this -> state('broadcastPesanData') -> getPelanggan();
        //ambil api key 
        $qApiKey = $this -> state('laundryRoomData') -> getApiKey();
        $apiKey = $qApiKey['value'];
        $status = '';
        if($tipeProses == 'langsung'){
            foreach($qPelanggan as $pel){
                $namaPelanggan = $pel['nama_lengkap'];
                $phone_no = $pel['hp'];
                $message = str_replace("{pelanggan}", $namaPelanggan, $isiPesan);
                $this -> broadcastPesan($message, $phone_no, $apiKey);
                $status = 'sukses';
                $waktu  = $this -> waktu();
            }
        }else{
            $status = 'pending';
        }
       //simpan ke tabel broadcast 
        $this -> state('broadcastPesanData') -> simpanBroadcast($idPesan, $judulPesan, $isiPesan, $tipeProses, $waktu, $status);
        $data['status'] = $qPelanggan;
        $this -> toJson($data);
    }

}