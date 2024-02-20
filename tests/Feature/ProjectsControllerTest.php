<?php

namespace Tests\Feature;

use App\Http\Controllers\ProjectsController;
use App\Models\Project;
use App\Models\User;
use App\Repositories\ProjectRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsControllerTest extends TestCase
{

    use RefreshDatabase;

    protected $projectsController;
    protected $user;

    public function setUp(): void 
    {
        parent::setUp();
        $this->projectsController = new ProjectsController(new ProjectRepository(new Project()));
        $this->user = User::factory()->create(); 
    }
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
    // Acting as authenticated user
    $this->actingAs($this->user);

        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_method_index(): void
    {
        // Acting as authenticated user
        $this->actingAs($this->user);
    
        // Create data using factory
        $projects = Project::factory()->create();

        // Call the index method of the controller
        $response = $this->projectsController->index();
        
        
        // Assert that the returned value is an instance of Illuminate\View\View
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
    
        // Assert that the response is successful
        $response->assertSuccessful();
    
        // Assert that the view returned by the index method is the expected view
        $response->assertViewIs('projects.index');
    
        // Assert that the view contains the $projects data
        $response->assertViewHas('projects', $projects);
    
        // Assert that the response content contains specific HTML elements or text
        $response->assertSee('Projects List');
    
        // Assert that the response content contains the project name
        $response->assertSee($projects->name);
    
        // Assert that the response content contains the project description
        $response->assertSee($projects->description);
    
        // Assert that the response content does not contain certain HTML elements or text
        $response->assertDontSee('No projects found');
    }
    
}
