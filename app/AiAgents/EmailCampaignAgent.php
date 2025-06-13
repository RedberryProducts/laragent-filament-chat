<?php

namespace App\AiAgents;

use LarAgent\Agent;
use App\Models\Employee;
use LarAgent\Attributes\Tool;

class EmailCampaignAgent extends Agent
{
    protected $model = 'gpt-4.1-2025-04-14';

    protected $history = 'session';

    protected $provider = 'default';

    protected $tools = [];

    protected ?Employee $employee = null;

    public function instructions()
    {
        $tools = $this->getTools();
        return view('prompts.email_campaing_agent.instructions', compact('tools'));
    }

    public function prompt($message)
    {
        return $message;
    }

    #[Tool('Get employee data using email')]
    public function getEmployeeData(string $email) {
        $this->employee = Employee::where('email', $email)->with('sentEmails')->first();
        return $this->employee;
    }

    #[Tool('Get detailed campaign plan')]
    public static function getCampaignPlan() {
        return view('prompts.email_campaing_agent.tools.campaign_plan')->render();
    }

    #[Tool('Send email to employee', [
        'subject' => 'personalized email subject',
        'content' => 'personalized email content in plain text',
    ])]
    public function sendEmail(string $subject, string $content) {
        $this->employee->sendEmail($subject, $content);
        return "Email sent successfully: " . $this->employee->email;
    }

    #[Tool('Mark campaign as completed')]
    public function markCampaignAsCompleted() {
        if(!$this->employee) {
            return "Employee not set, please use getEmployeeData tool first";
        }
        $this->employee->update(['status' => 'done']);
        return "Campaign marked as completed for employee: " . $this->employee->email;
    }

}
