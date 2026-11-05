@extends('admin.dashboard.dashboard')
@section('admin_title', 'Customer Feedback · Nebula Mart Admin')
@section('admin_contents')

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display font-extrabold text-2xl sm:text-[28px] tracking-tight">Customer Feedback</h1>
            <p class="text-ink/55 mt-1">Real photos from verified buyers, shown as a slider on the homepage.</p>
        </div>
        <a href="{{ route('admin.customer-feedbacks.create') }}" class="btn btn-primary">
            <svg class="ic ic-sm">
                <use href="#i-plus" />
            </svg>Add photo
        </a>
    </div>

    <div class="grid sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($feedbacks as $feedback)
            <article class="card overflow-hidden group">
                <div class="relative aspect-[4/5] bg-bg">
                    <img src="{{ $feedback->image_url }}" class="w-full h-full object-cover" alt="">
                    <span class="absolute top-3 left-3">
                        <span class="badge {{ $feedback->is_active ? 'b-success' : 'b-muted' }}">{{ $feedback->is_active ? 'Active' : 'Draft' }}</span>
                    </span>
                </div>
                <div class="p-4 flex items-center justify-end gap-1">
                    <a href="{{ route('admin.customer-feedbacks.edit', $feedback) }}" class="icon-btn">
                        <svg class="ic">
                            <use href="#i-edit" />
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('admin.customer-feedbacks.destroy', $feedback) }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="icon-btn danger" data-delete="this feedback photo">
                            <svg class="ic">
                                <use href="#i-trash" />
                            </svg>
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <p class="text-ink/55">No feedback photos yet.</p>
        @endforelse
    </div>

@endsection
