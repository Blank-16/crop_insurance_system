<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Farmer Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your farm's agricultural specifics including land size and crop varieties.") }}
        </p>
    </header>

    <form method="post" action="{{ route('farmer.profile.store') }}" class="mt-6 space-y-6">
        @csrf

        @php
            $profile = Auth::user()->farmerProfile;
        @endphp

        <div>
            <x-input-label for="land_size" :value="__('Land Size (e.g. 5 Acres)')" />
            <x-text-input id="land_size" name="land_size" type="text" class="mt-1 block w-full" :value="old('land_size', $profile->land_size ?? '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('land_size')" />
        </div>

        <div>
            <x-input-label for="crop_type" :value="__('Main Crop Type')" />
            <x-text-input id="crop_type" name="crop_type" type="text" class="mt-1 block w-full" :value="old('crop_type', $profile->crop_type ?? '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('crop_type')" />
        </div>

        <div>
            <x-input-label for="region" :value="__('Farming Region')" />
            <x-text-input id="region" name="region" type="text" class="mt-1 block w-full" :value="old('region', $profile->region ?? '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('region')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'farmer-profile-updated' || session('success'))
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
