@extends('layouts.template')

@section('title', 'book page')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y" id="controlData">
    <div class="row">
      <div class="col-md-5 offset-md-3">
        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1"><i class='bx bx-search-alt-2' style="font-size: 1.3rem" ></i></box-icon></span>
          <input type="text" class="form-control" placeholder="Username" autocomplete="off" v-model="search">
        </div>
      </div>
      <div class="col-md-3">
        <button class="btn btn-primary" @click="addData()">
         new book
        </button>
      </div>
    </div>

    <div class="row mt-4 justify-content-center">
      <div class="col-md-3 col-sm-6 col-xs-12 m-3" v-for="(book,index) in filteredList" >
        <a href="">
          <div class="card card-item position-relative" style="width: 18rem;">
          <div class="card-body" v-on:click.prevent="editData(book.id)">
            <h5 class="card-title">@{{ book.title }}, jumlah : (@{{ book.qty }})</h5>
            <h5 class="card-title">@{{ book.catalog_id }} (@{{ book.author_id }})</h5>
            <p class="card-text">Rp@{{ numberWithSpaces(book.price) }},- </p>
          </div>
          <button type="submit" class="btn btn-danger position-absolute btn-delete" v-on:click="deleteData(book.id)"
            >delete</button>
        </div>
        </a>
      </div>
    </div>

               <!-- Modal -->
    <div class="modal fade" id="modal-book" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">Modal title</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <form role="form">

            <div class="row g-2 mb-2">
              <div class="col">
                <label for="isbn" class="form-label">isbn</label>
                <input type="number" v-model="isbn" id="isbn" name="isbn" class="form-control" placeholder="masukkan nama"  />
              </div>

              <div class="col">
                <label for="title" class="form-label">title</label>
                <input type="text" v-model="title" id="title" name="title" class="form-control" placeholder="xxxx@xxx.xx"/>
              </div>
             
            </div>
            <div class="row g-2 mb-2">
              <div class="col mb-0">
                <label for="year" class="form-label">year</label>
                <input type="number" v-model="year" id="year" name="year" class="form-control" placeholder="masukkan tahun terbit" />
              </div>
              <div class="col mb-0">
                <label for="publisher" class="form-label">publisher</label>
                <select v-model="publisher_id" name="publisher_id" id="" class="form-control">
                 @foreach ($publishers as $publisher)
                    <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                 @endforeach
                
                </select>
              </div>        
             
            </div>

            <div class="row g-2 mb-2">
              <div class="col">
                <label for="author" class="form-label">author</label>
               <select v-model="author_id" name="author_id" id="" class="form-control">
                 @foreach ($authors as $author)
                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                 @endforeach
                </select>
              </div>  
              <div class="col mb-2">
                <label for="catalog" class="form-label">catalog</label>
                <select v-model="catalog_id" name="catalog_id" id="" class="form-control">
                 @foreach ($catalogs as $catalog)
                    <option value="{{ $catalog->id }}">{{ $catalog->name }}</option>
                 @endforeach
                </select>
              </div>      
            </div>

            <div class="row mb-2">
              <div class="col">
                <label for="qty" class="form-label">qty</label>
                <input type="number" v-model="qty" id="qty" name="qty" class="form-control" placeholder="masukkan alamat" />
              </div>             
            </div>

            <div class="row mt-2 mb-5">
              <div class="col">
                <label for="price" class="form-label">price </label>
                <input type="number" v-model="price" id="price" name="price" class="form-control" placeholder="masukkan alamat" />
              </div>             
            </div>

            <div class="position-relative save-btn">
              <button type="submit" class="btn btn-primary position-absolute bottom-0 end-0" 
              v-on:click.prevent="storeData">Save changes</button>
            </div>
          </form>
          </div>
        </div>
      </div>
     </div>

  </div>
@endsection

@push('styles')
<style>
    .btn-delete{
    bottom: 15px;
    right: 15px;
  }
  .save-btn {
    margin-top: 80px;
  }
  
</style>


@push('script')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
  <script>
  
  let actionUrl = '{{ url('books') }}'
  let apiUrl = '{{ url('api/books') }}'

  const { createApp } = Vue
      
   var App = createApp({
    data() {
      return {
        books: [],
        search : '',
        // book: {},
        editStatus: null,
        isbn:null,
        title:null,
        year:null,
        publisher_id:null,
        author_id:null,
        catalog_id:null,
        qty:null,
        price:null,
      }
    },
    mounted: function() {
     this.get_books()
    },
    methods : {
      get_books()
      {
        const _this = this
      
        $.ajax({
          url: apiUrl,
          methods: 'GET',
          success : function(data) {
            _this.books = JSON.parse(data)
          },
          error : function(error) {
            console.log(error)
          }
        })
      },
      numberWithSpaces(x) {
          return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      },
      addData()
      {
        this.book = ''
        this.isbn = null,
        this.title = null,
        this.year = null,
        this.publisher_id = null,
        this.author_id = null,
        this.catalog_id = '',
        this.qty = null,
        this.price = null,
        $('#modal-book').modal('show')
      },

      addDataUpdate()
      {
        $('#modal-book').modal('show')
      },

      storeData() {
        var form_data = new FormData()
        form_data.append("isbn", this.isbn)
        form_data.append("title", this.title)
        form_data.append("year", this.year)
        form_data.append("publisher_id", this.publisher_id)
        form_data.append("author_id", this.author_id)
        form_data.append("catalog_id", this.catalog_id)
        form_data.append("qty", this.qty)
        form_data.append("price", this.price)
        form_data.append("editStatus", this.editStatus)

        axios.post(`api/book-store`, form_data)
          .then((response) => {
              $('#modal-book').modal('hide')
              alert('success')
              this.isbn = "";
              this.title = "";
              this.year = "";
              this.publisher_id = "";
              this.author_id = "";
              this.catalog_id = "";
              this.qty = "";
              this.price = "";
              this.editStatus = null;
              this.get_books()
          }).catch((err) => {
            alert('error')
            console.log(err)
          });
      },

      editData(id)
      {
   
        this.editStatus = id
        var url = "{{ url('api/get-book') }}" + '/' + id

        axios.get(url)
          .then(resp => {
            var data = resp.data
              this.isbn = data.isbn;
              this.title = data.title;
              this.year = data.year;
              this.publisher_id = data.publisher_id;
              this.author_id = data.author_id;
              this.catalog_id = data.catalog_id;
              this.qty = data.qty;
              this.price = data.price;
              $('#modal-book').modal('show')
              
          }).catch(err => {
            alert('error')
            console.log(err)
          }).finally(()=> {
          })
       
      },

      deleteData(id){
        if(confirm("Are you sure to delete this book ?")){
            axios.delete(`/api/book-delete/${id}`).then(response => {
              this.get_books()
            }).catch(error=>{
              console.log(error)
            })
        }
      }
    },
      
    computed : {
      filteredList()
      {
        return this.books.filter(book => {
          return book.title.toLowerCase().includes(this.search.toLowerCase())
        })
      }
    }
  }).mount('#controlData')
  </script>
@endpush