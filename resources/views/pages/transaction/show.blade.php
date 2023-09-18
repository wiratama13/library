@extends('layouts.template')

@section('title', 'Show Transaction page')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y ">
    <h4>Show Peminjaman</h4>
  
  <table class="table table-borderless w-50 ms-auto me-auto mt-3 card p-5 fw-normal">
  
    <tr class="text-capitalize ">
      <td>Detail Peminjaman</td>
    </tr>
    <tr class="text-capitalize">
      <td>Anggota</td>
      <td >{{ $transactions->member->name }}</td>
    </tr>
    <tr class="text-capitalize">
     <td >Tanggal Pinjam</td>
     <td >{{ $transactions->date_start}}</td>
   </tr>
    <tr class="text-capitalize" >
      <td>Buku</td>
      <td>
        <div class="border border-secondary p-2">
            @foreach ($transactions->details as $item)
             
         
          <div class="">
            {{$item->book->title}}
          </div>
        
         @endforeach
        </div>
        
      </td>
    </tr>
   
     <tr class="text-capitalize">
      <td >Status</td>
      <td >
          @if ($transactions->status == 1)
            Belum dikembalikan
          @else
            Sudah dikembalikan
          @endif    
      </td>
    </tr>

    <tr class="d-flex justify-content-end mt-3">
      
      <td>
      <div class="ms-auto">
        <a href="{{ route('transactions.index') }}">
          <button class="btn btn-secondary">Tutup</button>
        </a>
      </div>
      </td>
    </tr>
 
  </table>
  
</div>

@endsection

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    .table {
      
    }
  </style>
@endpush
@push('script')
   <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script>
        $(document).ready(function() {
            // Select2 Multiple
            $('.select2-multiple').select2({
                placeholder: "Select",
               
            });

        });

    </script>
@endpush