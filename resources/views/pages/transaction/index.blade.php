@extends('layouts.template')

@section('title', 'Transaction page')

@section('content')

{{-- @role('petugas') --}}
@can('pengurus pinjaman')
<div id="controlData">
  <div class="container-xxl flex-grow-1 container-p-y">

            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Basic Tables</h4>

            <h4>Publisher Table</h4>
            <!-- Basic Bootstrap Table -->
            <div class="card">
              <h5 class="card-header">Table Basic</h5>
              
                <!-- Default Modal -->

                <div class="d-flex flex-row">
                      <!-- Button trigger modal -->
                    <div class="col-lg-3 col-md-3">
                      <a href="{{ route('transactions.create') }}" >
                      <button

                       type="button"
                        class="btn btn-primary ms-3"
                       >
                        Add Transaction
                      </button>
                      </a>
                    </div>
                   
                      <div class="ms-auto me-3 mb-3" style="width: 15%">
                         <select name="status" class="form-control filter-select" id="">
                        <option value="0">Pilih Status</option>
                        <option value="1">Belum dikembalikan</option>
                        <option value="2">sudah dikembalikan</option>
                      </select>
                      </div>
                       
                       <div class="me-3"  style="width: 15%">
                        <select name="date_start" class="form-control filter-select" id="">
                        <option value="0">Pilih Tanggal</option>
                        @foreach ($transactions as $transaction)
                        <option value="{{ $transaction->date_start }}">{{ convert_date($transaction->date_start) }}</option>
                
                        @endforeach
                       
                      </select>
                       </div>
                       
                 
                  
                 </div>
                 
                
             
              <div class="container">
                <table class="table table-responsive yajra-datatable"  id="datatable">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tanggal Pinjam</th>
                      <th>Tanggal Kembali</th>
                      <th>Nama Peminjam</th>
                      <th>Lama Pinjam(hari)</th>
                      <th>Total Buku</th>
                      <th>Total Bayar</th>
                      <th>Status</th>
                      <th>Actions</th>
                     
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">

                 
                  
                  </tbody>
                </table>
              </div>
            </div>
            <!--/ Basic Bootstrap Table -->

            <hr class="my-5" />

          

</div>
</div>
  
@endcan
  

@endsection

@push('styles')
<style>
    .save-btn{
    position: relative; 
    margin-top:80px
  }
  .save-btn-position {
    position: absolute; right: 0;bottom:1px
  }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
@endpush

@push('script')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script>
    var apiUrl = '{{ url('api/transactions') }}'
    var actionUrl = '{{ url('transactions') }}'
  
        var columns = [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'date_start', name: 'date_start'},
            {data: 'date_end', name: 'date_end'},
            {data: 'member.name', name: 'member.name'},
            {data: 'days', name: 'days'},
            {data: 'amount', name: 'amount'},
            {render: function (data){
              let number = data;
              let sum = 0
              
              number.forEach(num => {
                sum += num
              });

              return sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }, data: 'details[].book.price', name: 'details[].book.price'},
            {data: 'status_tr', name: 'status_tr'},
            {
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true
            },
        ]
    // });
    
  // });

  const { createApp } = Vue
      
  var App = createApp({
    data() {
      return {
        datas: [],
        data: {},
        anggota: {},        
        actionUrl,
        apiUrl,
        editStatus : false
      }
    },
    mounted: function() {
      this.datatable()
    },
    methods : {
      datatable() {
        const _this = this;
        _this.table = $(`#datatable`).DataTable({
          ajax: {
            url: _this.apiUrl,
            type: 'GET'
          },
          columns
        }).on('xhr', function(){
          _this.datas = _this.table.ajax.json().data
        })
      },
    
    
      }
  }).mount('#controlData')

</script>    
<script>
    $('select[name=status], select[name=date_start]').on('change', function() {
    status = $('select[name=status]').val()
    date_start = $('select[name=date_start]').val()

    App.table.ajax.url(actionUrl + '?status=' + status + '&date_start='+ date_start).load()
  })

</script>
@endpush