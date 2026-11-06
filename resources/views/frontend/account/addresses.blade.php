@extends('frontend.account.layout')

@php $storeName = $setting('store_name', 'Multiverse of Jersey'); @endphp

@section('frontend_title', 'Saved addresses | '.$storeName)
@section('account_parent', true)
@section('account_title', 'Saved addresses')
@section('account_sub', 'Your delivery addresses. The default one is filled in at checkout.')

@section('account_body')

    @forelse ($addresses as $address)
        @php $form = 'edit-'.$address->id; @endphp

        <article class="card p-5 sm:p-6">

            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="font-semibold">
                        {{ $address->name }}
                        <span class="badge badge-soft ml-1">{{ $address->label }}</span>
                        @if ($address->is_default)
                            <span class="badge badge-def">Default</span>
                        @endif
                    </p>
                    <p class="text-sm text-ink/60 mt-1">{{ $address->full_address }}</p>
                    <p class="text-sm text-ink/60">{{ $address->phone }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @unless ($address->is_default)
                        <form method="POST" action="{{ route('account.addresses.default', $address) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-ghost btn-sm">Make default</button>
                        </form>
                    @endunless
                    <form method="POST" action="{{ route('account.addresses.destroy', $address) }}" onsubmit="return confirm('Remove this address?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost btn-sm !text-danger">Remove</button>
                    </form>
                </div>
            </div>

            <details class="acc-addr mt-4" @if (old('_form') === $form) open @endif>
                <summary>Edit this address</summary>

                <form method="POST" action="{{ route('account.addresses.update', $address) }}" class="grid sm:grid-cols-2 gap-4 mt-4" novalidate>
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="_form" value="{{ $form }}">
                    @include('frontend.account.partials.address_fields', ['form' => $form, 'saved' => $address])
                    <div class="sm:col-span-2">
                        <button type="submit" class="btn btn-primary btn-sm">Save address</button>
                    </div>
                </form>
            </details>

        </article>
    @empty
        <div class="empty card">
            <svg class="ic w-10 h-10 mx-auto text-ink/30" aria-hidden="true"><use href="#i-pin" /></svg>
            <h2 class="font-display font-bold text-xl mt-3">No saved address</h2>
            <p class="text-ink/55 mt-1">Add one below to fill in checkout faster.</p>
        </div>
    @endforelse

    @if ($addresses->count() < $limit)
        <section class="card p-5 sm:p-7" aria-labelledby="h-add">

            <h2 id="h-add" class="font-display font-extrabold text-xl">Add a new address</h2>

            <form method="POST" action="{{ route('account.addresses.store') }}" class="grid sm:grid-cols-2 gap-4 mt-5" novalidate>
                @csrf
                <input type="hidden" name="_form" value="new">
                @include('frontend.account.partials.address_fields', ['form' => 'new', 'saved' => null])
                <div class="sm:col-span-2">
                    <button type="submit" class="btn btn-primary">Save address</button>
                </div>
            </form>

        </section>
    @endif

@endsection
