<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ProjectsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $projectRepository;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectRepository = new ProjectRepository(new Project());
        $this->user = User::factory()->create(); 
    }


// Test method: test_get_all_projects
public function test_get_all_projects() 
{
    // Acting as authenticated user
    $this->actingAs($this->user);

    // Creating a sample project for testing
    $existingProject = Project::factory()->create();
    
    // Getting all projects from the repository
    $projects = $this->projectRepository->getAll();

    // Asserting that the projects are not empty
    $this->assertNotEmpty($projects);

}


    /** @test */
    public function test_can_create_a_project()
    {
        // Given authenticated user
        $this->actingAs($this->user);

        // Given project data
        $projectData = [
            'name' => 'Test Project',
            'description' => 'This is a test project.',
        ];

        // When a project is created
        $project = $this->projectRepository->store($projectData);

        $this->assertDatabaseHas('projects', $projectData);

        // Then the project should be created successfully
        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals($projectData['name'], $project->name);
        $this->assertEquals($projectData['description'], $project->description);
    }

    /** @test */
    public function test_user_can_update_a_project()
    {
        // Given authenticated user
        $this->actingAs($this->user);

        // Given an existing project
        $existingProject = Project::factory()->create();

        // Given updated project data
        $updatedData = [
            'name' => 'Updated Title',
            'description' => 'Updated Description',
        ];

        // When updating the project
        $this->projectRepository->update($updatedData, $existingProject->id);

        // Check $updateData in the database.
        $this->assertDatabaseHas('projects', $updatedData);
        
        // Then the project should be updated successfully
        $updatedProject = Project::find($existingProject->id);
        $this->assertEquals($updatedData['name'], $updatedProject->name);
        $this->assertEquals($updatedData['description'], $updatedProject->description);
    }

    /** @test */
    public function test_user_can_delete_a_project()
    {
        // Given authenticated user
        $this->actingAs($this->user);

        // Given an existing project
        $existingProject = Project::factory()->create();

        // When deleting the project
        $this->projectRepository->delete($existingProject->id);

        // Then the project should be deleted from the database
        $this->assertDatabaseMissing('projects', ['id' => $existingProject->id]);
    }

}
