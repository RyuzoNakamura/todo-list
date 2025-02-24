@section('title', 'Todo編集')
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('todos.update', $todo) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="title" value="タイトル" />
                            <x-text-input id="title" name="title" value="{{ $todo->title }}" class="w-full" />
                        </div>

                        <div>
                            <x-input-label for="description" value="説明" />
                            <textarea id="description" name="description" class="w-full rounded-md border-gray-300">{{ $todo->description }}</textarea>
                        </div>

                        <div>
                            <x-input-label for="due_date" value="期限日" />
                            <input type="date" id="due_date" name="due_date"
                                value="{{ $todo->due_date?->format('Y-m-d') }}" class="rounded-md border-gray-300">
                        </div>

                        <div>
                            <x-input-label for="priority" value="優先度" />
                            <select id="priority" name="priority" class="rounded-md border-gray-300">
                                @foreach ($priorities as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ $todo->priority === $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_completed" value="1"
                                    {{ $todo->is_completed ? 'checked' : '' }} class="rounded">
                                <span class="ml-2">完了</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>更新</x-primary-button>
                            <a href="{{ route('todos.index') }}" class="text-gray-600 hover:text-gray-900">キャンセル</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
