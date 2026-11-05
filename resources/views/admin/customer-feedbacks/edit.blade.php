@extends('admin.dashboard.dashboard')
@section('admin_title', 'Edit feedback photo · Nebula Mart Admin')
@section('admin_contents')

    <h1 class="font-display font-extrabold text-2xl sm:text-[28px] tracking-tight">Edit feedback photo</h1>

    <form method="POST" action="{{ route('admin.customer-feedbacks.update', $feedback) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.customer-feedbacks._form', ['feedback' => $feedback])
    </form>

@endsection
