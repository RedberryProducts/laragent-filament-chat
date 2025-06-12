<?php

namespace Database\Seeders;

use App\Enums\EmployeeRole;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    private array $firstNames = [
        'John', 'Emma', 'Michael', 'Sophia', 'William', 'Olivia', 'James', 'Ava', 'Robert', 'Isabella',
        'David', 'Mia', 'Joseph', 'Charlotte', 'Daniel', 'Amelia', 'Matthew', 'Harper', 'Andrew', 'Evelyn'
    ];

    private array $lastNames = [
        'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
        'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'
    ];

    private array $hobbies = [
        'Reading', 'Hiking', 'Photography', 'Cooking', 'Gaming',
        'Painting', 'Swimming', 'Cycling', 'Traveling', 'Gardening'
    ];

    private array $roleDescriptions = [
        'developer' => [
            'Experienced full-stack developer with expertise in modern web technologies and frameworks.',
            'Passionate about writing clean, efficient, and maintainable code. Enjoys solving complex technical challenges.',
            'Backend specialist with strong database design skills and a focus on system architecture.',
            'Frontend developer with an eye for design and a passion for creating responsive user interfaces.',
            'DevOps enthusiast with experience in CI/CD pipelines and cloud infrastructure.'
        ],
        'designer' => [
            'Creative UI/UX designer with a strong portfolio of user-centered design solutions.',
            'Visual designer with expertise in creating engaging brand identities and marketing materials.',
            'Interaction designer focused on creating intuitive and accessible user experiences.',
            'Motion graphics specialist with a passion for bringing interfaces to life through animation.',
            'Product designer with experience in user research and design thinking methodologies.'
        ],
        'project manager' => [
            'Certified PMP with a track record of delivering complex projects on time and within budget.',
            'Agile coach and scrum master with expertise in facilitating high-performing teams.',
            'Technical project manager with a background in software development and system architecture.',
            'Client-focused PM with excellent stakeholder management and communication skills.',
            'Strategic leader with experience in resource allocation and risk management.'
        ]
    ];

    private array $descriptions = [
        'Passionate about technology and innovation. Always eager to learn new things and take on challenges.',
        'Creative thinker with a keen eye for detail. Enjoys solving complex problems and collaborating with teams.',
        'Results-driven professional with excellent communication skills and a strong work ethic.',
        'Tech enthusiast who loves staying up-to-date with the latest industry trends and developments.',
        'Team player with a positive attitude and a passion for continuous improvement.'
    ];

    private array $domains = ['example.com', 'test.org', 'demo.net', 'company.com', 'business.io'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $firstName = $this->firstNames[array_rand($this->firstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $domain = $this->domains[array_rand($this->domains)];
            $email = strtolower($firstName[0] . $lastName . rand(1, 99) . '@' . $domain);
            
            // Generate 0-3 random hobbies
            $hobbiesCount = rand(0, 3);
            $employeeHobbies = [];
            if ($hobbiesCount > 0) {
                $hobbyKeys = array_rand($this->hobbies, $hobbiesCount);
                $hobbyKeys = is_array($hobbyKeys) ? $hobbyKeys : [$hobbyKeys];
                foreach ($hobbyKeys as $key) {
                    $employeeHobbies[] = $this->hobbies[$key];
                }
            }

            $role = $this->getRandomRole();
            $roleKey = $role->value;
            $roleDescriptions = $this->roleDescriptions[$roleKey] ?? $this->descriptions;
            $description = $roleDescriptions[array_rand($roleDescriptions)];

            Employee::create([
                'name' => $firstName,
                'surname' => $lastName,
                'email' => $email,
                'role' => $roleKey,
                'description' => $description,
                'hobbies' => !empty($employeeHobbies) ? $employeeHobbies : null,
            ]);
        }
    }

    private function getRandomRole(): EmployeeRole
    {
        $roles = EmployeeRole::cases();
        return $roles[array_rand($roles)];
    }
}
