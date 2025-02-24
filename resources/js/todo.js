document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.todo-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const todoId = this.dataset.todoId;
            
            // CSRFトークンの取得
            const token = document.querySelector('meta[name="csrf-token"]').content;
            
            // 非同期リクエストの送信
            fetch(`/todos/${todoId}/toggle-complete`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // 成功時の視覚的フィードバック
                const todoTitle = this.closest('.flex').querySelector('.todo-title');
                if (this.checked) {
                    todoTitle.classList.add('line-through', 'text-gray-400');
                } else {
                    todoTitle.classList.remove('line-through', 'text-gray-400');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // エラー時はチェックボックスを元に戻す
                this.checked = !this.checked;
                alert('更新に失敗しました');
            });
        });
    });
});