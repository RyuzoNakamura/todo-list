<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
		$filter = $request->query('filter', 'all');

		$query = auth()->user()->todos();

		if ($filter === 'active') {
			$query->where('is_completed', false);
		} elseif ($filter === 'completed') {
			$query->where('is_completed', true);
		}

		$todos = $query->orderBy('due_date')
			->orderByPriorityDesc()
			->get();

		return view('todos.index', [
			'todos' => $todos,
			'priorities' => Todo::priorities(),
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
		$request->user()->todos()->create($validated);
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
		$todo->update($validated);
		return redirect()->route('todos.index');
	}

	public function toggleComplete(Todo $todo)
	{
		$todo->update([
			'is_completed' => !$todo->is_completed,
		]);

		return redirect()->back();
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
