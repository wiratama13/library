@extends('layouts.template')

@section('title', 'Transaction page')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <h4>Tambah Peminjaman</h4>
  <form action="{{ route('transactions.store') }}" method="POST">
    @csrf

  <table class="table table-borderless w-50 ms-auto me-auto mt-3 card p-3 fw-normal">
  
    <tr class="text-capitalize ">
      <td colspan="5">Tambah Peminjaman</td>
    </tr>
    <tr class="text-capitalize">
      <td>Anggota</td>
      <td >
        <select name="member_id" id="" class="form-control" >
          @foreach ($members as $member)
            <option value="{{ $member->id }}">{{ $member->name }}</option>
          @endforeach
        </select>
      </td>
    </tr>
    <tr class="text-capitalize">
     <td >Tanggal Pinjam</td>
     <td class="d-flex flex-row"><input
            type="date"
            class="form-control"
            id="date_start"
            placeholder="ketikkan nomor hp publisher"
            name="date_start"
        />
        <p class="ms-2 me-2 fw-bold">-</p>
       <input
            type="date"
            class="form-control"
            id="date_end"
            placeholder="ketikkan nomor hp publisher"
            name="date_end"></td>
   </tr>

   
    <tr class="text-capitalize" >
      <td>Buku</td>
      <td>
        <select name="book_id[]" multiple="multiple" id="" class="select2-multiple form-control" id="select2Multiple">
          @foreach ($books as $book)
            <option value="{{ $book->id }}">{{ $book->title }}</option>
          @endforeach
        </select>
        
      </td>
    </tr>
   
    

    <tr class="d-flex justify-content-end mt-3">
      
      <td>
      <div class="ms-auto">
         <div class="d-grid">
          <button type="submit" name="submit" class="btn btn-primary mt-4">Create</button>
       </div>
      </div>
      </td>
    </tr>

    
    
  </table>
  
  </form>
</div>
@endsection

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('script')
   <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script>
        $(document).ready(function() {
            // Select2 Multiple
            $('.select2-multiple').select2({
                placeholder: "Select",
                allowClear: true
            });

        });

    </script>
@endpush