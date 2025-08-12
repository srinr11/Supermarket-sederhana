@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Form Transaksi Penjualan</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('sales.store') }}">
        @csrf

        <div class="mb-3">
            <label for="customer_name" class="form-label">Nama Pelanggan</label>
            <input type="text" name="customer_name" class="form-control" id="customer_name" required>
        </div>

        <hr>
        <h4 class="mb-3">Daftar Produk</h4>

        <div id="product-list">
            <div class="product-row row g-2 align-items-center mb-3">
                <div class="col-md-5">
                    <select name="products[0][product_id]" class="form-select product-select" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }} - Rp{{ number_format($product->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="products[0][quantity]" class="form-control quantity" value="1" min="1" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control subtotal" placeholder="Subtotal" readonly>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger remove-row">Hapus</button>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary" id="add-product">+ Tambah Produk</button>
        </div>

        <div class="mb-4">
            <label for="total" class="form-label">Total</label>
            <input type="text" id="total" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
    </form>
</div>

{{-- Script --}}
<script>
let rowIdx = 1;

document.getElementById('add-product').addEventListener('click', function () {
    const firstRow = document.querySelector('.product-row');
    const newRow = firstRow.cloneNode(true);

    newRow.querySelectorAll('input, select').forEach(input => {
        input.name = input.name.replace(/\[\d+\]/, `[${rowIdx}]`);
        if (input.classList.contains('subtotal')) input.value = '';
        if (input.classList.contains('quantity')) input.value = 1;
        if (input.tagName === 'SELECT') input.selectedIndex = 0;
    });

    document.getElementById('product-list').appendChild(newRow);
    rowIdx++;
});

document.addEventListener('change', function (e) {
    if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity')) {
        updateSubtotals();
    }
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        const rows = document.querySelectorAll('.product-row');
        if (rows.length > 1) {
            e.target.closest('.product-row').remove();
            updateSubtotals();
        }
    }
});

function updateSubtotals() {
    let total = 0;
    document.querySelectorAll('.product-row').forEach(row => {
        const select = row.querySelector('.product-select');
        const quantity = parseInt(row.querySelector('.quantity').value) || 0;
        const price = parseFloat(select.selectedOptions[0]?.getAttribute('data-price')) || 0;
        const subtotal = quantity * price;

        row.querySelector('.subtotal').value = 'Rp' + subtotal.toLocaleString('id-ID');
        total += subtotal;
    });

    document.getElementById('total').value = 'Rp' + total.toLocaleString('id-ID');
}
</script>
@endsection
