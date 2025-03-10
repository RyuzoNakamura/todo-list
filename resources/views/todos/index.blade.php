@section('title', 'Todoリスト')
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Todo作成フォーム -->
                    <form method="POST" action="{{ route('todos.store') }}" class="mb-6">
                        @csrf
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <x-text-input name="title" placeholder="新しいタスク" class="w-full sm:flex-1" />
                            <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-4">
                                <input type="date" name="due_date"
                                    class="rounded-md border-gray-300 w-full sm:w-auto">
                                <select name="priority" class="rounded-md border-gray-300 w-full sm:w-auto">
                                    @foreach ($priorities as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <div class="w-full sm:w-auto">
                                    <x-primary-button class="w-full justify-center sm:w-auto">追加</x-primary-button>
                                </div>
                            </div>
                        </div>

                        <!-- エラーメッセージ表示部分を追加 -->
                        @if ($errors->any())
                            <div class="mt-2 p-4 bg-red-50 rounded-lg">
                                <ul class="text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </form>

                    {{-- 表示オプション --}}
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('todos.index', ['filter' => 'all']) }}"
                                class="px-3 py-2 text-sm rounded-lg {{ request('filter', 'all') === 'all' ? 'bg-blue-500 text-black' : 'bg-gray-200 text-gray-700' }}">
                                すべて
                            </a>
                            <a href="{{ route('todos.index', ['filter' => 'active']) }}"
                                class="px-3 py-2 text-sm rounded-lg {{ request('filter') === 'active' ? 'bg-blue-500 text-black' : 'bg-gray-200 text-gray-700' }}">
                                未完了のみ
                            </a>
                            <a href="{{ route('todos.index', ['filter' => 'completed']) }}"
                                class="px-3 py-2 text-sm rounded-lg {{ request('filter') === 'completed' ? 'bg-blue-500 text-black' : 'bg-gray-200 text-gray-700' }}">
                                完了済みのみ
                            </a>
                        </div>

                        <!-- Todo一覧 -->
                        <div class="space-y-4">
                            @foreach ($todos as $todo)
                                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                                    <form method="POST" action="{{ route('todos.toggle-complete', $todo) }}">
                                        @csrf
                                        @method('PATCH')
                                        {{-- チェックボックス --}}
                                        <input type="checkbox" class="todo-checkbox rounded"
                                            onchange="this.form.submit()" data-todo-id="{{ $todo->id }}"
                                            {{ $todo->is_completed ? 'checked' : '' }}>
                                    </form>

                                    <div class="flex-1">
                                        <h3
                                            class="font-semibold {{ $todo->is_completed ? 'line-through text-gray-400' : '' }}">
                                            {{ $todo->title }}
                                        </h3>
                                        @if ($todo->due_date)
                                            <p class="text-sm text-gray-600">期限: {{ $todo->due_date->format('Y/m/d') }}
                                            </p>
                                        @endif
                                    </div>

                                    <span
                                        class="px-2 py-1 text-sm rounded-full
                                    {{ $todo->priority === 'high'
                                        ? 'bg-red-100 text-red-800'
                                        : ($todo->priority === 'medium'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-blue-100 text-blue-800') }}">
                                        {{ $priorities[$todo->priority] }}
                                    </span>

                                    <a href="{{ route('todos.edit', $todo) }}"
                                        class="text-gray-500 hover:text-gray-700">
                                        編集
                                    </a>

                                    <form method="POST" action="{{ route('todos.destroy', $todo) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700">削除</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
