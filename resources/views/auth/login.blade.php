@extends('layouts.auth')

@section('content')

 <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              @include('includes.logo')
              <!-- /Logo -->
              <h4 class="mb-2">Welcome to MyLibrary 👋</h4>
              <p class="mb-4">Please sign-in to your account</p>
              <div class="alert alert-primary alert-dismissible fade show" role="alert">
                Login with username: admin@mail.com, 
                <br>
                password: password.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              
              <form id="loginForm">
                @csrf <!-- Laravel CSRF Protection -->
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
      
                    <div id="error-message" style="color:red"></div>
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                     @if (Route::has('password.request'))
                        <small><a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a></small>
                    @endif
                  </div>
                  <div class="input-group input-group-merge">
                    <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                    
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                  </div>
                </div>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </div>
              </form>

              <p class="text-center">
                <span>New on our platform?</span>
                <a href="{{ route('register') }}">
                  <span>Create an account</span>
                </a>
              </p>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>
@endsection

@push('styles')
     <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
@endpush

@push('script')
  <script>
    $(document).ready(function(){

      // Set CSRF token for all AJAX requests
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      // Handle form submission with AJAX
      $('#loginForm').on('submit', function(e){
        e.preventDefault(); // Prevent form from submitting normally
        
        let email = $('#email').val();
        let password = $('#password').val();

        $.ajax({
            url : '/api/login', // Make sure this is the correct API URL
            type: "POST",       // POST request
            dataType: 'json',
            contentType : 'application/json',
            data: JSON.stringify({
              email: email,
              password: password,
            }),
            success: function(response){
              localStorage.setItem('auth_token', response.token); // Store token
              window.location.href = '/';  // Redirect after successful login
            },
            error: function(xhr,status,error) {
               $('#error-message').text('Login gagal: ' + xhr.responseJSON.message); // Tampilkan pesan error
            }
        });
      });
    });
</script>

@endpush
