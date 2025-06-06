<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class TaskController extends Controller
{
    protected $authHeader;
    protected $baseUrl;

    public function __construct()
    {
        // Optional 1 for dynamic use:
        // $user = Auth::user();
        // $jiraEmail = $user->jira_email;
        // $jiraApiToken = Crypt::decryptString($user->jira_api_token);

        // Optional 2 for static use:
        $jiraEmail = env('JIRA_EMAIL');
        $jiraApiToken = env('JIRA_API_KEY');
        $this->baseUrl = env('JIRA_BASE_URL'); 
        $token = base64_encode($jiraEmail . ':' . $jiraApiToken);
        $this->authHeader = [
            'Authorization' => 'Basic ' . $token,
            'Accept' => 'application/json',
        ];
    }
    public function editTask($key){
        $issueUrl = "{$this->baseUrl}/rest/api/3/issue/{$key}?fields=summary,description,status";
        $transitionsUrl = "{$this->baseUrl}/rest/api/3/issue/{$key}/transitions";

        $issueResponse = Http::withHeaders($this->authHeader)->get($issueUrl);
        $transitionsResponse = Http::withHeaders($this->authHeader)->get($transitionsUrl);

        if ($issueResponse->successful() && $transitionsResponse->successful()) {
            $task = $issueResponse->json();
            $transitions = $transitionsResponse->json()['transitions'];
            return view('tasks.edit', compact('task', 'transitions'));
        }

        return redirect()->route('dashboard')->withErrors('Unable to fetch task details.');
    }
    public function updateTask(Request $request, $key)
    {
        $request->validate([
            'summary' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $payload = [
            'fields' => [
                'summary' => $request->summary,
                'description' => [
                    'type' => 'doc',
                    'version' => 1,
                    'content' => [[
                        'type' => 'paragraph',
                        'content' => [[ 'type' => 'text', 'text' => $request->description ]]
                    ]]
                ]
            ]
        ];

        $transition = Http::withHeaders($this->authHeader)
            ->post("{$this->baseUrl}/rest/api/3/issue/{$key}/transitions", [
                'transition' => ['id' => $request->status]
            ]);

        $update = Http::withHeaders($this->authHeader)
            ->put("{$this->baseUrl}/rest/api/3/issue/{$key}", $payload);

        if ($update->successful() || $transition->successful()) {
            return redirect()->route('dashboard')->with('success', 'Task updated successfully.');
        }

        return back()->withErrors('Update failed. Please try again.');
    }
}