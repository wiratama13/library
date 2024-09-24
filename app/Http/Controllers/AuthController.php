<?php

namespace App\Http\Controllers;

use App\Models\About;
use Exception;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    protected $redirectTo = '/';

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }


    public function login(Request $request)
    {

    $request->validate([
        'email' => ['required', function ($attribute, $value, $fail) {
            if (!DB::table('users')->where('email', $value)->exists()) {
                $fail('Email tidak ditemukan atau password salah');
            }
        }],
        'password'   => 'required',
    ]);


    $auth = auth()->attempt($request->only('email', 'password'));

    if($auth) {
        $user = Auth::user();

        // Hapus token lama jika ada
        $user->tokens()->delete();

        // Buat token baru
        $tokenResult = $user->createToken('auth_token');
        $newWtoken = $tokenResult->plainTextToken;
        // Update kolom expires_at secara manual di tabel personal_access_tokens
        // $token = $tokenResult->accessToken;
        // // dd($token);
        // PersonalAccessToken::where('id', $token->id)->update([
        //     'expires_at' => Carbon::now()->addHour(1),
        // ]);


        return response()->json([
            'message' => 'Login Berhasil',
            'token'   => $newWtoken,
            'user'    => $user,
            // 'tokenId' => $token
        ]);
    }
    return response()->json([
        'message' => 'email atau password salah'
    ]);

    
    
    }

    public function register(Request $request)
    {

        $unvalidate = [
            'email.unique' => 'Email sudah terdaftar silahkan login'
        ];
        $validate = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], $unvalidate);

        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

      

        return redirect()->route('login')->with('success', 'berhasil membuat pengguna baru, silahkan login');
    }


    public function logout(Request $request)
    {
    
        // auth()->logout();
        // $request->user()->token()->delete();
       

        // return redirect()->route('login');
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Logged out successfully'
            ]);
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Redirect to login page for web requests
        return redirect()->route('login');
    }

    public function error()
    {
        return view('403');
    }

    public function ubahPassword()
    {
        User::where('kode_mitra', '240100001')->update([
            'password' => Hash::make('katasandi')
        ]);

        return 'ubah password berhasil';
    }
}
