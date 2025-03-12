<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TodoController extends Controller
{
	/**
	 * 一覧表示
	 * 期限日順で並び替え
	 * 優先度による並び替え
	 * ソート順を変更できるようにする
	 */
	public function index(Request $request): View
	{
		return $this->indexSqlVersion($request);
	}

	private function indexOrmVersion(Request $request): View
	{
		$filter = $request->query('filter', 'all');

		$query = auth()->user()->todos();

		if ($filter === 'active') {
			$query->where('is_completed', false);
		} elseif ($filter === 'completed') {
			$query->where('is_completed', true);
		}

		$query->orderBy('due_date')->orderByPriorityDesc();

		$todos = $query->get();

		return view('todos.index', [
			'todos' => $todos,
			'priorities' => Todo::priorities(),
			'filter' => $filter,
		]);
	}

	private function indexSqlVersion(Request $request): View
	{
		$filter = $request->query('filter', 'all');

		$userId = Auth::id();
		$sql = 'SELECT * FROM todos WHERE todos.user_id = ?';
		$bindings = [$userId];

		if ($filter === 'active') {
			$sql .= ' AND is_completed = ?';
			$bindings[] = 0;
		} elseif ($filter === 'completed') {
			$sql .= ' AND is_completed = ?';
			$bindings[] = 1;
		}

		$sql .= ' ORDER BY due_date ASC, priority DESC';

		$todos = DB::select($sql, $bindings);

		$todoCollection = collect($todos)->map(function ($todo) {
			$todoModel = new \App\Models\Todo((array)$todo);
			$todoModel->forceFill((array)$todo);
			$todoModel->id = $todo->id;
			$todoModel->exists = true;
			return $todoModel;
		});

		return view('todos.index', [
			'todos' => $todoCollection,
			'priorities' => \App\Models\Todo::priorities(),
			'filter' => $filter,
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * todoの追加
	 * 
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'description' => 'nullable|string,',
			'due_date' => 'nullable|date',
			'priority' => 'required|in:low,medium,high',
		]);
		$query = $request->user()->todos()->create($validated);
		$sql = $query->toSql();
		return redirect()->route('todos.index');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Todo $todo)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Todo $todo): View
	{
		Gate::authorize('update', $todo);
		return view('todos.edit', [
			'todo' => $todo,
			'priorities' => Todo::priorities(),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Todo $todo): RedirectResponse
	{
		Gate::authorize('update', $todo);
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'description' => 'nullable|string',
			'due_date' => 'nullable|date',
			'priority' => 'required|in:low,medium,high',
			'is_completed' => 'boolean',
		]);

		$sql = 'UPDATE todos SET title = ?, description = ?, due_date = ?, priority = ?, is_completed = ? WHERE id = ?';
		$bindings = [
			$validated['title'],
			$validated['description'],
			$validated['due_date'],
			$validated['priority'],
			$validated['is_completed'],
			$todo->id,
		];

		DB::update($sql, $bindings);

		return redirect()->route('todos.index');
	}

	public function toggleComplete(Todo $todo): RedirectResponse
	{
		$todo->update([
			'is_completed' => !$todo->is_completed,
		]);

		return redirect()->route('todos.index');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Todo $todo): RedirectResponse
	{
		Gate::authorize('delete', $todo);
		$todo->delete();
		return redirect()->route('todos.index');
	}
}
