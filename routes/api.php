<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::prefix('products')->controller(ProductController::class)->group(function () {
//    Route::middleware('auth:admin_api')->group(function () {
//        Route::post('/', 'store');
//        Route::post('update/{id}', 'update');
//        Route::delete('/{id}', 'delete');
//    });
//
//    Route::middleware('auth:customer_api')->group(function () {
//        Route::get('/', 'getAllProduct');
//        Route::get('/{id}', 'show');
//    });
//});


Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::post('login', 'login');
    Route::middleware('auth:admins')->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'me');
        Route::post('update_profile', 'update');
        Route::post('change_password', 'updatePassword');

        Route::prefix('question')->controller(QuestionController::class)->group( function() {
            Route::get('', 'list');
        });

        Route::prefix('Answers')->controller(AnswerController::class)->group( function() {
            Route::prefix('correctAns')->group(function () {
                Route::get('/{id}', 'indexCorrectAns');
                Route::post('create', 'storeCorrectAns');
                Route::delete('delete', 'destroyCorrectAns');
                Route::post('update/{id}', 'updateCorrectAns');
            });

            Route::prefix('wrongAns')->group(function () {
                Route::get('/{id}', 'indexWrongAns');
                Route::post('create', 'storeWrongAns');
                Route::delete('delete', 'destroyWrongAns');
                Route::post('update/{id}', 'updateWrongAns');
            });
        });

        Route::prefix('exam')->controller(ExamController::class)->group( function() {
            Route::get('', 'list');
        });
    });
});

Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::middleware('auth:users')->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'me');
        Route::post('update_profile', 'update');
        Route::post('change_password', 'updatePassword');
    });
});

Route::prefix('teacher')->controller(TeacherController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::middleware('auth:teachers')->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'me');
        Route::post('update_profile', 'update');
        Route::post('change_password', 'updatePassword');
    });
});

Route::prefix('category')->controller(CategoryController::class)->group(function () {
    Route::middleware('auth:admins')->group(function () {
        Route::post('create', 'store');
        Route::get('edit/{id}', 'edit');
        Route::post('update', 'update');
        Route::delete('delete/{id}', 'destroy');
    });

    Route::middleware('auth:users,teachers,admins')->group(function () {
        Route::get('', 'index');
    });
});

Route::prefix('question')->controller(QuestionController::class)->group(function () {
    Route::middleware('auth:teachers')->group(function () {
        Route::post('create', 'store');
        Route::post('update/{id}', 'update');
        Route::delete('delete/{id}', 'destroy');
    });

    Route::middleware('auth:teachers')->group(function () {
        Route::get('', 'index');
    });
});

Route::prefix('Answers')->controller(AnswerController::class)->group(function () {
    Route::middleware('auth:teachers')->group(function () {
        Route::prefix('correctAns')->group(function () {
            Route::get('/{id}', 'indexCorrectAns');
            Route::post('create', 'storeCorrectAns');
            Route::delete('delete', 'destroyCorrectAns');
            Route::post('update/{id}', 'updateCorrectAns');
        });

        Route::prefix('wrongAns')->group(function () {
            Route::get('/{id}', 'indexWrongAns');
            Route::post('create', 'storeWrongAns');
            Route::delete('delete', 'destroyWrongAns');
            Route::post('update/{id}', 'updateWrongAns');
        });
    });
});


Route::prefix('exam')->controller(ExamController::class)->group(function() {
    Route::middleware('auth:users')->group(function () {
        Route::get('start/{cate_id}', 'generateExam');
        Route::post('finish', 'finish');
        Route::get('history', 'index');
    });

    Route::middleware('auth:users,admins')->group(function () {
        Route::get('/{id}', 'getExam');
    });
});
