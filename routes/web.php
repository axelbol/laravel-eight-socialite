<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/auth/github/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/github/callback', function () {
    $githubUser = Socialite::driver('github')->user();

    $user = User::firstOrCreate(
        [
            'provider_id' => $githubUser->getId()
        ],
        [
            'email' => $githubUser->getEmail(),
            'name' => $githubUser->getName(),
        ]
    );
    // create a new user in database
    // $user = User::create([
    //     'email' => $githubUser->getEmail(),
    //     'name' => $githubUser->getName(),
    //     'provider_id' => $githubUser->getId(),
    // ]);
    // log the user in
    auth()->login($user, false);

    // redirect dashboard
    return redirect('dashboard');
});
