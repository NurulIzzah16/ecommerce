<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users')->only(['index']);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak diizinkan mengakses halaman ini.');
        }

        $users = User::where('role', 'user')->get();
        return view('admin.users.index', compact('users'));
    }


    /**
     * Menampilkan profil user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();

        // Hanya izinkan user melihat profil sendiri atau admin melihat profil siapa saja
        if ($authUser->role !== 'admin' && $authUser->id !== $user->id) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk melihat profil ini.');
        }

        // Ambil data orders dan gabungkan dengan status dari tabel payments
        $orders = Order::where('orders.user_id', $user->id)
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id')
            ->select('orders.id', 'orders.total_price', 'payments.status', 'orders.created_at')
            ->get();

        return view('admin.users.show', compact('user', 'orders'));
    }

    /**
     * Menampilkan halaman edit profil user (hanya untuk user sendiri)
     */
    public function edit()
    {
        $user = Auth::user();
        // Pastikan user hanya bisa mengedit profilnya sendiri
        if ($user->role !== 'admin' && $user->id !== Auth::user()->id) {
            return redirect()->route('home')->with('error', 'Anda tidak diizinkan mengedit profil ini.');
        }

        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Memperbarui profil user
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50',
            'email'    => 'required|email|max:100|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update the user data
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Ensure this is an Eloquent model
        if ($user instanceof \Illuminate\Database\Eloquent\Model) {
            $user->save();
        } else {
            // Debugging output
            dd('User is not an Eloquent model');
        }

        return redirect()->route('users.show', $user->id)->with('success', 'Profil berhasil diperbarui.');
    }
    /**
     * Menampilkan halaman profil user
     */
    public function profile()
    {
        $user = Auth::user();
        return view('admin.users.profile', compact('user'));
    }

    /**
     * Membatasi login untuk user biasa
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Cek login dengan role
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Jika bukan admin, arahkan ke halaman lain
            if ($user->role !== 'admin') {
                Auth::logout();
                return redirect()->route('home')->with('error', 'Anda tidak diizinkan login ke dashboard admin.');
            }

            return redirect()->route('admin.dashboard'); // Ganti dengan route admin dashboard Anda
        }

        return redirect()->route('login')->with('error', 'Login gagal. Silakan coba lagi.');
    }

    public function export()
    {
        return Excel::download(new UserExport, 'users.xlsx');
    }
}
