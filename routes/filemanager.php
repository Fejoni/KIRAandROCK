<?php

use Illuminate\Support\Facades\Route;
use \UniSharp\LaravelFilemanager\Controllers\LfmController;
use \UniSharp\LaravelFilemanager\Controllers\UploadController;
use \UniSharp\LaravelFilemanager\Controllers\ItemsController;
use \UniSharp\LaravelFilemanager\Controllers\FolderController;
use \UniSharp\LaravelFilemanager\Controllers\CropController;
use \UniSharp\LaravelFilemanager\Controllers\RenameController;
use \UniSharp\LaravelFilemanager\Controllers\ResizeController;
use \UniSharp\LaravelFilemanager\Controllers\DownloadController;
use \UniSharp\LaravelFilemanager\Controllers\DeleteController;

// display main layout
Route::get('/',[LfmController::class,'show'])->name('show');
// display integration error messages
Route::get('/errors',[LfmController::class,'getErrors'])->name('getErrors');
// upload
Route::any('/upload',[UploadController::class,'upload'])->name('upload');
// list images & files
Route::get('/jsonitems',[ItemsController::class,'getItems'])->name('getItems');
Route::get('/move',[ItemsController::class,'move'])->name('move');
Route::get('/domove',[ItemsController::class,'domove'])->name('domove');
// folders
Route::get('/newfolder',[FolderController::class,'getAddfolder'])->name('getAddfolder');
// list folders
Route::get('/folders',[FolderController::class,'getFolders'])->name('getFolders');
// crop
Route::get('/crop',[CropController::class,'getCrop'])->name('getCrop');
Route::get('/cropimage',[CropController::class,'getCropimage'])->name('getCropimage');
Route::get('/cropnewimage',[CropController::class,'getNewCropimage'])->name('getCropnewimage');
// rename
Route::get('/rename',[RenameController::class,'getRename'])->name('getRename');
// scale/resize
Route::get('/resize',[ResizeController::class,'getResize'])->name('getResize');
Route::get('/doresize',[ResizeController::class,'performResize'])->name('performResize');
// download
Route::get('/download',[DownloadController::class,'getDownload'])->name('getDownload');
// delete
Route::get('/delete',[DeleteController::class,'getDelete'])->name('getDelete');
