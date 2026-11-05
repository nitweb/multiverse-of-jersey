@extends('admin.dashboard.dashboard')
@section('admin_title', 'Add feedback photo · Nebula Mart Admin')
@section('admin_contents')

    <h1 class="font-display font-extrabold text-2xl sm:text-[28px] tracking-tight">Add feedback photo</h1>

    <form method="POST" action="{{ route('admin.customer-feedbacks.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.customer-feedbacks._form', ['feedback' => null])
    </form>

@endsection
