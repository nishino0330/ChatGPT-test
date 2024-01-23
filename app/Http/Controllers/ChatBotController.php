<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ChatBotController extends Controller
{
    /**
     * index
     *
     * @param  Request  $request
     */
    public function index(Request $request)
    {
        // 過去の質問履歴をセッションから取得
        $chatHistory = Session::get('chat_history', []);

        return view('chatbot', compact('chatHistory'));
    }

    /**
     * chat
     *
     * @param  Request  $request
     */
    public function chat(Request $request)
    {
        // バリデーション
        $request->validate([
            'sentence' => 'required',
        ], [
            // カスタムエラーメッセージ
            'sentence.required' => '文章を入力してください',
        ]);

        // 文章
        $sentence = $request->input('sentence');

        // 過去の質問履歴をセッションから取得
        $chatHistory = Session::get('chat_history', []);

        // ChatGPT API処理
        $chat_response = $this->chat_gpt($sentence);

        // 過去の質問履歴に追加
        $chatHistory[] = [
            'user' => $sentence,
            'response' => $chat_response,
        ];

        // 過去の質問履歴をセッションに保存
        Session::put('chat_history', $chatHistory);

        return view('chatbot', compact('sentence', 'chat_response', 'chatHistory'));
    }

    /**
     * ChatGPT API呼び出し
     * ライブラリ
     */
    function chat_gpt($user)
    {
        $api_key = env('CHAT_GPT_KEY');

        // パラメータ
        $data = array(
            "model" => "gpt-3.5-turbo-1106",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "あなたは優秀なアシスタントAIです。日本語で応答してください"
                ]
            ]
        );

        // 過去の質問履歴を取得
        $chatHistory = Session::get('chat_history', []);

        // 過去の質問があればそれを追加
        if (count($chatHistory) > 0) {
            $data['messages'][] = [
                "role" => "user",
                "content" => $chatHistory[count($chatHistory) - 1]['user']
            ];
        }

        // 現在の質問を追加
        $data['messages'][] = [
            "role" => "user",
            "content" => $user
        ];

        // APIにリクエストを送信
        $openaiClient = \Tectalic\OpenAi\Manager::build(
            new \GuzzleHttp\Client(),
            new \Tectalic\OpenAi\Authentication($api_key)
        );

        try {
            $response = $openaiClient->chatCompletions()->create(
                new \Tectalic\OpenAi\Models\ChatCompletions\CreateRequest($data)
            )->toModel();

            return $response->choices[0]->message->content;
        } catch (\Exception $e) {
            return "ERROR";
        }
    }

}
