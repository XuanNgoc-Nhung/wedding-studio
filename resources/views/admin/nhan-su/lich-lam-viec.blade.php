@extends('admin.layouts.app')

@section('content')
<div class="card">
    <h5 class="card-header">Lịch làm việc</h5>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
        @endif
    </div>
</div>
@endsection