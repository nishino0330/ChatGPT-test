<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatGptController extends Controller
{

    /**
     * index
     *
     * @param  Request  $request
     */
    public function index(Request $request)
    {
        return view('chat');
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

        $chat_log[] = $sentence;
        // ChatGPT API処理
        if(count($chat_log) == 1){
            $judge = 0;
        }else{
            $judge = 1;
        }
        $chat_response = $this->chat_gpt($sentence, $judge);

        return view('chat', compact('sentence', 'chat_response'));
    }

    /**
     * ChatGPT API呼び出し
     * ライブラリ
     */
    function chat_gpt($user, $judge)
    {
        // APIキー
        $api_key = env('CHAT_GPT_KEY');

        if($judge == 0){
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
        }

        if(count($data['messages']) == 1){
            // パラメータ
            $data['messages'][] = [
                "role" => "user",
                "content" => $user
            ];
        }else if(count($data['messages']) >= 2){
            $data['messages'][1] = $user;
        }

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
