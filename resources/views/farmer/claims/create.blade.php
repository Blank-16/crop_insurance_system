<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('File a New Claim') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    @if($policies->isEmpty())
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4">
                            You must have at least one active policy to file a claim.
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('farmer.plans') }}" class="text-indigo-600 hover:text-indigo-800 underline">Browse Insurance Plans</a>
                        </div>
                    @else
                        <form action="{{ route('farmer.claims.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="policy_id" class="block text-sm font-medium text-gray-700">Select Active Policy</label>
                                    <select name="policy_id" id="policy_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="" disabled selected>-- Select Policy --</option>
                                        @foreach($policies as $policy)
                                            <option value="{{ $policy->id }}" {{ old('policy_id') == $policy->id ? 'selected' : '' }}>
                                                Plan #{{ $policy->plan->id }} - {{ $policy->plan->crop_type }} ({{ $policy->plan->region }}) - Coverage: ${{ number_format($policy->plan->coverage, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('policy_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="damage_type" class="block text-sm font-medium text-gray-700">Type of Damage</label>
                                    <select name="damage_type" id="damage_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="" disabled selected>-- Select Type --</option>
                                        <option value="flood" {{ old('damage_type') == 'flood' ? 'selected' : '' }}>Flood</option>
                                        <option value="drought" {{ old('damage_type') == 'drought' ? 'selected' : '' }}>Drought</option>
                                        <option value="pest" {{ old('damage_type') == 'pest' ? 'selected' : '' }}>Pest Infestation</option>
                                        <option value="storm" {{ old('damage_type') == 'storm' ? 'selected' : '' }}>Storm/Hurricane</option>
                                    </select>
                                    @error('damage_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="damage_percentage" class="block text-sm font-medium text-gray-700">Estimated Damage Percentage (1-100)</label>
                                    <input type="number" name="damage_percentage" id="damage_percentage" min="1" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('damage_percentage') }}" required>
                                    @error('damage_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Detailed Description of Incident/Reason for Claim</label>
                                    <textarea name="description" id="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description') }}</textarea>
                                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="document" class="block text-sm font-medium text-gray-700">Proof Document/Image (PDF, JPG, PNG)</label>
                                    <input type="file" name="document" id="document" accept=".jpg,.jpeg,.png,.pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                    @error('document') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-8 flex items-center justify-end">
                                <a href="{{ route('farmer.claims.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 transition">Cancel</a>
                                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Submit Claim
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
