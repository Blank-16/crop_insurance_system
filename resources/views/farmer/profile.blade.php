<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complete Farmer Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('farmer.profile.store') }}">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="land_size" :value="__('Land Size (e.g. 5 Acres)')" />
                        <x-text-input id="land_size" class="block mt-1 w-full" type="text" name="land_size" :value="old('land_size', $profile->land_size ?? '')" required autofocus />
                        <x-input-error :messages="$errors->get('land_size')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="crop_type" :value="__('Main Crop Type')" />
                        <x-text-input id="crop_type" class="block mt-1 w-full" type="text" name="crop_type" :value="old('crop_type', $profile->crop_type ?? '')" required />
                        <x-input-error :messages="$errors->get('crop_type')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="region" :value="__('Farming Region')" />
                        <x-text-input id="region" class="block mt-1 w-full" type="text" name="region" :value="old('region', $profile->region ?? '')" required />
                        <x-input-error :messages="$errors->get('region')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>
                            {{ __('Save Profile') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
