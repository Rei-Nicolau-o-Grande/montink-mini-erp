<?php

use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProdutoController::class, 'index'])->name('produto.index');
Route::get('/produto/create', [ProdutoController::class, 'create'])->name('produto.create');
Route::post('/produto/store', [ProdutoController::class, 'store'])->name('produto.store');
Route::get('/produto/{produto}/edit', [ProdutoController::class, 'edit'])->name('produto.edit');
Route::put('/produto/{produto}', [ProdutoController::class, 'update'])->name('produto.update');
Route::post('/carrinho/add', [CarrinhoController::class, 'addToCart'])->name('carrinho.add');
Route::post('/carrinho/update/quantidade', [CarrinhoController::class, 'updateQuantidadeCart'])->name('carrinho.update.quantidade');
Route::post('/carrinho/remove', [CarrinhoController::class, 'removeItemCart'])->name('carrinho.remove');
