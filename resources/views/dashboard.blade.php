@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Dashboard</h2>
                <p class="mt-1 text-gray-600 dark:text-gray-300">Welcome, {{ auth()->user()->name }}</p>
            </div>

            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-4">Assigned Jira Tasks</h3>

                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700  w-full">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Project Name</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Task Summary</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Task Key</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Task Status</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Task Priority</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Sprint Name</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Created At</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($Jiratasks as $task)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task['fields']['project']['name'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task['fields']['summary'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task['key'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task['fields']['status']['name'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $task['fields']['priority']['name'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        @php
                                            $sprint = $task['fields']['customfield_10007'][0]['name'] ?? '-';
                                        @endphp
                                        {{ $sprint }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($task['fields']['created'])->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('task.edit', ['key' => $task['key']]) }}"
                                           class="text-sm text-gray-900 dark:text-gray-100">
                                            Edit
                                        </a>
                                        
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 text-sm">
                                        No tasks assigned.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
