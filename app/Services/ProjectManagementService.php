<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\Integration;
use App\Models\Milestone;
use Illuminate\Support\Facades\Log;

class ProjectManagementService
{
    protected $notificationService;
    protected $analyticsService;

    public function __construct(
        NotificationService $notificationService,
        AnalyticsService $analyticsService
    ) {
        $this->notificationService = $notificationService;
        $this->analyticsService = $analyticsService;
    }

    public function createProject($data)
    {
        try {
            $project = Project::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'client_id' => $data['client_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'budget' => $data['budget'],
                'status' => 'planning',
                'team_id' => $this->assignTeam($data['requirements']),
                'milestones' => $this->createMilestones($data['timeline']),
                'integrations' => $this->setupIntegrations($data['integrations'])
            ]);

            // إنشاء خطة المشروع
            $this->createProjectPlan($project, $data);

            // إعداد التكاملات
            $this->setupProjectIntegrations($project, $data['integrations']);

            return $project;
        } catch (\Exception $e) {
            Log::error('Error creating project: ' . $e->getMessage());
            throw $e;
        }
    }

    public function manageProjectTasks($projectId)
    {
        $project = Project::findOrFail($projectId);
        
        return [
            'tasks' => $this->getTasks($project),
            'dependencies' => $this->getTaskDependencies($project),
            'assignments' => $this->getTaskAssignments($project),
            'progress' => $this->calculateProgress($project),
            'timeline' => $this->getProjectTimeline($project)
        ];
    }

    public function trackProjectProgress($projectId)
    {
        $project = Project::findOrFail($projectId);
        
        return [
            'milestones' => $this->trackMilestones($project),
            'tasks_completion' => $this->calculateTasksCompletion($project),
            'budget_utilization' => $this->trackBudget($project),
            'team_performance' => $this->evaluateTeamPerformance($project),
            'client_feedback' => $this->getClientFeedback($project)
        ];
    }

    public function generateProjectReports($projectId, $type = 'all')
    {
        $project = Project::findOrFail($projectId);
        
        $reports = [
            'progress' => $this->generateProgressReport($project),
            'financial' => $this->generateFinancialReport($project),
            'resources' => $this->generateResourceReport($project),
            'quality' => $this->generateQualityReport($project),
            'risks' => $this->generateRiskReport($project)
        ];

        return $type === 'all' ? $reports : $reports[$type] ?? null;
    }

    public function manageProjectResources($projectId)
    {
        $project = Project::findOrFail($projectId);
        
        return [
            'team_allocation' => $this->allocateTeamMembers($project),
            'equipment' => $this->manageEquipment($project),
            'software_licenses' => $this->manageLicenses($project),
            'external_resources' => $this->manageExternalResources($project)
        ];
    }

    protected function createProjectPlan($project, $data)
    {
        // إنشاء المراحل
        foreach ($data['phases'] as $phase) {
            $milestone = Milestone::create([
                'project_id' => $project->id,
                'name' => $phase['name'],
                'description' => $phase['description'],
                'due_date' => $phase['due_date'],
                'deliverables' => $phase['deliverables']
            ]);

            // إنشاء المهام لكل مرحلة
            foreach ($phase['tasks'] as $taskData) {
                Task::create([
                    'milestone_id' => $milestone->id,
                    'name' => $taskData['name'],
                    'description' => $taskData['description'],
                    'assigned_to' => $taskData['assigned_to'],
                    'start_date' => $taskData['start_date'],
                    'end_date' => $taskData['end_date'],
                    'priority' => $taskData['priority'],
                    'dependencies' => $taskData['dependencies'] ?? []
                ]);
            }
        }
    }

    protected function setupProjectIntegrations($project, $integrations)
    {
        foreach ($integrations as $integration) {
            Integration::create([
                'project_id' => $project->id,
                'service' => $integration['service'],
                'config' => $integration['config'],
                'status' => 'active'
            ]);

            switch ($integration['service']) {
                case 'github':
                    $this->setupGitHubIntegration($project, $integration);
                    break;
                case 'jira':
                    $this->setupJiraIntegration($project, $integration);
                    break;
                case 'slack':
                    $this->setupSlackIntegration($project, $integration);
                    break;
                // إضافة المزيد من التكاملات
            }
        }
    }

    protected function setupGitHubIntegration($project, $config)
    {
        return [
            'repository' => $this->createGitHubRepo($project),
            'webhooks' => $this->setupGitHubWebhooks($project),
            'branch_protection' => $this->setupBranchProtection($project),
            'automated_workflows' => $this->setupGitHubActions($project)
        ];
    }

    protected function setupJiraIntegration($project, $config)
    {
        return [
            'project' => $this->createJiraProject($project),
            'boards' => $this->setupJiraBoards($project),
            'workflows' => $this->setupJiraWorkflows($project),
            'automations' => $this->setupJiraAutomations($project)
        ];
    }

    protected function trackMilestones($project)
    {
        $milestones = $project->milestones;
        $trackedData = [];

        foreach ($milestones as $milestone) {
            $trackedData[$milestone->id] = [
                'progress' => $this->calculateMilestoneProgress($milestone),
                'status' => $this->getMilestoneStatus($milestone),
                'delays' => $this->checkMilestoneDelays($milestone),
                'risks' => $this->assessMilestoneRisks($milestone)
            ];
        }

        return $trackedData;
    }

    protected function evaluateTeamPerformance($project)
    {
        return [
            'productivity' => $this->calculateTeamProductivity($project),
            'quality' => $this->assessWorkQuality($project),
            'collaboration' => $this->measureTeamCollaboration($project),
            'skills_gap' => $this->identifySkillsGap($project)
        ];
    }

    // ... المزيد من الوظائف المساعدة ...
} 