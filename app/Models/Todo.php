<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
	// Todo追加時に一括でDBに割り当てる属性
	protected $fillable = [
		'title',
		'description',
		'is_completed',
		'due_date',
		'priority'
	];

	// 属性を型変換
	protected $casts = [
		'is_completed' => 'boolean',
		'due_date' => 'date',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public const PRIORITY_LOW = 'low';
	public const PRIORITY_MEDIUM = 'medium';
	public const PRIORITY_HIGH = 'high';

	public static function priorities(): array
	{
		return [
			self::PRIORITY_LOW => '低',
			self::PRIORITY_MEDIUM => '中',
			self::PRIORITY_HIGH => '高',
		];
	}

	// 優先度を降順で並び替える
	public function scopeOrderByPriorityDesc($query)
	{
		return $query->orderBy('priority', 'desc');
	}
}
