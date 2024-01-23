<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatBot</title>
</head>
<body>

<div>
    <h1>AI Assistant</h1>
    
    <!-- 質問フォーム -->
    <form method="POST" action="{{ route('chat_bot-chat') }}">
        @csrf
        <input type="text" name="sentence" placeholder="Type your question">
        <button type="submit">Ask</button>
    </form>

    <!-- 過去の質問履歴 -->
    @if (!empty($chatHistory))
        <h2>トーク履歴</h2>
        <ul>
            @foreach ($chatHistory as $chat)
                <li>User: {{ $chat['user'] }}</li>
                <li>ChatBot: {{ $chat['response'] }}</li>
            @endforeach
        </ul>
    @endif

    <!-- 最新の質問と回答 -->
    @if (!empty($sentence) && !empty($chat_response))
        <h2>確認用</h2>
        <p>User: {{ $sentence }}</p>
        <p>ChatBot: {{ $chat_response }}</p>
    @endif
</div>

</body>
</html>
