<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use App\Http\Controllers\FrequenciaController;
use Illuminate\Support\Facades\Log;

class AlunoController extends Controller
{
    private $aluno;

    public function __construct(Aluno $aluno)
    {
        $this->aluno = $aluno;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paginado = $request->input('per_page', 99999);
        $pagina = $request->input('page', 1);
 
        $aluno = Aluno::with('frequencias')->paginate($paginado, ['*'], 'page', $pagina);
        return Response()->json($aluno, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nome' => 'required|string',
                'matricula' => 'required|numeric',
                'data_nascimento' => 'required|string',
                'sexo' => 'required|string',
                'email' => 'required|string|email',
                'telefone' => 'required|string',
                'senha' => 'required|string|min:6',
                'curso' => 'required|string',
                'serie' => 'required|string'
            ]);

            $validatedData['data_nascimento'] = \DateTime::createFromFormat('d/m/Y', $validatedData['data_nascimento']);

            $validatedData['data_nascimento'] = $validatedData['data_nascimento']->format('Y-m-d');

            $aluno = $this->aluno->create($validatedData);

            return response()->json($aluno, 201);
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar aluno: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao cadastrar aluno, tente novamente mais tarde.'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $aluno = $this->aluno->find($id);

        return Response()->json($aluno, 200);
    }

    public function validarSenha(Request $request, $id)
    {
        $aluno = $this->aluno->find($id);

        if (!$aluno) {
            return response()->json(['message' => 'Aluno não encontrado'], 404);
        }

        if ($aluno->senha === $request->input('senha')) {

            app(FrequenciaController::class)->registrarAcesso($request->merge(['id_aluno' => $id]));

            return response()->json(['acessoPermitido' => true]);
        } else {
            return response()->json(['acessoPermitido' => false]);
        }
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
