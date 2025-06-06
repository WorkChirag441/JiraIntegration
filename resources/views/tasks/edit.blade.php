@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">
                Edit Task: {{ $task['key'] }}
            </h2>

            <form method="POST" action="{{ route('task.update', $task['key']) }}" class="space-y-6">
                @csrf

                <div>
                    <label for="summary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Summary
                    </label>
                    <input type="text" id="summary" name="summary"
                        value="{{ $task['fields']['summary'] }}"
                        required
                        class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none">{{ $task['fields']['description']['content'][0]['content'][0]['text'] ?? '' }}</textarea>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Change Status
                    </label>
                    <select id="status" name="status" class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 shadow-sm focus:ring focus:ring-blue-300 focus:outline-none"
                            required>
                            @foreach ($transitions as $transition)
                                <option value="{{ $transition['id'] }}"
                                    {{ $task['fields']['status']['name'] === $transition['to']['name'] ? 'selected' : '' }}>
                                    {{ $transition['to']['name'] }}
                                </option>
                            @endforeach
                        </select>

                </div>

                <div class="flex justify-end">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md mr-2">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
