<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    private $aluno;

    public function __construct(Aluno $aluno) {
        $this->aluno = $aluno;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aluno = $this->aluno->get();
        return Response()->json($aluno, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $aluno = $this->aluno->create([
            'nome' => $request->nome,
            'matricula'=> $request->matricula,
            'data_nascimento' => $request->data_nascimento,
            'sexo' => $request->sexo,
            'serie' => $request->serie,
            'curso' => $request->curso,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'imagem' => $request->imagem
        ]);

        return Response()->json($aluno, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $aluno = $this->aluno->find($id);

        return Response()->json($aluno, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $aluno = $this->aluno->find($id);

        if ($request->method() === 'PATCH') {

            $aluno->fill($request->all());
            $aluno->save();
        }

        return Response()->json($aluno, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $aluno = $this->aluno->find($id);
        $aluno->delete();

        return Response()->json(['msg' => 'Aluno(a), excluido com sucesso']);
    }
}
