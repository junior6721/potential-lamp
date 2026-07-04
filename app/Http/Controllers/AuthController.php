<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\HistoriqueConnexion;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
public function showLogin() {
return view('auth.login');
    }
public function login(Request $request) {
$credentials = $request->validate([
'email'    => 'required|email',
'password' => 'required',
        ]);
if (Auth::attempt($credentials, $request->remember)) {
$request->session()->regenerate();

            HistoriqueConnexion::create([
                'user_id'    => auth()->id(),
                'adresse_ip' => $request->ip(),
                'connecte_a' => now(),
            ]);

return redirect()->route('dashboard');
        }
return back()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }
public function logout(Request $request) {
        Auth::logout();
$request->session()->invalidate();
$request->session()->regenerateToken();
return redirect()->route('login');
    }
}