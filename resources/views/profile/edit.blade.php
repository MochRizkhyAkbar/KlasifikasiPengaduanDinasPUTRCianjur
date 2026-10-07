@extends('layouts.admin_layout')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-blue-900">Profil Saya</h1>
        <p class="text-gray-500">Perbarui informasi profil dan kata sandi akun Anda.</p>
    </div>

    <div class="space-y-6">
        {{-- <div class="p-8 bg-white shadow-sm border-t-4 border-blue-900 rounded-xl">
            @include('profile.partials.update-profile-information-form')
        </div> --}}

        <div class="p-8 bg-white shadow-sm border-t-4 border-blue-900 rounded-xl">
            @include('profile.partials.update-password-form')
        </div>

        {{-- <div class="p-8 bg-white shadow-sm border-t-4 border-blue-900 rounded-xl">
            @include('profile.partials.delete-user-form')
        </div> --}}
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

    {{-- SweetAlert2 Toast untuk BERHASIL (Edit Profil & Ubah Password) --}}
    @if (session('success') || session('status') === 'profile-updated' || session('status') === 'password-updated')
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') ?? (session('status') === 'password-updated' ? 'Kata sandi berhasil diperbarui!' : 'Informasi profil berhasil diperbarui!') }}',
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                color: '#166534'
            });
        </script>
    @endif

    {{-- SweetAlert2 Toast untuk GAGAL / ERROR VALIDASI (Edit Profil & Ubah Password) --}}
    @if (session('error') || $errors->any() || $errors->updatePassword->any())
        <script>
            let errorMessage = '{{ session('error') }}';

            @if($errors->updatePassword->any())
                errorMessage = '{{ $errors->updatePassword->first() }}';
            @elseif($errors->any())
                errorMessage = '{{ $errors->first() }}';
            @endif

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: errorMessage || 'Terjadi kesalahan saat menyimpan perubahan.',
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                color: '#991b1b'
            });
        </script>
    @endif
@endpush
