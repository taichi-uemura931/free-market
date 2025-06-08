@extends('layouts.app')

@section('title', '取引チャット')

@section('content')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">

<div class="chat-container">
    <div class="chat-sidebar">
        <h3>取引一覧</h3>
        <ul class="transaction-list">
            @foreach($transactions as $t)
                @php
                    $lastMessage = $t->messages->last();
                    $unreadCount = $t->unread_messages_count;
                @endphp
                <li class="transaction-card {{ $t->id === $transaction->id ? 'active' : '' }}">
                    <a href="{{ route('chat.show', $t->id) }}" class="transaction-link">
                        <div class="transaction-content">
                            <span class="transaction-title">{{ $t->product->product_name }}</span>
                            <span class="last-message">
                                {{ Str::limit($t->latestMessage ? $t->latestMessage->content : '（メッセージなし）', 25) }}
                            </span>
                        </div>
                        @if($unreadCount > 0)
                            <span class="notification-dot">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="chat-main">
        <div class="chat-header">
            <h2>{{ $transaction->product->product_name }} の取引チャット</h2>
        </div>

        <div class="chat-messages" id="chat-messages">
            @foreach($transaction->messages->sortBy('created_at') as $message)
            <div class="chat-message {{ $message->sender_id === Auth::id() ? 'own' : 'other' }}">
                <div class="message-content">
                    {{ $message->content }}
                    @if ($message->image_path)
                        <div class="chat-image">
                            <img src="{{ Storage::url($message->image_path) }}" alt="投稿画像">
                        </div>
                    @endif
                </div>
                <div class="message-meta">
                    <span>{{ $message->sender->username }}</span>
                    <span>{{ $message->created_at->format('Y-m-d H:i') }}</span>
                </div>
                @if ($message->sender_id === Auth::id())
                    <div class="button-row">
                        <button class="edit-button" data-id="{{ $message->id }}" data-content="{{ $message->content }}">編集</button>
                        <button class="delete-button" data-id="{{ $message->id }}">削除</button>
                    </div>
                @endif
            </div>
            @endforeach
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="error">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('chat.store', $transaction->id) }}" method="POST" enctype="multipart/form-data" class="chat-form">
            @csrf
            <div class="form-row">
                <textarea name="content" placeholder="メッセージを入力">{{ old('content') }}</textarea>

                <label for="imageInput" class="submit-button">画像を選択</label>
                <input type="file" name="image" id="imageInput" accept=".jpeg,.jpg,.png" style="display: none">

                <div id="imagePreview" class="image-preview-box">
                    <span class="preview-placeholder">画像のプレビューが表示</span>
                </div>

                <button type="submit" class="submit-button">送信</button>
            </div>
        </form>

        @if(Auth::id() === $transaction->buyer_id || Auth::id() === $transaction->seller_id)
            <div style="text-align: center;">
                <button class="complete-button" onclick="openModal()">取引完了</button>
            </div>
        @endif

        <div id="rating-modal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <h3 class="modal-title">取引完了・ユーザーを評価</h3>
                <form id="review-form" action="{{ route('review.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                    <input type="hidden" name="reviewee_id" value="{{ $transaction->buyer_id === Auth::id() ? $transaction->seller_id : $transaction->buyer_id }}">

                    <label class="form-label">評価（1〜5）</label>
                    <select name="rating" class="form-select" required>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>

                    <label class="form-label">コメント（任意）</label>
                    <textarea name="comment" class="form-textarea" rows="3" placeholder="コメントを入力"></textarea>

                    <div class="modal-buttons">
                        <button type="button" class="modal-submit" onclick="openConfirmModal()">送信</button>
                        <button type="button" class="modal-close" onclick="closeModal()">キャンセル</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="confirm-modal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <h3 class="modal-title">本当に送信しますか？</h3>
                <p>評価が完了するとこのユーザーとはチャットができなくなりますがよろしいですか？</p>
                <div class="modal-buttons">
                    <button type="button" class="modal-submit" onclick="submitReview()">送信</button>
                    <button type="button" class="modal-close" onclick="closeConfirmModal()">キャンセル</button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatBox = document.getElementById('chat-messages');
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.querySelector('.image-preview-box');

    const placeholder = document.querySelector('.preview-placeholder');
    imageInput.addEventListener('change', function () {
        imagePreview.innerHTML = '';
        const file = this.files[0];
        if (file && file.type.match('image.*')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('preview-image');
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });

    imageInput.addEventListener('change', function () {
        imagePreview.innerHTML = '';
        const file = this.files[0];
        if (file && file.type.match('image.*')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('preview-image');
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
});

$(function () {
    $('.edit-button').on('click', function () {
        const id = $(this).data('id');
        const content = prompt('新しい内容を入力してください：', $(this).data('content'));
        if (content) {
            fetch(`/messages/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ content })
            }).then(res => location.reload());
        }
    });

    $('.delete-button').on('click', function () {
        const id = $(this).data('id');
        if (confirm('このメッセージを削除しますか？')) {
            fetch(`/messages/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(res => location.reload());
        }
    });
});

$(function () {
    $(document).on('click', function (e) {
        if ($(e.target).closest('#rating-modal form, .complete-button').length === 0) {
            $('#rating-modal').fadeOut();
        }
    });

    $(document).on('keydown', function (e) {
        if (e.key === "Escape") {
            $('#rating-modal').fadeOut();
        }
    });
});

function openModal() {
    document.getElementById('rating-modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('rating-modal').style.display = 'none';
}

function openConfirmModal() {
    document.getElementById('confirm-modal').style.display = 'flex';
}

function closeConfirmModal() {
    document.getElementById('confirm-modal').style.display = 'none';
}

function submitReview() {
    document.getElementById('review-form').submit();
}

</script>
@endpush
