<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Proposer Company Profile') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your organizational specifics such as your company name to be displayed publicly to farmers.") }}
        </p>
    </header>

    <form method="post" action="{{ route('proposer.profile.store') }}" class="mt-6 space-y-6">
        @csrf

        @php
            $profile = Auth::user()->proposerProfile;
        @endphp

        <div>
            <x-input-label for="company_name" :value="__('Company Name')" />
            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $profile->company_name ?? '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'proposer-profile-updated')
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
