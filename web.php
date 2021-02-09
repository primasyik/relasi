<?php

use App\Profile;
use App\user;
use App\Post;
use App\Category;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth']], function(){

Route::get('/create/user', function () {
    $user = User::create([
        'name' => "Jill",
        'email' => "jill@mail.test",
        'password' => bcrypt(12345678)
    ]);
    return $user;
});

Route::get('/create/profile', function () {
    //menambahkan data secara manual
    // $profile = Profile::create([
    //     'user_id' => "1",
    //     'phone' => "6273441517",
    //     'address' => "Cengkareng"
    // ]);

    //menambahkan data menggunakan eloquent
    $user = User::find(8);
    $user->profile()->create([
        'phone' => "6273441521",
        'address' => "Cinangneng"
    ]);
    return $user;
});

Route::get('/create/user_profile', function () {
    $user = User::findOrFail(1);

    $profile = new Profile([
        'phone' => '6273441516',
        'address' => 'Ciledug'
    ]);

    $user->profile()->save($profile);
    return $user;
});

Route::get('/read_user_onedata', function () {
    $user = User::find(2);
    return $user->profile->address;
});

Route::get('/read_user_manydata', function () {
    $user = User::find(2);
    $data = [
        'name' => $user->name,
        'email' => $user->email,
        'address' => $user->profile->address,
        'phone' => $user->profile->phone,
    ];
    return $data;
});

//memanggil data user melalui profile
//setting di model profile dengan belongsTo
Route::get('/read_profile_onedata', function () {
    $profile = Profile::where('phone', '6273441517')->first();

    return $profile->user->name;
});

Route::get('/read_profile_manydata', function () {
    $profile = Profile::where('phone', '6273441517')->first();
    $data = [
        'name' => $profile->user->name,
        'email' => $profile->user->email,
        'address' => $profile->address,
        'phone' => $profile->phone,
    ];
    return $data;
});

//update data relasi onte to one
Route::get('/update_profile_satu', function () {
    $user = User::find(2);
    $user->profile()->update([
        'phone' => '6281318452012',
        'address' => 'Petukangan Selatan'
    ]);
    return $user;
});

//cara lain update data relasi onte to one
Route::get('/update_profile_dua', function () {
    $user = User::find(2);

    $data = [
        'address' => "M. Saidi Raya",
        'phone' => '6285694224507',
    ];

    $user->profile()->update($data);
    return $user;
});

//delete data
Route::get('/delete_profile', function () {
    $user = User::find(2);
    $user->profile()->delete();
    return $user;
});

//one to many
Route::get('/create/user_post', function () {
    $user = User::create([
        'name' => 'Prima',
        'email' => 'prima@mail.test',
        'password' => bcrypt(12345678)
    ]);
    $user->posts()->create([
        'title' => 'Judul postingan',
        'body' => 'hai, berikut ini ada tutorial membuat blog'
    ]);
    return 'success';
});

Route::get('/create/post', function () {
    $user = User::findOrFail(4);

    $user->posts()->create([
        'title' => 'Artikel ketiga james',
        'body' => 'ini adalah artikel ketiga yang ditu is oleh james'
    ]);
    return 'success';
});

Route::get('/read/post/loop', function () {
    $user = User::find(3);
    $posts = $user->posts()->get();

    foreach ($posts as $post) {
        $data[] = [
            'name' => $post->user->name,
            'title' => $post->title,
            'body' => $post->body,
        ];
    }
    return $data;
});

//read relasi one to many
Route::get('/read/post', function () {
    $user = User::find(3);
    $post = $user->posts()->first();
    $data = [
        'name' => $post->user->name,
        'title' => $post->title,
        'body' => $post->body,
    ];
    return $data;
});

//update relasi one to many
//update whereId
Route::get('/update/post', function () {
    $user = User::findOrFail(3);
    $user->posts()->whereId(1)->update([
        'title' => 'Judul bagian satu update',
        'body' => 'Hai, ini dari blog yang sudah diubah'
    ]);
    return 'success';
});

//update where
Route::get('/update/post/id', function () {
    $user = User::findOrFail(3);
    $user->posts()->where('id', 2)->update([
        'title' => 'Judul bagian update',
        'body' => 'Hai, ini dari blog yang sudah diubah'
    ]);
    return 'success';
});

//update keseluruhan data
Route::get('/update/post/all', function () {
    $user = User::findOrFail(3);
    $user->posts()->update([
        'title' => 'Judul update semua sama',
        'body' => 'Hai, ini dari blog yang sudah diubah'
    ]);
    return 'success';
});

//delete one to many
Route::get('/delete/post/whereid', function () {
    $user = User::findOrFail(3);
    $user->posts()->whereId(2)->delete();
    return 'success';
});

Route::get('/delete/post/where', function () {
    $user = User::findOrFail(3);
    $user->posts()->where('id', 1)->delete();
    return 'success';
});

//delete semua data
Route::get('/delete/post/all', function () {
    $user = User::findOrFail(3);
    $user->posts()->delete();
    return 'success';
});

//delete user_id
Route::get('/delete/post/userid', function () {
    $user = User::findOrFail(2);
    $user->posts()->whereUserId(2)->delete();
    return 'success';
});

//many to many
Route::get('/create/categories', function () {
    $post = Post::findOrFail(2);
    $post->categories()->create([
        'slug' => str_slug('PHP mahir', '-'),
        'category' => 'Belajar PHP'
    ]);
    return 'success';
});

//menambahkan data user, post, category
Route::get('/create/all', function () {
    $user = User::create([
        'name' => 'jack',
        'email' => 'jack@mail.test',
        'password' => bcrypt(12345678)
    ]);

    $user->posts()->create([
        'title' => 'Artikel Jack',
        'body' => 'Hai, ini adalah artikel yang ditulis oleh Jack'
    ])->categories()->create([
        'slug' => str_slug('profil pribadi', '-'),
        'category' => 'Kategori Pribadi'
    ]);
    return 'success';
});

//menampilkan data categori many to many
Route::get('/read/categories', function () {
    $post = Post::find(2);

    $categories = $post->categories;
    foreach ($categories as $category) {
        echo $category->slug . '</br>';
    }
});

//menampilkan categori satu data many to many
Route::get('/read/category', function () {
    $post = Post::find(1);
    $categories = $post->categories->where('id', 2);
    foreach ($categories as $category) {
        echo $category->slug . '</br>';
    }
});

//invers many to many category->post
Route::get('/read/category/inverse', function () {
    $category = Category::find(3);

    $posts = $category->posts;
    foreach ($posts as $post) {
        echo $post->title . '</br>';
    }
});

Route::get('/attach', function () {
    $post = Post::findOrFail(2);
    $post->categories()->attach(2);
    return 'success';
});

Route::get('/attach/allcategory', function () {
    $post = Post::findOrFail(2);
    $post->categories()->attach(2);
    return 'success';
});

});

Route::group(['middleware' => ['auth', 'auth.admin']], function(){
    Route::get('user', 'UserController@index');
    Route::get('/category/{id}', 'CategoryController@index');
});

Route::group(['middleware' => ['auth', 'auth.user']], function(){
    Route::get('/post/{id}', 'PostController@index');
});