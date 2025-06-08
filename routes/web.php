<?php

use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CupomController;
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

Route::post('/carrinho/cupom/aplicar', [CarrinhoController::class, 'aplicarCupom'])->name('cupom.aplicar');
Route::post('/carrinho/cep/buscar',   [CarrinhoController::class, 'buscarCep'])->name('carrinho.cep');

Route::post('/carrinho/cupom/remover', [CarrinhoController::class, 'removerCupom'])->name('cupom.remover');
Route::post('/carrinho/cep/remover', [CarrinhoController::class, 'removerCep'])->name('cep.remover');

Route::get('/cupons', [CupomController::class, 'index'])->name('cupons.index');
Route::get('/cupons/create', [CupomController::class, 'create'])->name('cupons.create');
Route::post('/cupons/store', [CupomController::class, 'store'])->name('cupons.store');
Route::get('/cupons/{cupom}/edit', [CupomController::class, 'edit'])->name('cupons.edit');
Route::put('/cupons/{cupom}', [CupomController::class, 'update'])->name('cupons.update');
Route::delete('cupons/{cupom}', [CupomController::class, 'destroy'])->name('cupons.delete');
Route::patch('/cupons/{cupom}', [CupomController::class, 'active'])->name('cupons.active');

