<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Campaign') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (\Session::has('success'))
                    <div class="alert alert-success">
                        {!! \Session::get('success') !!}
                    </div>
                @elseif (\Session::has('error'))
                    <div class="alert alert-error">
                        {!! \Session::get('error') !!}
                    </div>
                @endif
                <form method="POST" enctype="multipart/form-data" action="{{ route('store.campaign') }}" class="p-6">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Campaign Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-3">
                        <x-input-label for="csv" :value="__('Campaign CSV')" />
                        <x-text-input id="csv" class="block mt-1 w-full" type="file" name="csv" :value="old('csv')" required autofocus autocomplete="csv" accept=".csv" />
                        <x-input-error :messages="$errors->get('csv')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ms-3">
                        {{ __('Submit') }}
                    </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
