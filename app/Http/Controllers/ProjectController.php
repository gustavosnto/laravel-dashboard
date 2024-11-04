<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Business;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    // Método para exibir a lista de projetos
    public function index()
    {
        $projects = Project::with('business')->get(); // Carrega os projetos com os dados da empresa
        return Inertia::render('Dashboard/Projects/Index', [
            'projects' => $projects,
        ]);
    }

    // Método para criar um novo projeto
    public function create()
    {
        // Carregue as empresas para o dropdown
        $businesses = Business::all();
        return Inertia::render('Dashboard/Projects/Create', [
            'businesses' => $businesses,
        ]);
    }

    // Método para armazenar um novo projeto
    public function store(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'technology' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'additional_info' => 'nullable|string',
        ]);

        Project::create($request->all());

        return redirect()->route('projects.index'); // Redireciona para a lista de projetos
    }

    // Método para mostrar um projeto específico
    public function show($id)
    {
        $project = Project::with('business')->findOrFail($id);
        return Inertia::render('Dashboard/Projects/Show', [
            'project' => $project,
        ]);
    }

    // Método para editar um projeto
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $businesses = Business::all(); // Carrega as empresas para o dropdown
        return Inertia::render('Dashboard/Projects/Edit', [
            'project' => $project,
            'businesses' => $businesses,
        ]);
    }

    // Método para atualizar um projeto
    public function update(Request $request, $id)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'technology' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'additional_info' => 'nullable|string',
        ]);

        $project = Project::findOrFail($id);
        $project->update($request->all());

        return redirect()->route('projects.index'); // Redireciona para a lista de projetos
    }

    // Método para excluir um projeto
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index'); // Redireciona para a lista de projetos
    }
}
