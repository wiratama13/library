<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Catalog;
use App\Models\Publisher;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    $total_anggota = Member::count();
    $total_buku = Book::count();
    $total_peminjam = Transaction::whereMonth('date_start', date('m'))->count();
   

    $data_book = Book::select(DB::raw("COUNT(catalog_id) as total"))->groupBy('catalog_id')
      ->orderBy('catalog_id')->pluck('total');

    $label_book = Catalog::join('books', 'catalogs.id', '=', 'books.catalog_id')
      ->groupBy('name')
      ->orderBy('name', 'asc')
      ->pluck('name');


    $data_donut = Book::select(DB::raw("COUNT(publisher_id) as total"))->groupBy('publisher_id')->orderBy('publisher_id', 'asc')->pluck('total');
    $label_donut = Publisher::join('books', 'books.publisher_id', '=', 'publishers.id')
      ->groupBy('name')
      ->orderBy('name', 'asc')
      ->pluck('name');

    $label_bar = ['Peminjaman', 'Pengembalian'];
    $data_bar = [];

    foreach ($label_bar as $key => $value) {
      $data_bar[$key]['label'] = $label_bar[$key];
      $data_bar[$key]['backgroundColor'] = $key == 0 ? 'rgba(60,141,188,0.9)' : 'rgba(20,260,88,0.9)';
      $data_month = [];

      foreach (range(1, 12) as $month) {
        if ($key == 0) {
          $data_month[] = Transaction::select(DB::raw("COUNT(*) as total"))->whereMonth('date_start', $month)->first()->total;
        } else {
          $data_month[] = Transaction::select(DB::raw("COUNT(*) as total"))->whereMonth('date_end', $month)->first()->total;
        }
      }

      $data_bar[$key]['data'] = $data_month;
    }

    $tes = date("Y-m-d");

    
    return view('home', compact(
      'total_anggota',
      'total_buku',
      'total_peminjam',
      'data_donut',
      'label_donut',
      'data_bar',
      'label_book',
      'data_book',
    
    ));
  }

  public function test_spatie()
  {
    // $role = Role::create(['name' => 'petugas']);
    // $permission = Permission::create(['name' => 'pengurus pinjaman']);

    // $role->givePermissionTo($permission);
    // $permission->assignRole($role);

    // return auth()->user();

  //   $user = User::with('roles')->get();
  //   return $user;
  // }

  $user = auth()->user();
  $user->assignRole('petugas');
  return $user;
  }
  
}