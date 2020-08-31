<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\M_Barang;
use App\M_Estimasi;
use App\M_Teknisi;
use App\M_User;
use App\M_Service;
use App\M_Rating;
use App\M_Kerusakan;
use App\M_Sk;
use App\M_Toko;
use App\M_Produk;
use App\M_Suplai;
use App\M_Pemasukan;
use App\M_Target;

use Carbon\Carbon;
use GuzzleHttp\Client;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class userController extends Controller
{


    public function register(Request $request){
        $data = new M_User();
        $data->username = $request->input('username');
        $data->no_telepon = $request->input('no_telepon');
        $data->foto_ktp = $request->input('foto_ktp');
        $data->selfi_ktp = $request->input('selfi_ktp');
        $data->aktif = 0;
        $data->role_user = 2;
       
        $data->save();

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $data
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 404);
          }
    }

    public function login(Request $request){
        $no_telepon = $request->input('no_telepon');
        $data = M_User::where('no_telepon',$no_telepon)->first();
        
       

        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'data ditemukan',
              'data' => $data
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
              'data' => ''
          ], 404);
        }
    }

    public function tambah_toko(Request $request){
        $data = new M_Toko();
        $data->id_toko = $request->get('id_toko');
        $data->nama_toko = $request->input('nama_toko');
        $data->nama_pemilik = $request->input('nama_pemilik');
        $data->alamat = $request->input('alamat');
        $data->latitude = $request->input('latitude');
        $data->longitude = $request->input('longitude');
        $data->no_hp = $request->input('no_hp');
        $data->foto_toko = $request->input('foto_toko');
        $data->status_toko = 'belum_suplai';
        
        $data->save();

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $data
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 404);
          }
    }

    public function toko_supplied(Request $request){

      $data = M_Toko::where('status_toko','supplied')
      ->orderBy('toko.nama_toko', 'ASC')
      ->get();

      if ($data->count () >0) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $data
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 200);
          }
    }

    public function toko_belum_suplai(Request $request){

      $data = M_Toko::where('status_toko','belum_suplai')
      ->orderBy('toko.nama_toko', 'ASC')
      ->get();

      if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $data
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 200);
          }
    }

    public function suplai(Request $request){

      $date_now = date('Y-m-d');
      $status = $request->input('status');
      $data = new M_Suplai();      
      $data->tanggal_suplai = $date_now;
      $data->status_suplai = "belum_bayar";
      $data->id_produk = (int)$request->input('id_produk');
      $data->id_user = $request->input('id_user');  
      $data->id_toko = $request->input('id_toko');  
      $data->jumlah_suplai = $request->input('jumlah_suplai');  
      $data->save();

      $toko = new M_Toko();
       M_Toko::where('id_toko', $request->input('id_toko'))
      ->update(['status_toko' => 'supplied']);


      $target = new M_Target();
      $target->target_pcs = $request->get('target_pcs');
      $total_target = M_Target::where('id_user', $request->input('id_user'))
      ->where('id_produk', $request->input('id_produk'))
      ->select('target_pcs', 'sisa_target')->first();
      // dd($total_target);
      if ($total_target->sisa_target == null) {
        # code...
        $total = $total_target->target_pcs - $request->input('jumlah_suplai');
      } 
      if ($total_target->sisa_target != null) {
        # code...
        $total = $total_target->sisa_target - $request->input('jumlah_suplai');
      }

      M_Target::where('id_user', $request->input('id_user'))
      ->where('id_produk', $request->input('id_produk'))
      ->update([ 'sisa_target' => $total]);


      // $total = $target->target_pcs - $request->input('target_pcs');
      // M_Target::where('id_target', $request->input('id_target'))
      // ->update([ 'sisa_target' => $total]);
      
      if ($data->count () >0) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $data
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 200);
          }
    }

    public function produk(Request $request){

      $data = M_Produk::get();
     
      if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $data
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 404);
          }
    }

    public function cek_telepon(Request $request){

        $data = M_User::where('no_telepon',$request->input('no_telepon'))
        ->where('aktif', 1 )->first();
        

        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'data ditemukan',
              'data' => $data
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
              'data' => ''
          ], 404);
        }
    }

    public function belum_bayar_suplai($id_user){

//      $data = M_Suplai::where('status_suplai','belum_bayar')->get();
        $data = M_Suplai::join('produk', 'produk.id_produk','=', 'suplai.id_produk')
          ->join('toko', 'toko.id_toko','=', 'suplai.id_toko')
          ->join('user', 'user.id_user','=', 'suplai.id_user')
          ->where('suplai.status_suplai','belum_bayar')
          ->where('suplai.id_user',$id_user)
          ->select( 'toko.nama_toko', 'toko.nama_pemilik', 'suplai.tanggal_suplai', 'suplai.jumlah_suplai',
            'suplai.status_suplai', 'suplai.id_suplai', 'produk.nama_produk', 'produk.harga_dasar')
          ->orderBy('suplai.tanggal_suplai', 'DESC')
          ->get();

        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'data ditemukan',
              'data' => $data
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
              'data' => ''
          ], 404);
        }
    }

    public function suplai_lunas($id_user){
        // $data = M_Target::where('id_user',$id_user)->first();
        $data = M_Suplai::
          select( 'toko.nama_toko', 'toko.nama_pemilik', 'suplai.tanggal_suplai', 'suplai.jumlah_suplai',
            'suplai.status_suplai', 'suplai.id_suplai', 'produk.nama_produk')
          ->join('produk', 'produk.id_produk','=', 'suplai.id_produk')
          ->join('toko', 'toko.id_toko','=', 'suplai.id_toko')
          ->join('user', 'user.id_user','=', 'suplai.id_user')
          ->where('suplai.status_suplai','lunas')
          ->where('suplai.id_user',$id_user)
          ->orderBy('suplai.tanggal_suplai', 'DESC')
          ->get();


        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'data ditemukan',
              'data' => $data
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
              'data' => ''
          ], 404);
        }
    }




    public function pemasukan(Request $request){

      $suplai = M_Suplai::where('id_suplai', $request->input('id_suplai'))
      ->join('user', 'user.id_user', '=', 'suplai.id_user')
      ->first();

      $data = new M_Pemasukan();
      $date_now = date('Y-m-d');
      $data->tanggal = $date_now;
      $data->id_suplai = (int)$request->input('id_suplai');
      $data->jumlah_debit = $request->input('jumlah_debit'); 
      $data->jumlah_pcs = $request->input('jumlah_pcs');  
      $data->return_produk = $request->input('return_produk'); 
      $data->save();


        # code...
        $data_target = M_Target::where('id_user', $suplai->id_user )->first();
        $last_selisih = $data_target->selisih_target;
        
        $data_target->selisih_target = $last_selisih + $request->input('return_produk');
        $data_target->save();
    

      M_Suplai::where('id_suplai', $request->input('id_suplai'))
      ->update(['status_suplai' => 'lunas']);

      // $return = new M_Pemasukan();
      // $return->return_produk = $request->get('return_produk');
      // $total_return = M_Pemasukan::where('id_user', $request->input('id_user'))
      // // ->where('id_produk', $request->input('id_produk'))
      // ->select('return_produk')->first();

      // if ($total_return->return_produk == null) {
      //   # code...
      //   $total = $total_return->return_produk + $request->input('return_produk');
      // } 
      // if ($total_return->return_produk != null) {
      //   # code...
      //   $total = $total_return->return_produk + $request->input('return_produk');
      // }

      // M_Pemasukan::where('id_user', $request->input('id_user'))
      // // ->where('id_produk', $request->input('id_produk'))
      // ->update([ 'return_produk' => $total]);
      
      if ($data->count () >0) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $data
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 200);
          }
    }

    public function profile(Request $request){

      $update = M_User::where('id_user', $request->input('id_user'))->first();

      $update->username = $request->input('username');
      $update->no_telepon = $request->input('no_telepon');
      $update->foto_profil = $request->input('foto_profil');
      $update->save();
      
      if ($update->count () >0) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $update
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 200);
          }
    }

    public function tampil_profil(Request $request){

      $data = M_User::where('id_user', $request->input('id_user'))->first();
     
      if ($data->count () >0) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $data
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 404);
          }
    }

    public function edit_toko(Request $request){

      $update = M_Toko::where('id_toko', $request->input('id_toko'))->first();

      $update->nama_toko = $request->input('nama_toko');
      $update->nama_pemilik = $request->input('nama_pemilik');
      $update->no_hp = $request->input('no_hp');
      $update->foto_toko = $request->input('foto_toko');
      $update->alamat = $request->input('alamat');
      $update->latitude = $request->input('latitude');
      $update->longitude = $request->input('longitude');
      $update->save();

      // M_Toko::where('id_toko', $request->input('id_toko'))
      // ->update(['nama_toko' => 'lunas']);
      
      if ($update->count () >0) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $update
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 200);
          }
    }


    public function target($id_user){

      $data = M_Target::where('id_user',$id_user)->first();
     
      if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $data
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 404);
          }
    }

    public function selisih_target($id_user){

      $data = M_Target::where('id_user',$id_user)->get();

      $selisih = 0; 
      foreach ($data as $selisih_target) {
        # code...
        $selisih += $selisih_target->selisih_target;

      }
     
      if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $selisih
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 404);
          }
    }

    public function data_suplai($id_user, $id_toko){

//      $data = M_Suplai::where('status_suplai','belum_bayar')->get();
        $data = M_Suplai::join('produk', 'produk.id_produk','=', 'suplai.id_produk')
          ->join('toko', 'toko.id_toko','=', 'suplai.id_toko')
          ->join('user', 'user.id_user','=', 'suplai.id_user')
          ->where('suplai.status_suplai','belum_bayar')
          ->where('suplai.id_user',$id_user)
          ->where('suplai.id_toko',$id_toko)
          ->select( 'toko.nama_toko', 'toko.nama_pemilik', 'suplai.tanggal_suplai', 'suplai.jumlah_suplai',
            'suplai.status_suplai', 'suplai.id_suplai', 'produk.nama_produk', 'produk.harga_dasar')
          ->get();

        if ($data) {
          return response()->json([
              'success' => true,
              'message' => 'data ditemukan',
              'data' => $data
          ], 200);
        } else {
          return response()->json([
              'success' => false,
              'message' => 'data tidak ditemukan',
              'data' => ''
          ], 404);
        }
    }

    public function produk_terlaris(Request $request){

     $favorit = M_Pemasukan::leftjoin('suplai','suplai.id_suplai','=','pemasukan.id_suplai')
                ->leftjoin('produk','produk.id_produk','=','suplai.id_produk')
                ->leftjoin('toko','toko.id_toko','=','suplai.id_toko')
                // ->groupBy('suplai.id_toko')
                ->where('suplai.id_user',$request->id_user)
                ->where('suplai.id_toko',$request->id_toko)
                ->where('suplai.status_suplai','lunas')
                ->select('toko.nama_toko','produk.nama_produk','pemasukan.jumlah_pcs')
                // ->sum('jumlah_pcs')
                ->orderBy('toko.nama_toko','DESC')
                ->get();
               
      if ($favorit) {
            return response()->json([
                'success' => true,
                'message' => 'data disimpan',
                'data' => $favorit
            ], 200);
          } else {
            return response()->json([
                'success' => false,
                'message' => 'data tidak disimpan',
                'data' => ''
            ], 200);
          }
    }

    // public function jumlah_penjualan(Request $request){

    //   // Get all the days date of past month for performance report
    // $start_date = date("Y-m", strtotime("now")) ."-01";
    
    // $start_time = date("Y-m-d", strtotime($start_date));     

    // // $end_time_1 = strtotime("+1 week", $start_time);
    // $end_time_1 = date("Y-m-d", strtotime("+1 week ",strtotime($start_time))); 
    

    // for($i=strtotime($start_time); $i<strtotime($end_time_1); $i+=86400)
    // {
    // $list1[] = date('D, d F Y', $i);
    // }




   
    // $end_time_2 = date("Y-m-d", strtotime("+1 week ",strtotime($end_time_1))); 
     

    // for($i=strtotime($end_time_1); $i<strtotime($end_time_2); $i+=86400)
    // {
    // $list2[] = date('D, d F Y', $i);
    // }


    // $end_time_3 = date("Y-m-d", strtotime("+1 week ",strtotime($end_time_2)));
    

    // for($i=strtotime($end_time_2); $i<strtotime($end_time_3); $i+=86400)
    // {
    // $list3[] = date('D, d F Y', $i);
    // }

    // $end_time_4 = date("Y-m-d", strtotime("+1 week ",strtotime($end_time_3)));

    // for($i=strtotime($end_time_3); $i<strtotime($end_time_4); $i+=86400)
    // {
    // $list4[] = date('D, d F Y', $i);
    // }



    // $performance_week_1 = \DB::table('pemasukan')
    // ->join('suplai', 'suplai.id_suplai', '=', 'pemasukan.id_suplai')
    //   ->join('user', 'user.id_user', '=', 'suplai.id_user')
    //   ->where('suplai.id_user', $request->input('id_user'))
    // ->whereBetween('tanggal', [$start_date, $end_time_1]) // get week 1
    // ->get();
    // $total = 0;
    // $total2 = 0;
    // foreach ($performance_week_1 as $key) {
    //   # code...

    //   $total += $key->jumlah_pcs;
    //   $total2 += $key->return_produk;
    // }
   
    // $performance_week_2 = \DB::table('pemasukan')
    // ->join('suplai', 'suplai.id_suplai', '=', 'pemasukan.id_suplai')
    //   ->join('user', 'user.id_user', '=', 'suplai.id_user')
    //   ->where('suplai.id_user', $request->input('id_user'))
    // ->whereBetween('tanggal', [$end_time_1, $end_time_2]) // get week 2
    // ->get();
    // $total3 = 0;
    // $total4 = 0;
    // foreach ($performance_week_1 as $key) {
    //   # code...

    //   $total3 += $key->jumlah_pcs;
    //   $total4 += $key->return_produk;
    // }
     
    // $performance_week_3 = \DB::table('pemasukan')
    // ->join('suplai', 'suplai.id_suplai', '=', 'pemasukan.id_suplai')
    //   ->join('user', 'user.id_user', '=', 'suplai.id_user')
    //   ->where('suplai.id_user', $request->input('id_user'))
    // ->whereBetween('tanggal', [$end_time_2, $end_time_3]) // get week 3
    // ->get();
    // $total5 = 0;
    // $total6 = 0;
    // foreach ($performance_week_1 as $key) {
    //   # code...

    //   $total5 += $key->jumlah_pcs;
    //   $total6 += $key->return_produk;
    // }

    // $performance_week_4 = \DB::table('pemasukan')
    // ->join('suplai', 'suplai.id_suplai', '=', 'pemasukan.id_suplai')
    //   ->join('user', 'user.id_user', '=', 'suplai.id_user')
    //   ->where('suplai.id_user', $request->input('id_user'))
    // ->whereBetween('tanggal', [$end_time_3, $end_time_4]) // get week 4
    // ->get();
    // $total7 = 0;
    // $total8 = 0;
    // foreach ($performance_week_1 as $key) {
    //   # code...

    //   $total7 += $key->jumlah_pcs;
    //   $total8 += $key->return_produk;
    // }
    
    // $data = [

    //   $total,$total2,$total3,$total4,$total5,$total6,$total7,$total8

    // ];

      
    //   if ($data) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'data disimpan',
    //             'data' => $data
    //         ], 200);
    //       } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'data tidak disimpan',
    //             'data' => ''
    //         ], 200);
    //       }
    // }


}
