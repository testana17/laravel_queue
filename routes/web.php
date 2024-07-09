<?php
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\MessageController;
use App\Jobs\ProcessUserData;
use App\Jobs\ProcessOrder;
use App\Models\Order;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UsersController::class);
Route::get('/email-form', function () {
    return view('emails.email_form');
});

Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
Route::post('/messages', [MessageController::class, 'store'])->name('send-message');
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');

Route::post('/send-email', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'message' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();
    if (!$user) {
        $user = new User;
        $user->name = 'ZAL'; // Nama default jika user baru dibuat
        $user->email = $request->email;
        $user->password = bcrypt('password'); // Ini hanya contoh, seharusnya tidak menyimpan password secara plain
        $user->save();
    }

    Mail::to($request->email)->send(new WelcomeEmail($user, $request->message));

    return 'Email has been sent!';
})->name('send-email');

Route::get('/process-user', function () {
    // Buat pengguna baru
    $user = new User;
    $user->name = 'John Doe';
    $user->email = 'john.doe@example.com';
    $user->password = bcrypt('password');
    $user->save();

    // Dispatch job untuk memproses data pengguna
    ProcessUserData::dispatch($user);

    return 'User created and job dispatched!';
});

Route::get('/test-order', function () {
    // Buat user baru
    $user = User::create([
        'name' => 'Jane Doe',
        'email' => 'ahmadrizalrb60@gmail.com',
        'password' => bcrypt('password'),
    ]);

    // Buat pesanan baru
    $order = Order::create([
        'user_id' => $user->id,
    ]);

    // Dispatch job untuk memproses pesanan
    ProcessOrder::dispatch($order, $user);

    return 'Order has been processed.';
});