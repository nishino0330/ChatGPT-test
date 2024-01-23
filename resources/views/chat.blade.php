<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8' />
</head>

<body>
    <h1>AI Assistant</h1>
    <p>ChatGPT APIを使ったチャットボットです。</p>
    <p>メッセージを入力してください。</p>
    {{-- フォーム --}}
    <form method="POST">
        @csrf
        <textarea rows="10" cols="50" name="sentence" required></textarea>
        <button type="submit">送信</button>
    </form>

    {{-- エラーメッセージ --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- 結果 --}}
    <p>chatGPT : {{ isset($chat_response) ? $chat_response : '' }}</p>
    <p>user : {{ isset($sentence) ? $sentence : '' }}</p>
</body>

</html>
