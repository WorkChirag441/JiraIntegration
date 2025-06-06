<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    protected $authHeader;
    protected $baseUrl = 'https://chiragahuja.atlassian.net';

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
    
    public function index(){
        $projects = Http::withHeaders($this->authHeader)
            ->get("{$this->baseUrl}/rest/api/3/project/search");
        if($projects->successful()){
            $Jiraprojects = $projects->json()['values'];
        }else{
            $Jiraprojects = [];
        }
        $jql = 'assignee=currentUser() ORDER BY priority DESC';
        $fields = 'summary,key,status,priority,project,customfield_10007,created';

        $tasks = Http::withHeaders($this->authHeader)
            ->get("{$this->baseUrl}/rest/api/3/search", [
                'jql' => $jql,
                'fields' => $fields
            ]);
        if($tasks->successful()){
            $Jiratasks = $tasks->json()['issues'];
        }else{
            $Jiratasks = [];
        }
        return view('dashboard',compact('Jiraprojects','Jiratasks'));
    }
}