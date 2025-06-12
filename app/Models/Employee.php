<?php

namespace App\Models;

use App\Enums\EmployeeRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'role',
        'description',
        'hobbies',
        'status',
    ];

    protected $casts = [
        'role' => EmployeeRole::class,
        'hobbies' => 'array',
        'status' => 'string',
    ];

    public function sentEmails(): HasMany
    {
        return $this->hasMany(SentEmail::class);
    }

    /**
     * Send an email to the employee and create a record in sent_emails table
     *
     * @param string $subject
     * @param string $content
     * @param array $meta Additional metadata to store with the email
     * @return \App\Models\SentEmail
     * @throws \Exception If email sending fails
     */
    public function sendEmail(string $subject, string $content, array $meta = []): SentEmail
    {
        try {
            // Create a record in sent_emails table
            $sentEmail = $this->sentEmails()->create([
                'subject' => $subject,
                'content' => $content,
                'sent_at' => now(),
                'status' => 'sent',
                'meta' => $meta,
            ]);

            // Update employee status if needed
            if ($this->status === 'none') {
                $this->update(['status' => 'in_progress']);
            }

            return $sentEmail;
        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('Failed to send email to employee ' . $this->id . ': ' . $e->getMessage());
            
            // Create a failed email record
            $sentEmail = $this->sentEmails()->create([
                'subject' => $subject,
                'content' => $content,
                'sent_at' => now(),
                'status' => 'failed',
                'meta' => array_merge($meta, [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString(),
                ]),
            ]);

            throw $e;
        }
    }
}
