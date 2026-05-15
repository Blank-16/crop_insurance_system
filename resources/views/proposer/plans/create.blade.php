<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($plan) ? __('Edit Insurance Plan') : __('Create New Insurance Plan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <form action="{{ isset($plan) ? route('proposer.plans.update', $plan->id) : route('proposer.plans.store') }}" method="POST">
                        @csrf
                        @if(isset($plan))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1">
                                <label for="crop_type" class="block text-sm font-medium text-gray-700">Crop Type</label>
                                <input type="text" name="crop_type" id="crop_type" value="{{ old('crop_type', $plan->crop_type ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('crop_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-1">
                                <label for="region" class="block text-sm font-medium text-gray-700">Region</label>
                                <input type="text" name="region" id="region" value="{{ old('region', $plan->region ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('region') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-1">
                                <label for="premium" class="block text-sm font-medium text-gray-700">Premium (₹)</label>
                                <input type="number" step="0.01" name="premium" id="premium" value="{{ old('premium', $plan->premium ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('premium') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-1">
                                <label for="coverage" class="block text-sm font-medium text-gray-700">Coverage (₹)</label>
                                <input type="number" step="0.01" name="coverage" id="coverage" value="{{ old('coverage', $plan->coverage ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('coverage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-full">
                                <label for="duration" class="block text-sm font-medium text-gray-700">Duration (in Months)</label>
                                <input type="number" name="duration" id="duration" value="{{ old('duration', $plan->duration ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('duration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('proposer.plans.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 transition">Cancel</a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                {{ isset($plan) ? 'Update Plan' : 'Save Plan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
