@extends('layout')

@section('content')
    <h1>Daftar Kue</h1>
    <a href="{{ route('myadmin.create') }}">Tambah Kue</a>
    <table id="table_id" class="display">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Kue</th>
                <th>Nama Kue</th>
                <th>Harga Kue</th>
                <th>Gambar Kue</th>
                <th>Tanggal</th>
                <th>Aksi</th>
                <th>Set BS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $manika)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $manika->kode_kue }}</td>
                    <td>{{ $manika->nama_kue }}</td>
                    <td>{{ $manika->formatted_harga }}</td>
                    <td><img src="{{ asset('storage/' . $manika->gambar_kue) }}" alt="{{ $manika->nama_kue }}" width="100"></td>
                    <td>{{ $manika->created_at->format('d-m-Y') }}</td>
                    <td>
                        <a href="{{ route('myadmin.edit', $manika->kode_kue) }}">Edit</a>
                        <form action="{{ route('myadmin.destroy', $manika->kode_kue) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                    <td><input type="checkbox" class="bs-checkbox" data-id="{{ $manika->kode_kue }}" {{ $manika->status_bs ? 'checked' : '' }}></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
       <form method="POST" action="{{ route('logout') }} ">
           @csrf   
           <div class="mb-3">
               <button class="btn btn-primary" >
                   Logout
               </button>
           </div>
       </form>
   </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.bs-checkbox');
            const maxChecks = 4;

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const checkedCheckboxes = document.querySelectorAll('.bs-checkbox:checked');
                    if (checkedCheckboxes.length > maxChecks) {
                        this.checked = false;
                        alert('Maksimal hanya 4 kue yang dapat dijadikan Best Seller.');
                    } else {
                        const id = this.getAttribute('data-id');
                        const status = this.checked;

                        fetch(`/myadmin/${id}/set-bs`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ status })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                this.checked = !status;
                                alert(data.message);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
