@extends('layouts.template')

@section('title', 'dashboard page')

@section('content')         
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mt-4 justify-content-center mb-5">
      <div class="col-md-3 col-sm-6 col-xs-12" >
        <div class="card shadow border-3 border-primary border-end-0 border-top-0  border-bottom-0 position-relative" style="width: 16rem;">
          <div class="card-body">
            <h5 class="card-title">Total anggota</h5>
            <p class="card-text" >{{ $total_anggota }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12" >
        <div class="card shadow border-3 border-warning border-end-0 border-top-0  border-bottom-0 card-item position-relative" style="width: 16rem;">
          <div class="card-body">
            <h5 class="card-title">Total Buku</h5>
            <p class="card-text" >{{ $total_buku }}</p>
          </div>
        </div>
      </div>
   
      <div class="col-md-3 col-sm-6 col-xs-12" >
        <div class="card shadow border-3 border-danger border-end-0 border-top-0  border-bottom-0 card-item position-relative" style="width: 16rem;">
          <div class="card-body">
            <h5 class="card-title">Total peminjam</h5>
            <p class="card-text" >{{ $total_peminjam }}</p>
          </div>
        </div>
      </div>

      
    </div>

     <div class="row d-flex justify-content-center">
       <div class="col-md-5 shadow-sm bg-white position-relative">
        <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>

        
         <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 270px; max-width: 100%;"></canvas>
      </div>
       <div class="col-md-7">
        
        <!-- DONUT CHART -->
            <div class="card card-danger">
                <h5 class="card-header m-0 me-2 pb-3">Grafik Katalog</h5>              
              <div class="card-body">
                <canvas id="bookGraphic" style="min-height: 250px; height: 250px; max-height: 270px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
        
      </div>
     </div>
      
       
       
     </div>
  </div>
    
@endsection

@push('styles')
  <style>
    .card-item-blue {
      border-left: 2px solid #0d6efd ;
    }
  </style>
@endpush
@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>

 
  var data_bar = '{!! json_encode($data_bar) !!}'
  var label_book = '{!! json_encode($label_book) !!}'
  var data_book = '{!! json_encode($data_book) !!}'

  const Bar = document.getElementById('barChart');

  new Chart(Bar, {
    type: 'bar',
    data: {
      labels: ['January', 'Ferruary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November','December'],
      datasets: JSON.parse(data_bar)
      },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  const dataBook = document.getElementById('bookGraphic');

  new Chart(dataBook, {
    type: 'polarArea',

    data: {
      labels: JSON.parse(label_book),
      datasets: [{
        label: 'Jumlah',
        data: JSON.parse(data_book),
        backgroundColor: [
          'rgb(255, 99, 132)',
          'rgb(75, 192, 192)',
          'rgb(255, 205, 86)',
          'rgb(201, 203, 207)',
          'rgb(54, 162, 235)'
        ]
      }]
    },


    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });


 </script>
@endpush
